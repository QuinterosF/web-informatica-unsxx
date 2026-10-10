# Plan SDD - Módulo de Laboratorios

## 1. Estructura de Datos

### Tabla: `laboratorio`

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id_lab` | `INT UNSIGNED` | PK, AI | Identificador único |
| `nombre_lab` | `VARCHAR(100)` | NOT NULL, UNIQUE | Nombre canónico (ej: "Seguridad Informática") |
| `resp_acad_id` | `INT UNSIGNED` | FK → `academico.id_acad`, NULLABLE | Docente responsable (académico) |
| `resp_usu_id` | `INT UNSIGNED` | FK → `usuario.usu_id`, NULLABLE | Responsable con acceso al sistema |
| `horario_lab` | `VARCHAR(255)` | NULLABLE | Nombre archivo PDF (UUID + extensión) |
| `activo` | `TINYINT(1)` | DEFAULT 1 | Estado activo/inactivo |

> **Nota**: La tabla ya existe en la BD v2 (ver `database_v2.sql`). Campos coinciden con `plan-001.md`.

---

## 2. Relaciones

### LEFT JOIN laboratorio ↔ academico

```sql
SELECT 
    l.id_lab,
    l.nombre_lab,
    l.horario_lab,
    l.activo,
    a.id_acad,
    a.nom_acad AS responsable_nombre,
    a.email_acad AS responsable_email
FROM laboratorio l
LEFT JOIN academico a ON l.resp_acad_id = a.id_acad
WHERE l.activo = 1
ORDER BY l.nombre_lab;
```

- **LEFT JOIN** porque un laboratorio puede no tener responsable asignado (`resp_acad_id` NULLABLE)
- Relación 1:N: un académico puede ser responsable de múltiples laboratorios

---

## 3. Arquitectura MVC

### 3.1 Modelo: `src/Models/Lab.php`

```php
namespace App\Models;

use App\Config\Database;
use PDO;

class Lab {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function findAll(): array { /* JOIN academico */ }
    public function findById(int $id): ?array { /* JOIN academico */ }
    public function create(array $data): int { /* validaciones + INSERT */ }
    public function update(int $id, array $data): bool { /* validaciones + UPDATE */ }
    public function delete(int $id): bool { /* validación existencia + DELETE */ }
    public function getActiveLabs(): array { /* solo activos para selects */ }
    private function generatePdfUuid(string $ext): string { /* UUID para PDF */ }
}
```

### 3.2 Controlador: `src/Controllers/LabController.php`

```php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Lab;

class LabController extends Controller {
    private Lab $model;

    public function __construct() { $this->model = new Lab(); }

    public function index(): void { /* listado con JOIN + Simple-DataTables */ }
    public function create(): void { /* formulario + select académicos */ }
    public function store(): void { /* validación + create + redirect + flash */ }
    public function edit(int $id): void { /* buscar + formulario + select académicos */ }
    public function update(int $id): void { /* validación + update + redirect + flash */ }
    public function destroy(int $id): void { /* validar + delete + redirect + flash */ }
    private function generatePdfUuid(string $ext): string { /* UUID para PDF */ }
}
```

### 3.3 Vistas: `src/Views/lab/`

```
src/Views/lab/
├── index.php      # Listado tabla (tarjeta responsive, scroll horizontal + Simple-DataTables)
├── create.php     # Formulario (nombre, select responsable, file PDF con validación)
└── edit.php       # Formulario pre-poblado + PDF actual + file nuevo
```

---

## 4. Rutas (`src/routes.php`)

```php
/* ==================== CRUD LABORATORIOS (protegido) ==================== */
$router->get('/labs', [LabController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/labs/create', [LabController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs', [LabController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/labs/{id}/edit', [LabController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs/{id}/edit', [LabController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs/{id}', [LabController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/labs/{id}/delete', [LabController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);
```

---

## 5. Reglas de Negocio Específicas

| Regla | Detalle |
|-------|---------|
| **horario_lab** | String con nombre archivo PDF (ej: `lab-horario-seg-abc123.pdf`). Se genera UUID en PHP (`bin2hex(random_bytes(16)) . '.' . $ext`). Validación MIME + extensión PDF obligatoria. |
| **nombre_lab** | Único, creación dinámica (VARCHAR). |
| **responsable** | Select cargado dinámicamente desde `academico` (solo activos). Guarda `resp_acad_id` (FK academico) y opcionalmente `resp_usu_id` si el académico tiene usuario sistema. |
| **Notificaciones** | SweetAlert2 vía `$_SESSION['success']` / `$_SESSION['error']` (ya implementado en layout). Toast en esquina sup. der., auto-dismiss 5s, colores institucionales. |
| **Validaciones** | `nombre_lab` requerido + único, `resp_acad_id` debe existir en `academico`, `horario_lab` opcional (solo PDF, validar MIME + extensión). |
| **Permisos** | Todas las rutas protegidas con `AuthMiddleware::handle` (requiere login). |

---

## 6. UI/UX (Colores Institucionales)

- **Azul** (`unsxx-blue-900/800/700`): headers tabla, sidebar, textos principales
- **Amarillo** (`unsxx-yellow-400/300`): botones submit/guardar, focus rings, badges
- **Responsive**: Tabla con `overflow-x-auto`, `text-sm`, `px-4 py-3` + Simple-DataTables para paginación
- **Formularios**: Select responsable (carga dinámica), input file PDF con validación MIME + extensión, validación HTML5 + server-side

---

## 7. Archivos a Crear/Modificar (Checklist)

| Archivo | Acción |
|---------|--------|
| `src/Models/Lab.php` | **Crear** (nuevo modelo) |
| `src/Controllers/LabController.php` | **Crear** (nuevo controlador) |
| `src/Views/lab/index.php` | **Crear** (listado tabla + botón nuevo + Simple-DataTables) |
| `src/Views/lab/create.php` | **Crear** (formulario nuevo) |
| `src/Views/lab/edit.php` | **Crear** (formulario edición) |
| `src/routes.php` | **Modificar** (agregar 7 rutas CRUD) |
| `src/Views/layouts/app.php` | **Modificar** (agregar enlace "Laboratorios" en sidebar) |

---

## 9. Próximos Pasos (Ejecución)

1. **Crear modelo** `src/Models/Lab.php`
2. **Crear controlador** `src/Controllers/LabController.php`
3. **Crear vistas** `src/Views/lab/index.php`, `create.php`, `edit.php`
4. **Actualizar rutas** `src/routes.php` (agregar 7 rutas CRUD)
5. **Actualizar sidebar** `src/Views/layouts/app.php` (enlace "Laboratorios")
6. **Probar E2E**: crear, listar, editar, eliminar, validar PDF, responsive