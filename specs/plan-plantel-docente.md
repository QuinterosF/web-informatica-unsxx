# Plan: Implementación Módulo Plantel Docente (Gestión Académica)

Basado en `specs/spec-plantel-docente.md`. Pasos secuenciales, atómicos y probables independientemente.

---

## Paso 1: Capa de Base de Datos y Modelo

### Objetivo
Asegurar esquema relacional correcto, datos semilla y Modelo `Academic.php` completo con validaciones de servidor.

### Archivos Involucrados
| Archivo | Acción |
|---------|--------|
| `database/database_v2.sql` | Verificar/agregar `CREATE TABLE academico` + FKs + `categoria_academico` + seed data |
| `database/migracion_datos.sql` | (Opcional) Migración desde tabla legacy `academicos` |
| `src/Models/Academic.php` | Implementar/validar todos los métodos especulados |

### SQL Requerido (para `database_v2.sql`)

```sql
-- Tabla categorias (si no existe)
CREATE TABLE `categoria_academico` (
    `cat_id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `cat_nombre` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`cat_id`),
    UNIQUE KEY `uk_cat_nombre` (`cat_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed data categorías
INSERT IGNORE INTO `categoria_academico` (`cat_nombre`) VALUES 
('Director de Carrera'), ('Titular'), ('Asociado'), ('Adjunto'), 
('Invitado'), ('Coordinador'), ('IIDAI');

-- Tabla academico (verificar FKs)
CREATE TABLE `academico` (
    `id_acad` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nom_acad` VARCHAR(300) NOT NULL,
    `foto_acad` VARCHAR(255) NOT NULL COMMENT 'UUID filename',
    `cel_acad` VARCHAR(20) NOT NULL,
    `email_acad` VARCHAR(150) NOT NULL,
    `cat_acad` TINYINT UNSIGNED NOT NULL COMMENT 'FK → categoria_academico.cat_id',
    `desc_acad` LONGTEXT NOT NULL,
    `usu_id` INT UNSIGNED NULL COMMENT 'FK opcional → usuario.usu_id',
    PRIMARY KEY (`id_acad`),
    UNIQUE KEY `uk_academico_email` (`email_acad`),
    KEY `idx_academico_cat` (`cat_acad`),
    KEY `idx_academico_usu` (`usu_id`),
    CONSTRAINT `fk_academico_categoria` FOREIGN KEY (`cat_acad`) REFERENCES `categoria_academico` (`cat_id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_academico_usuario` FOREIGN KEY (`usu_id`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Métodos a Validar en `Academic.php`
| Método | Responsabilidad | Validaciones Clave |
|--------|-----------------|-------------------|
| `getCategories()` | SELECT cat_id, cat_nombre ORDER BY cat_nombre | Retorna array asociativo |
| `findAll()` | JOIN categoria → cat_nombre | Ordenado por nom_acad ASC |
| `findById($id)` | JOIN categoria + filtro id | Retorna `null` si no existe |
| `emailExists($email, $excludeId)` | UNIQUE check excluyendo ID actual | Bool |
| `categoryExists($catId)` | FK check previa | Bool |
| `create($data)` | Validaciones completas → INSERT | Requeridos, email format, email único, FK cat, foto UUID |
| `update($id, $data)` | Validaciones + email único excluyendo $id | Mantiene foto si no viene nueva |
| `delete($id)` | Verifica existencia → DELETE | Bool |
| `all()` | Alias a `findAll()` | Compatibilidad SDD |

### Pruebas Manuales Paso 1
1. Ejecutar `database_v2.sql` en MariaDB limpio → 0 errores, FKs creadas.
2. `INSERT INTO academico ...` con email duplicado → Error 1062 (UNIQUE).
3. `INSERT` con `cat_acad` inexistente → Error 1452 (FK RESTRICT).
4. Instanciar `Academic` → `getCategories()` retorna 7+ filas.
5. `create(['nom_acad'=>'Test','email_acad'=>'t@test.com','cel_acad'=>'77777777','cat_acad'=>1,'desc_acad'=>'desc'])` → retorna ID > 0.
6. `create()` con mismo email → lanza Exception "email ya registrado".
7. `update()` cambiando email a uno existente → Exception.
8. `delete(999)` (inexistente) → `false`.

---

## Paso 2: Capa de Enrutamiento y Controlador Base

### Objetivo
Registrar 7 rutas CRUD protegidas y crear esqueleto `AcademicController.php` extendiendo `Controller`.

### Archivos Involucrados
| Archivo | Acción |
|---------|--------|
| `src/routes.php` | Agregar import + 7 rutas con `AuthMiddleware` |
| `src/Controllers/AcademicController.php` | Crear clase con `__construct`, propiedades, métodos vacíos |

### Rutas a Registrar (orden exacto)
```php
use App\Controllers\AcademicController;
// ... existente ...

/* ==================== CRUD PLANTEL DOCENTE (protegido) ==================== */
$router->get('/academics', [AcademicController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/academics/create', [AcademicController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/academics', [AcademicController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/academics/{id}/edit', [AcademicController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/academics/{id}/edit', [AcademicController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/academics/{id}', [AcademicController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/academics/{id}/delete', [AcademicController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);
```

### Estructura `AcademicController.php`
```php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Academic;

class AcademicController extends Controller {
    private Academic $model;
    public function __construct() { $this->model = new Academic(); }
    
    public function index(): void { /* TODO */ }
    public function create(): void { /* TODO */ }
    public function store(): void { /* TODO */ }
    public function edit(int $id): void { /* TODO */ }
    public function update(int $id): void { /* TODO */ }
    public function destroy(int $id): void { /* TODO */ }
    
    // Helper UUID (temporal hasta FileUploader completo)
    private function generatePhotoUuid(string $ext = 'jpg'): string { ... }
}
```

### Pruebas Manuales Paso 2
1. Acceder `/academics` logueado → no 404, carga vista (aunque vacía).
2. Acceder `/academics/create` → no 404.
3. Acceder `/academics/999/edit` → no 404.
4. POST a `/academics` sin login → redirect `/login` (AuthMiddleware).
5. Verificar en `routes.php` que `AcademicController` está importado.

---

## Paso 3: Controlador - Lógica de Creación e Inserción (FileUploader)

### Objetivo
Implementar `store()` con subida real de imagen via `FileUploader::uploadImage()`, validaciones, flash messages y redirección.

### Archivos Involucrados
| Archivo | Acción |
|---------|--------|
| `src/Controllers/AcademicController.php` | Implementar `store()` completo |
| `src/Core/FileUploader.php` | Verificar `uploadImage()` funciona (ya existe) |

### Lógica `store()` (pseudocódigo obligatorio)
```php
public function store(): void {
    // 1. Recoger $_POST
    $data = [ 'nom_acad'=>trim(...), 'email_acad'=>trim(...), 'cel_acad'=>trim(...), 
              'cat_acad'=>(int)(...), 'desc_acad'=>trim(...) ];
    
    // 2. Manejar archivo (REQUERIDO en create)
    if (!isset($_FILES['foto_acad']) || $_FILES['foto_acad']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error'] = 'La foto de perfil es obligatoria';
        return $this->view('academic/create', [ 'categories'=>..., 'old'=>$data ]);
    }
    $filename = FileUploader::uploadImage($_FILES['foto_acad']);
    if (!$filename) {
        $_SESSION['error'] = 'Archivo inválido. Solo JPG, PNG, WebP';
        return $this->view('academic/create', [ 'categories'=>..., 'old'=>$data ]);
    }
    $data['foto_acad'] = $filename;
    
    // 3. Intentar crear
    try {
        $id = $this->model->create($data);
        $_SESSION['success'] = "Docente creado (ID: $id)";
        $this->redirect('/academics');
    } catch (\Exception $e) {
        FileUploader::delete($filename, 'img'); // Rollback archivo
        $_SESSION['error'] = $e->getMessage();
        $this->view('academic/create', [ 'categories'=>..., 'old'=>$data ]);
    }
}
```

### Pruebas Manuales Paso 3
1. POST `/academics` sin archivo → error flash "foto obligatoria", vuelve a create con datos.
2. POST con archivo `.pdf` → error "Solo JPG, PNG, WebP", vuelve a create.
3. POST con JPG válido + datos correctos → éxito, redirect `/academics`, archivo en `public/uploads/images/academics/img_<uuid>.jpg`.
4. POST con email duplicado → error flash, archivo subido **eliminado** (rollback), vuelve a create.
5. Verificar BD: registro insertado con `foto_acad = 'img_<uuid>.jpg'`.

---

## Paso 4: Vistas - Listado y Formulario de Creación

### Objetivo
Implementar `index.php` (DataTables + SweetAlert2 delete) y `create.php` (formulario multipart, Tailwind, repoblación).

### Archivos Involucrados
| Archivo | Acción |
|---------|--------|
| `src/Views/academic/index.php` | Tabla responsive, DataTables init, SweetAlert2 confirm delete |
| `src/Views/academic/create.php` | Formulario grid 2-col, file input, categorías select, `$old` repoblación |

### Requisitos UI `index.php`
- **Tabla**: `id="docentesTable"`, columnas: Foto (avatar 40px), Nombre, Email (mailto), Categoría (badge), Celular, Acciones.
- **DataTables CDN**: simple-datatables (CSS + JS).
- **Config JS**: `perPage:5`, `perPageSelect:[5,10,25,50]`, labels ES, búsqueda "Buscar docente...".
- **Estilos**: Cabecera `bg-unsxx-blue-900`, paginación activa `bg-unsxx-yellow-400`, responsive stack `<768px`, scroll horizontal `min-width:800px`.
- **Delete**: Form POST + clase `.form-eliminar` + SweetAlert2 warning modal (rojo/azul, reverseButtons).

### Requisitos UI `create.php`
- **Layout**: Card `max-w-4xl mx-auto`, `enctype="multipart/form-data"`.
- **Grid**: `md:grid-cols-2 gap-6` (Nombre/Email, Celular/Categoría).
- **Campos**: `required` en Nombre, Email, Categoría. `accept="image/jpeg,image/png,image/webp"` en file.
- **Repoblación**: `value="<?= htmlspecialchars($old['campo'] ?? '') ?>"`.
- **Select Categoría**: `<option value="<?= $cat['cat_id'] ?>" <?= ($old['cat_acad']??'')==$cat['cat_id']?'selected':'' ?>>`.
- **Errores**: Banner rojo si `$_SESSION['error']` (se unsetea tras mostrar).
- **Botones**: Cancelar (enlace `/academics`) + Guardar (amarillo UNSXX bold).

### Pruebas Manuales Paso 4
1. `/academics` → Tabla renderiza, DataTables funciona (busca, pagina, ordena).
2. Responsive `<768px` → Controles apilados, tabla scrolleable horizontal.
3. Click "Eliminar" → SweetAlert2 modal aparece → Cancelar no borra, Confirmar envía POST.
4. `/academics/create` → Formulario visible, categorías cargadas en select.
5. Enviar formulario vacío → Validación HTML5 bloquea (required).
6. Enviar con error servidor (email duplicado) → Datos repoblados en inputs, select mantiene valor, error banner visible.

---

## Paso 5: Controlador y Vistas - Edición y Eliminación

### Objetivo
Implementar `edit()`, `update()` (reemplazo opcional de foto), `destroy()` y vista `edit.php`.

### Archivos Involucrados
| Archivo | Acción |
|---------|--------|
| `src/Controllers/AcademicController.php` | Implementar `edit()`, `update()`, `destroy()` |
| `src/Views/academic/edit.php` | Formulario edición + foto actual + file opcional |

### Lógica `update()` (puntos críticos)
```php
public function update(int $id): void {
    $existing = $this->model->findById($id);
    if (!$existing) { $_SESSION['error']='No encontrado'; $this->redirect('/academics'); }
    
    $oldFilename = $existing['foto_acad'];
    $newFilename = $oldFilename;
    
    // Archivo NUEVO opcional
    if (isset($_FILES['foto_acad']) && $_FILES['foto_acad']['error'] === UPLOAD_ERR_OK) {
        $uploaded = FileUploader::uploadImage($_FILES['foto_acad']);
        if (!$uploaded) { /* error flash + view edit */ return; }
        $newFilename = $uploaded;
        // Borrar anterior SI existe
        if ($oldFilename) FileUploader::delete($oldFilename, 'img');
    }
    
    $data = [ ..., 'foto_acad' => $newFilename ];
    
    try {
        $this->model->update($id, $data);
        $_SESSION['success'] = 'Actualizado';
        $this->redirect('/academics');
    } catch (\Exception $e) {
        // Rollback: si subió nuevo y falla BD, borrar nuevo
        if ($newFilename !== $oldFilename) FileUploader::delete($newFilename, 'img');
        $_SESSION['error'] = $e->getMessage();
        $this->view('academic/edit', [ 'document'=>$existing, 'categories'=>..., 'old'=>$_POST ]);
    }
}
```

### Lógica `destroy()`
```php
public function destroy(int $id): void {
    $existing = $this->model->findById($id);
    // NOTA: Por spec actual NO borra archivo físico (política retención)
    // Si se desea borrar: if($existing && $existing['foto_acad']) FileUploader::delete($existing['foto_acad'], 'img');
    $deleted = $this->model->delete($id);
    $_SESSION[$deleted ? 'success' : 'error'] = $deleted ? 'Eliminado' : 'No existe';
    $this->redirect('/academics');
}
```

### Vista `edit.php` (diferencias vs create)
- Foto actual visible (avatar 40px) junto al input file.
- Input file **sin `required`**.
- Label helper: "Dejar vacío para mantener foto actual. JPG, PNG, WebP máx. 2MB".
- Campos `value` desde `$academic` (no `$old`), salvo que venga de error validación.
- Select categoría `selected` desde `$academic['cat_acad']`.

### Pruebas Manuales Paso 5
1. `/academics/{id}/edit` → Carga datos correctos, foto actual visible, categorías seleccionada.
2. Editar solo nombre → Guarda, éxito, nombre actualizado en lista.
3. Subir **nueva foto** en edit → Guarda → Nueva foto visible en lista y en edit; **archivo anterior borrado** de disco.
4. Editar sin cambiar foto → Foto original se mantiene (no se regenera UUID).
5. Email duplicado en edit → Error flash, formulario repoblado, foto actual sigue visible.
6. Eliminar docente → SweetAlert2 confirm → Éxito → Desaparece de lista; **archivo físico queda en disco** (política actual).
7. Acceso directo `/academics/999/edit` (inexistente) → Flash error + redirect `/academics`.

---

## Resumen de Entregables por Paso

| Paso | Archivos Creados/Modificados | Comando Verificación Rápida |
|------|------------------------------|----------------------------|
| 1 | `database_v2.sql` (verificar), `Academic.php` | `php -r "require 'src/autoload.php'; $m=new App\Models\Academic; var_dump($m->getCategories());"` |
| 2 | `routes.php`, `AcademicController.php` (esqueleto) | `curl -I http://localhost:8000/academics` (200 OK logueado) |
| 3 | `AcademicController.php` (`store()`) | POST formulario create → archivo en `public/uploads/images/academics/` |
| 4 | `Views/academic/index.php`, `create.php` | Navegador: tabla DataTables funcional, create renderiza bien |
| 5 | `AcademicController.php` (`edit`,`update`,`destroy`), `Views/academic/edit.php` | Flujo completo: crear → editar (con/sin foto) → eliminar |

---

## Notas de Dependencias Técnicas

1. **FileUploader** (`src/Core/FileUploader.php`) **debe existir y funcionar** antes del Paso 3. Verificar `uploadImage()` y `delete()`.
2. **AuthMiddleware** debe estar registrado y funcional (ya implementado en proyecto).
3. **Directorio uploads**: `public/uploads/images/academics/` se crea automáticamente por `FileUploader` (mkdir recursive).
4. **SweetAlert2 y simple-datatables** se cargan via CDN en las vistas (sin build step).
5. **Colores Tailwind**: Usar `unsxx-blue-*` y `unsxx-yellow-*` definidos en `layouts/app.php`.