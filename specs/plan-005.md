# Plan SDD - Módulo de Extensión (plan-005.md)

## 1. Estructura de Datos

### Tabla: `extension`

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id_ext` | `INT UNSIGNED` | PK, AI | Identificador único |
| `nom_ext` | `VARCHAR(500)` | NOT NULL | Nombre/título de la actividad de extensión |
| `fech_ext` | `DATE` | NOT NULL | Fecha de la actividad |
| `desc_ext` | `LONGTEXT` | NOT NULL | Descripción detallada de la actividad |
| `creado_por` | `INT UNSIGNED` | FK → `usuario.usu_id`, NOT NULL | ID del usuario administrador que creó el registro |

> **Nota**: La tabla ya existe en la BD v2 (ver `database_v2.sql`). Campos coinciden con `plan-001.md`.

---

## 2. Arquitectura MVC

### 2.1 Modelo: `src/Models/Extension.php`

```php
namespace App\Models;

use App\Config\Database;
use PDO;

class Extension {
    private PDO $pdo;

    public function __construct() { $this->pdo = Database::getInstance(); }

    public function all(): array { /* LEFT JOIN usuario ON creado_por = usu_id */ }
    public function findById(int $id): ?array { /* JOIN usuario */ }
    public function create(array $data): int { /* validaciones + INSERT */ }
    public function update(int $id, array $data): bool { /* validaciones + UPDATE */ }
    public function delete(int $id): bool { /* DELETE físico */ }
    private function generatePdfUuid(string $ext): string { /* UUID para PDF */ }
    private function validatePdf(array $file): ?string { /* validación MIME + extensión */ }
}
```

### 2.2 Controlador: `src/Controllers/ExtensionController.php`

```php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Extension;

class ExtensionController extends Controller {
    private Extension $model;

    public function __construct() { $this->model = new Extension(); }

    public function index(): void { /* listado con JOIN usuario */ }
    public function create(): void { /* formulario sin campo creado_por */ }
    public function store(): void { /* asigna creado_por = $_SESSION['user']['id'] */ }
    public function edit(int $id): void { /* buscar + formulario */ }
    public function update(int $id): void { /* validación + update + redirect + flash */ }
    public function destroy(int $id): void { /* delete físico + redirect + flash */ }
}
```

### 2.3 Vistas: `src/Views/extension/`

```
src/Views/extension/
├── index.php      # Listado tabla con JOIN usuario (creador)
├── create.php     # Formulario (sin campo creado_por)
└── edit.php       # Formulario pre-poblado
```

---

## 3. Rutas (`src/routes.php`)

```php
/* ==================== CRUD EXTENSIÓN (protegido) ==================== */
$router->get('/extensions', [ExtensionController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/extensions/create', [ExtensionController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/extensions', [ExtensionController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/extensions/{id}/edit', [ExtensionController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/extensions/{id}/edit', [ExtensionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/extensions/{id}', [ExtensionController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/extensions/{id}/delete', [ExtensionController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);
```

---

## 4. Reglas de Negocio

| Regla | Detalle |
|-------|---------|
| **creado_por** | **NO** en formulario. Asignado automáticamente en `store()`: `$data['creado_por'] = $_SESSION['user']['id']` |
| **all()** | LEFT JOIN `usuario` ON `creado_por = usu_id` → muestra `usu_nom` del creador en index |
| **fech_ext** | Tipo `DATE`, validar formato `YYYY-MM-DD` |
| **nom_ext** | Único, requerido, máx 500 chars |
| **desc_ext** | `LONGTEXT`, requerido |
| **Borrado** | DELETE físico (no soft-delete) |
| **Notificaciones** | SweetAlert2 vía `$_SESSION['success']` / `$_SESSION['error']` con `unset()` |
| **Permisos** | Todas las rutas con `AuthMiddleware::handle` (requiere login) |
| **PDF opcional** | Campo `horario_file` opcional (placeholder UUID, subida física en Fase futura) |

---

## 4. UI/UX (Colores Institucionales)

| Color | Uso |
|-------|-----|
| **Azul** (`unsxx-blue-900/800/700`) | Headers tabla, sidebar, textos principales, botón actualizar |
| **Amarillo** (`unsxx-yellow-400/300`) | Botones submit/guardar, focus rings, badges |
| **Responsive** | Tabla con `overflow-x-auto`, `text-sm`, `px-4 py-3` |
| **Formularios** | Focus visible: `focus:ring-2 focus:ring-unsxx-yellow-400` |

---

## 5. Checklist de Implementación (Backend)

| Archivo | Acción |
|---------|--------|
| `src/Models/Extension.php` | Crear (nuevo modelo con `all()` con LEFT JOIN usuario) |
| `src/Controllers/ExtensionController.php` | Crear (controlador completo) |
| `src/routes.php` | Agregar 7 rutas CRUD bajo `/extensions` con AuthMiddleware |

---

## 6. Próximos Pasos (Fases)

1. **Backend**: Modelo, Controlador, Rutas (esta fase)
2. **Frontend**: Vistas `index.php`, `create.php`, `edit.php` con UI UNSXX
3. **Integración**: Probar flujo E2E completo

---

**Confirmación**: Plan listo para implementación. El backend se implementará en la siguiente fase.