# Proyecto: Web Informática UNSXX Refactor

Refactor de la web institucional de Ingeniería Informática (UNSXX). MVC propio en **PHP 8 nativo**, sin Composer ni frameworks. Base de datos **MySQL/MariaDB vía PDO**. Frontend con Tailwind (CDN) + SweetAlert2 + JS vanilla. Despliegue objetivo: hosting compartido / cPanel.

## Reglas operativas (innegociables)
- Las reglas de arquitectura y seguridad están en `docs/constitution.md`. Léelas antes de escribir código.
- Metodología: Spec-Driven Development (SDD). No escribas código sin `specs/spec-XX.md` y un plan aprobado (`specs/plan-XX.md`).
- Código limpio y **comentarios en español**; **nombres de variables/funciones en inglés**.
- Seguridad (tolerancia cero): solo *prepared statements* (nunca concatenar SQL), `password_hash`/`password_verify`, validación de MIME al subir archivos, `htmlspecialchars` en salidas, validación de sesión/rol en cada endpoint.

## Comandos
- Servidor de desarrollo (ejecutar **desde la raíz del proyecto**, no `-t public`):
  ```
  php -S localhost:8000 server.php
  ```
  `server.php` sirve archivos físicos de `public/` y delega el resto a `public/index.php`.
- No hay `composer`, `npm`, tests, linter, CI ni framework de testing. **No inventes** comandos de test/lint.
- El esquema SQL es manual (`database/database_v2.sql`); no hay migraciones automáticas.

## Arquitectura
- Flujo: `server.php` → `public/index.php` (front controller, hace `session_start()`, `chdir()` a la raíz, carga `.env`, fija `America/La_Paz`) → `src/routes.php` → `App\Core\Router`.
- Autoloader PSR-4 propio: prefijo `App\` mapea a `src/`. Crea la clase en la ruta exacta, p. ej. `App\Models\Foo` → `src/Models/Foo.php`.
- Rutas en `src/routes.php`. Cada ruta protegida usa `->middleware([AuthMiddleware::class, 'handle'])`; el rol admin usa `requireRole('admin')`.
- **Gotcha del router**: las rutas dinámicas usan `{param}` y el `Router` inyecta argumentos por **nombre** vía reflexión. El nombre del parámetro en el método del controlador debe coincidir con el de la ruta (p. ej. `/labs/{id_lab}/gallery/{id_foto}/delete` → `deletePhoto($id_lab, $id_foto)`).
- Controladores: extienden `App\Core\Controller`; usan `$this->view('carpeta/vista', $data)` (hace `extract($data)`) y `$this->redirect()`. Vistas en `src/Views/`; layout maestro en `src/Views/layouts/app.php`.
- Sesión: usa `App\Core\Session`. Datos del usuario en `$_SESSION['user']` (`id`, `role`, `logged_at`), no `$_SESSION['user_id']`. Expiración por `SESSION_LIFETIME`.

## Base de datos
- Credenciales vía `.env` (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`) cargadas por `App\Config\EnvLoader`. Copia desde `.env.example`.
- Conexión singleton: `App\Config\Database::getInstance()` (PDO, `ERRMODE_EXCEPTION`, `FETCH_ASSOC`, `EMULATE_PREPARES=false`, utf8mb4).

## Subida de archivos
- Usa siempre `App\Core\FileUploader` (estático). No reimplementes validaciones.
- `uploadImage($file, $subcarpeta)` → `public/uploads/img/{subcarpeta}/`; `uploadPdf($file, $subcarpeta)` → `public/uploads/pdf/{subcarpeta}/`. Devuelve solo el nombre de archivo (no la ruta).
- `delete($filename, $folder)`: el 2º argumento es la carpeta compuesta, p. ej. `'img/academic'`, `'pdf/documents'`.
- Patrón de actualización: archivo nuevo > checkbox `remove_*` > conservar existente; borra el archivo anterior antes de guardar. Nunca borres `default.png`.

## Convenciones de módulos
- Cada módulo = Modelo (`src/Models/`) + Controlador (`src/Controllers/`) + Vistas (`src/Views/{modulo}/`) + rutas en `src/routes.php`.
- Patrón CRUD de rutas: `GET /x`, `GET /x/create`, `POST /x`, `GET /x/{id}/edit`, `POST /x/{id}/edit`, `POST /x/{id}`, `POST /x/{id}/delete` (todas con `AuthMiddleware`).
