# Plan 008 - Módulo Documentos Oficiales

## Objetivo
Implementar el módulo de gestión de documentos oficiales (planes de estudio, horarios, reglamentos, convocatorias, etc.) con subida física de PDFs usando la utilidad `FileUploader`.

---

## 1. Estructura de Datos

### Tabla: `documento_oficial`

| Campo | Tipo | Detalles |
|-------|------|----------|
| `id_doc` | INT UNSIGNED | PK, AUTO_INCREMENT |
| `titulo` | VARCHAR(255) | NOT NULL |
| `categoria` | ENUM | `plan_estudios`, `horario_clases`, `horario_labs`, `reglamento_titulacion`, `convocatoria`, `otro` |
| `archivo_pdf` | VARCHAR(255) | NOT NULL - nombre físico generado por FileUploader (ej: `doc_abc123.pdf`) |
| `gestion` | YEAR | NOT NULL - año de gestión académica |
| `estado` | TINYINT(1) | DEFAULT 1 (1=activo, 0=inactivo) |
| `subido_por` | INT UNSIGNED | FK → `usuario.usu_id` |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
| `updated_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |

### Índices y FKs
```sql
INDEX idx_categoria (categoria),
INDEX idx_gestion (gestion),
INDEX idx_estado (estado),
INDEX idx_subido_por (subido_por),
FOREIGN KEY (subido_por) REFERENCES usuario(usu_id) ON DELETE SET NULL
```

### SQL de Creación (para `database_v2.sql`)
```sql
CREATE TABLE `documento_oficial` (
    `id_doc` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(255) NOT NULL,
    `categoria` ENUM('plan_estudios','horario_clases','horario_labs','reglamento_titulacion','convocatoria','otro') NOT NULL,
    `archivo_pdf` VARCHAR(255) NOT NULL,
    `gestion` YEAR NOT NULL,
    `estado` TINYINT(1) NOT NULL DEFAULT 1,
    `subido_por` INT UNSIGNED NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_doc`),
    INDEX `idx_categoria` (`categoria`),
    INDEX `idx_gestion` (`gestion`),
    INDEX `idx_estado` (`estado`),
    INDEX `idx_subido_por` (`subido_por`),
    CONSTRAINT `fk_doc_usuario` FOREIGN KEY (`subido_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 2. Arquitectura MVC

### Modelo: `src/Models/OfficialDocument.php`
- `all(): array` - SELECT con LEFT JOIN usuario u ON d.subido_por = u.usu_id → retorna `u.usu_nom AS subido_por_nombre`
- `findById(int $id): ?array` - mismo JOIN
- `create(array $data): int` - valida campos requeridos + categoria ENUM + archivo_pdf obligatorio
- `update(int $id, array $data): bool` - valida, mantiene archivo anterior si no viene nuevo
- `delete(int $id): bool` - DELETE físico

### Controlador: `src/Controllers/OfficialDocumentController.php`
Extiende `Controller`. Métodos:
- `index()` - GET `/documents` - lista con paginación opcional
- `create()` - GET `/documents/create` - formulario
- `store()` - POST `/documents` - **requiere archivo PDF** via `FileUploader::uploadPdf()`
- `edit(int $id)` - GET `/documents/{id}/edit`
- `update(int $id)` - POST `/documents/{id}/edit` y POST `/documents/{id}` - FileUploader solo si `$_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK`
- `destroy(int $id)` - POST `/documents/{id}/delete` - borra archivo físico + registro BD

### Vistas: `src/Views/document/`
| Vista | Descripción |
|-------|-------------|
| `index.php` | Tabla responsive: Título, Categoría, Gestión, Estado, Subido por, Acciones |
| `create.php` | Formulario: título, categoria (select), archivo_pdf (input file accept=".pdf"), gestión (number), estado (checkbox) |
| `edit.php` | Igual que create + muestra nombre archivo actual + checkbox "Reemplazar PDF" |

---

## 3. Rutas (src/routes.php)

```php
/* ==================== CRUD DOCUMENTOS OFICIALES (protegido) ==================== */
$router->get('/documents', [OfficialDocumentController::class, 'index'])->middleware([AuthMiddleware::class, 'handle']);
$router->get('/documents/create', [OfficialDocumentController::class, 'create'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/documents', [OfficialDocumentController::class, 'store'])->middleware([AuthMiddleware::class, 'handle']);

$router->get('/documents/{id}/edit', [OfficialDocumentController::class, 'edit'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/documents/{id}/edit', [OfficialDocumentController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/documents/{id}', [OfficialDocumentController::class, 'update'])->middleware([AuthMiddleware::class, 'handle']);
$router->post('/documents/{id}/delete', [OfficialDocumentController::class, 'destroy'])->middleware([AuthMiddleware::class, 'handle']);
```

---

## 4. Reglas de Negocio Clave

### 4.1 Auditoría - Inyección de `subido_por`
En **store()** y **update()** del controlador:
```php
$data['subido_por'] = (int)($_SESSION['user_id'] ?? $_SESSION['user']['id'] ?? 1);
```

### 4.2 JOIN Usuario en Modelo
```php
// En all() y findById()
SELECT d.*, u.usu_nom AS subido_por_nombre
FROM documento_oficial d
LEFT JOIN usuario u ON d.subido_por = u.usu_id
ORDER BY d.gestion DESC, d.categoria, d.titulo
```

### 4.3 Integración FileUploader (OBLIGATORIO)

#### store() - Archivo REQUERIDO
```php
if (!isset($_FILES['archivo_pdf']) || $_FILES['archivo_pdf']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'El archivo PDF es obligatorio';
    // volver a create con old data
}

$filename = FileUploader::uploadPdf($_FILES['archivo_pdf']);
if (!$filename) {
    $_SESSION['error'] = 'Archivo inválido. Solo se permiten PDFs.';
    // volver a create
}
$data['archivo_pdf'] = $filename;
```

#### update() - Archivo OPCIONAL
```php
$oldFilename = $existing['archivo_pdf'];

if (isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
    $newFilename = FileUploader::uploadPdf($_FILES['archivo_pdf']);
    if (!$newFilename) {
        $_SESSION['error'] = 'Archivo inválido. Solo se permiten PDFs.';
        // volver a edit
    }
    // Borrar archivo anterior
    if ($oldFilename) {
        FileUploader::delete($oldFilename, 'pdf');
    }
    $data['archivo_pdf'] = $newFilename;
} else {
    // Mantener archivo existente
    $data['archivo_pdf'] = $oldFilename;
}
```

#### destroy() - Borrado físico ANTES que BD
```php
$existing = $this->model->findById($id);
if ($existing && $existing['archivo_pdf']) {
    FileUploader::delete($existing['archivo_pdf'], 'pdf');
}
$this->model->delete($id);
```

### 4.4 Validaciones de Negocio
- `titulo`: requerido, max 255 chars
- `categoria`: requerido, debe estar en ENUM válido
- `archivo_pdf`: **obligatorio en create**, opcional en edit
- `gestion`: requerido, YEAR válido (ej: 2024)
- `estado`: checkbox → `isset($_POST['estado']) ? 1 : 0`
- Solo PDFs (validado por FileUploader via MIME type)

### 4.5 Flash Messages
- Éxito: `$_SESSION['success'] = 'Documento creado/actualizado/eliminado correctamente'`
- Error: `$_SESSION['error'] = $e->getMessage()` o mensaje específico

---

## 5. Checklist de Implementación

- [ ] Agregar tabla `documento_oficial` a `database/database_v2.sql`
- [ ] Crear `src/Models/OfficialDocument.php`
- [ ] Crear `src/Controllers/OfficialDocumentController.php`
- [ ] Registrar 7 rutas en `src/routes.php` con AuthMiddleware
- [ ] Crear vistas: `src/Views/document/index.php`, `create.php`, `edit.php`
- [ ] Actualizar layout/sidebar para incluir enlace a `/documents`
- [ ] Probar flujo completo: crear → editar (con/sin nuevo PDF) → eliminar → verificar borrado físico

---

## 6. Notas Técnicas

- **Sin Composer**: FileUploader se carga via autoloader PSR-4 existente
- **Directorio uploads**: `public/uploads/pdf/` (creado automáticamente por FileUploader)
- **Nombres de archivo**: `doc_{uniqid}.pdf` - sin colisiones, sin información sensible
- **Borrado físico**: Siempre ANTES del DELETE en BD para evitar archivos huérfanos
- **Estados**: Soft-delete NO aplica (DELETE físico en BD + archivo) - consistente con resto del proyecto