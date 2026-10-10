# Plan SDD - Módulo de Comunicados (plan-006.md)

## 1. Estructura de Datos

### Tabla: `comunicado`

| Campo | Tipo | Restricciones | Descripción |
|-------|------|---------------|-------------|
| `id_com` | `INT UNSIGNED` | PK, AI | Identificador único |
| `nom_com` | `VARCHAR(300)` | NOT NULL | Título del comunicado |
| `desc_com` | `LONGTEXT` | NOT NULL | Contenido/descripción completa |
| `doc_com` | `VARCHAR(255)` | NULLABLE | Nombre archivo PDF (placeholder texto) |
| `foto_com` | `VARCHAR(255)` | NULLABLE | Nombre archivo imagen (placeholder texto) |
| `fech_com` | `DATE` | NOT NULL | Fecha de publicación |
| `publicado_por` | `INT UNSIGNED` | FK → `usuario.usu_id`, NOT NULL | ID del admin que publicó |
| `estado` | `TINYINT(1)` | NOT NULL, DEFAULT 0 | 1=publicado, 0=borrador |

---

## 2. Arquitectura MVC

### 2.1 Modelo: `src/Models/Announcement.php`

```php
namespace App\Models;
use App\Config\Database;
use PDO;

class Announcement {
    private PDO $pdo;
    public function __construct() { $this->pdo = Database::getInstance(); }

    public function all(): array { 
        // LEFT JOIN usuario u ON c.publicado_por = u.usu_id
        // SELECT c.*, u.usu_nom AS autor_nombre
        // Sin WHERE estado=1 (ver todos en admin)
    }
    public function findById(int $id): ?array { /* JOIN usuario */ }
    public function create(array $data): int { /* validaciones + INSERT */ }
    public function update(int $id, array $data): bool { /* validaciones + UPDATE */ }
    public function delete(int $id): bool { /* DELETE físico */ }
    public function nombreExists(string $nombre, ?int $excludeId = null): bool { /* unicidad */ }
}
```

### 2.2 Controlador: `src/Controllers/AnnouncementController.php`

```php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller {
    private Announcement $model;
    public function __construct() { $this->model = new Announcement(); }

    public function index(): void { /* all() → render announcement/index.php */ }
    public function create(): void { /* formulario sin publicado_por */ }
    public function store(): void { 
        $data['publicado_por'] = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1;
        $data['estado'] = isset($_POST['estado']) ? 1 : 0;  // checkbox explícito
        // create + redirect + flash
    }
    public function edit(int $id): void { /* findById + formulario */ }
    public function update(int $id): void { 
        $data['estado'] = isset($_POST['estado']) ? 1 : 0;
        // update + redirect + flash
    }
    public function destroy(int $id): void { /* delete físico + redirect + flash */ }
}
```

### 2.3 Vistas: `src/Views/announcement/`

```
src/Views/announcement/
├── index.php      # Tabla con estado visible (badge publicado/borrador)
├── create.php     # Formulario: nom_com, fech_com, desc_com, doc_com, foto_com, estado (checkbox)
└── edit.php       # Pre-poblado + estado checkbox pre-seleccionado
```

---

## 3. Rutas (`src/routes.php`)

```php
/* ==================== CRUD COMUNICADOS (protegido) ==================== */
$router->get('/announcements', [AnnouncementController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/announcements/create', [AnnouncementController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/announcements', [AnnouncementController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/announcements/{id}/edit', [AnnouncementController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/announcements/{id}', [AnnouncementController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/announcements/{id}/delete', [AnnouncementController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);
```

---

## 4. Reglas de Negocio Clave

| Regla | Implementación |
|-------|----------------|
| **Auditoría `publicado_por`** | NO en formulario. `store()` inyecta: `$data['publicado_por'] = $_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1` |
| **JOIN Usuario** | `all()` hace `LEFT JOIN usuario u ON c.publicado_por = u.usu_id` → `u.usu_nom AS autor_nombre` |
| **Visibilidad Admin** | `all()` **SIN** `WHERE estado = 1` → muestra borradores (estado=0) y publicados (estado=1) |
| **Archivos** | `doc_com` y `foto_com` solo guardan nombre string (placeholder). Subida física en fase futura |
| **Estado (checkbox)** | Controlador valida: `$data['estado'] = isset($_POST['estado']) ? 1 : 0;` |
| **Borrado** | DELETE físico (no soft-delete) |
| **Notificaciones** | SweetAlert2 vía `$_SESSION['success']` / `$_SESSION['error']` con `unset()` |
| **Permisos** | Todas las rutas con `AuthMiddleware::handle` |

---

## 4. UI/UX (Colores Institucionales)

| Elemento | Estilo |
|----------|--------|
| **Estado publicado** | Badge verde `bg-green-100 text-green-800` |
| **Estado borrador** | Badge amarillo `bg-yellow-100 text-yellow-800` |
| **Botón publicar** | Verde `bg-green-600` |
| **Botón guardar borrador** | Amarillo `bg-yellow-500` |
| **Focus inputs** | `focus:ring-2 focus:ring-unsxx-yellow-400` |
| **Tabla responsive** | `overflow-x-auto`, `text-sm`, `px-4 py-3` |

---

## 5. Checklist de Implementación (Backend)

| Archivo | Acción |
|---------|--------|
| `src/Models/Announcement.php` | Crear (modelo con `all()` JOIN usuario, sin filtro estado) |
| `src/Controllers/AnnouncementController.php` | Crear (controlador completo con checkbox estado) |
| `src/routes.php` | Agregar 7 rutas CRUD bajo `/announcements` con AuthMiddleware |

---

**Confirmación**: Plan listo para implementación. El backend se implementará en la siguiente fase.