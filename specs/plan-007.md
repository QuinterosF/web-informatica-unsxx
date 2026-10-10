# Plan SDD - Módulo de Admisión (plan-007.md)

## 1. Estructura de Datos

### Tabla: `admision`

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id_adm` | `INT UNSIGNED` | PK, AI | Identificador único |
| `proc_adm` | `LONGTEXT` | NOT NULL | Proceso de admisión (contenido HTML/texto) |
| `req_adm` | `LONGTEXT` | NOT NULL | Requisitos de admisión |
| `img_adm` | `VARCHAR(255)` | NULLABLE | Nombre archivo imagen (placeholder texto) |
| `tipo` | `ENUM('prueba','preuni')` | NOT NULL | Tipo: 'prueba' (examen) o 'preuni' (preuniversitario) |
| `actualizado_por` | `INT UNSIGNED` | FK → `usuario.usu_id`, NOT NULL | ID del admin que modificó |

---

## 2. Arquitectura MVC

### 2.1 Modelo: `src/Models/Admission.php`

```php
namespace App\Models;
use App\Config\Database;
use PDO;

class Admission {
    private PDO $pdo;
    public function __construct() { $this->pdo = Database::getInstance(); }

    public function all(): array { 
        // LEFT JOIN usuario u ON a.actualizado_por = u.usu_id
        // SELECT a.*, u.usu_nom AS actualizado_por_nombre
        // ORDER BY a.tipo, a.id_adm
    }
    public function findById(int $id): ?array { /* JOIN usuario */ }
    public function create(array $data): int { /* validaciones + INSERT */ }
    public function update(int $id, array $data): bool { /* validaciones + UPDATE */ }
    public function delete(int $id): bool { /* DELETE físico */ }
}
```

### 2.2 Controlador: `src/Controllers/AdmissionController.php`

```php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Admission;

class AdmissionController extends Controller {
    private Admission $model;
    public function __construct() { $this->model = new Admission(); }

    public function index(): void { /* all() → render admission/index.php */ }
    public function create(): void { /* formulario sin actualizado_por */ }
    public function store(): void { 
        $data['actualizado_por'] = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1;
        // create + redirect + flash
    }
    public function edit(int $id): void { /* findById + formulario */ }
    public function update(int $id): void { 
        // update + redirect + flash
    }
    public function destroy(int $id): void { /* delete físico + redirect + flash */ }
}
```

### 2.3 Vistas: `src/Views/admission/`

```
src/Views/admission/
├── index.php      # Tabla con tabs/filtro por tipo (prueba/preuni), muestra actualizado_por_nombre
├── create.php     # Formulario: proc_adm, req_adm, img_adm (file), tipo (radio/select)
└── edit.php       # Pre-poblado + img_adm actual visible
```

---

## 3. Rutas (`src/routes.php`)

```php
/* ==================== CRUD ADMISIÓN (protegido) ==================== */
$router->get('/admissions', [AdmissionController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/admissions/create', [AdmissionController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/admissions', [AdmissionController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/admissions/{id}/edit', [AdmissionController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/admissions/{id}/edit', [AdmissionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/admissions/{id}', [AdmissionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/admissions/{id}/delete', [AdmissionController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);
```

---

## 4. Reglas de Negocio Clave

| Regla | Implementación |
|-------|----------------|
| **Auditoría `actualizado_por`** | NO en formulario. `store()` y `update()` inyectan: `$data['actualizado_por'] = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1` |
| **JOIN Usuario** | `all()` hace `LEFT JOIN usuario u ON a.actualizado_por = u.usu_id` → `u.usu_nom AS actualizado_por_nombre` |
| **Tipos** | ENUM `('prueba','preuni')`. Formulario usa radio buttons o select |
| **Archivos** | `img_adm` solo guarda nombre string (placeholder). Subida física en fase futura |
| **Borrado** | DELETE físico (no soft-delete) |
| **Notificaciones** | SweetAlert2 vía `$_SESSION['success']` / `$_SESSION['error']` con `unset()` |
| **Permisos** | Todas las rutas con `AuthMiddleware::handle` |

---

## 4. UI/UX (Colores Institucionales)

| Elemento | Estilo |
|----------|--------|
| **Tabs tipo** | Azul `bg-unsxx-blue-100` activo, gris inactivo |
| **Formulario** | `proc_adm` y `req_adm` con textarea (wysiwyg opcional futuro) |
| **Imagen** | Input file + preview si existe `img_adm` actual |
| **Tabs tipo** | Radio buttons estilizados: "Prueba de Aptitud" / "Preuniversitario" |
| **Focus inputs** | `focus:ring-2 focus:ring-unsxx-yellow-400` |
| **Tabla responsive** | `overflow-x-auto`, `text-sm`, `px-4 py-3` |

---

## 5. Checklist de Implementación (Backend)

| Archivo | Acción |
|---------|--------|
| `src/Models/Admission.php` | Crear (modelo con `all()` JOIN usuario, `findById`, `create`, `update`, `delete`) |
| `src/Controllers/AdmissionController.php` | Crear (controlador completo) |
| `src/routes.php` | Agregar 7 rutas CRUD bajo `/admissions` con AuthMiddleware |

---

**Confirmación**: Plan listo para implementación. El backend se implementará en la siguiente fase.