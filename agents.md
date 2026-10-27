# Proyecto: Web Informática UNSXX Refactor

Web institucional de Ingeniería Informática (UNSXX). MVC propio en **PHP 8 nativo**, sin Composer ni frameworks; MySQL/MariaDB vía PDO; Tailwind CDN + SweetAlert2 + JS vanilla. Destino: hosting compartido/cPanel.

## Reglas operativas
- Lee `docs/constitution.md` antes de escribir código.
- Seguridad obligatoria: prepared statements, `password_hash`/`password_verify`, MIME real en subidas, `htmlspecialchars` en salidas y validación de sesión/rol en cada endpoint.
- SDD: revisa `specs/spec-*.md` y `specs/plan-*.md` antes de implementar; no inventes requisitos.
- Comentarios en español; variables y funciones en inglés.
- Referencia visual: `DESIGN.md`, cuya fuente de verdad es `design-system/MASTER.md`. `DESIGN.md` advierte que aún no autoriza modificar vistas PHP.

## Comandos y verificación
- Desarrollo desde la raíz: `php -S localhost:8000 server.php` (no `-t public`). `server.php` sirve archivos físicos de `public/` y delega el resto a `public/index.php`.
- La app no usa Composer ni npm; `.opencode/` es solo tooling de OpenCode. No hay linter, CI ni migraciones automáticas. **No inventes** comandos.
- Verifica sintaxis con `php -l ruta\archivo.php`. Para todo `src`: `Get-ChildItem src -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }`.
- No hay suite de tests: para probar modelos/controladores usa un script temporal con `require 'src/autoload.php'; \App\Config\EnvLoader::load();` y limpia los datos creados.
- `database/database_v2.sql` no refleja el esquema vivo (faltan columnas como `celular.es_soporte`). Antes de escribir SQL, verifica con `SHOW COLUMNS`/`information_schema`.
- `.env` contiene credenciales locales y está versionado: no expongas su contenido ni lo modifiques sin necesidad. No hay `.gitignore` raíz: `public/uploads/` no está ignorado, evita añadirlo en bloque.

## Flujo y arquitectura
- Flujo: `server.php` → `public/index.php` (sesión, `chdir()` a la raíz, autoload, `.env`, `America/La_Paz`) → `src/routes.php` → `App\Core\Router`.
- Autoload PSR-4 manual: `App\Foo\Bar` → `src/Foo/Bar.php`; no hay `vendor/`.
- `AuthMiddleware` está en `src/Middleware/AuthMiddleware.php` (`App\Middleware`), no en `Core`.
- Middleware de rutas: auth `[AuthMiddleware::class, 'handle']`, módulos `$moduleGuard(...)`, admin `$adminOnly`.
- `$moduleGuard` usa claves de `User::MODULES`, no el prefijo URL: `/announcements`→`communications`, `/labs`→`laboratories`, `/mentions`→`curricular`, `/directory`→`redes`. `/admin/logros` usa el módulo `logros`, **no** es `$adminOnly`.
- El router inyecta parámetros **por nombre** vía reflexión: el argumento debe escribirse igual que `{param}`. El repo usa guiones bajos (`$id_lab`, `$id_foto`, `$id_cat`, `$id_ext`); un nombre distinto no se inyecta.
- Controladores extienden `App\Core\Controller`; `view()` hace `extract($data)` y añade `.php` a la ruta exacta bajo `src/Views/` (`public/home` = `public/home.php`, no `public/home/index.php`). Las vistas del panel cargan `layouts/app.php`; las públicas, `layouts/public.php`.
- Sesión: el login guarda `$_SESSION['user']` (`id`, `name`, `email`, `role_id`, `role`, `logged_at`) y también `user_id`, `user_name`, `user_role`, `user_modules`, `user_acad_id`, `user_lab_ids`. Usa `Session`/`Auth`; `Auth::isAdmin()` compara `role` con el string `admin`.
- `/dashboard` es el panel único; `/admin` redirige a `/dashboard` y `HomeController::adminDashboard()` solo redirige.

## Base de datos
- Variables: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `SESSION_LIFETIME`; `EnvLoader::load()` las carga en `$_ENV`.
- `Database::getInstance()` es un singleton PDO con excepciones, `FETCH_ASSOC`, `EMULATE_PREPARES=false` y utf8mb4.

## Invariantes de dominio
- Root: `private const ROOT_USER_ID = 1` en `App\Models\User`; siempre admin, activo y sin módulos. `update()`/`toggleStatus()` lo protegen y nadie puede desactivar su propia cuenta.
- Operadores: quedan inactivos sin módulos ni laboratorio activo. Tras cambios de responsable llama `User::syncOperatorStatusForAcademic()`.
- Responsables de laboratorio (sin ser admin) entran en `ownerMode`: solo gestionan sus labs, no pueden crear/eliminar laboratorios ni reasignar el responsable.
- El teléfono de soporte se mantiene único; `(new Phone())->setAsSupport($id)` es transaccional y el login usa `(new Phone())->getSupportPhone()`. Ambos son métodos de instancia, no estáticos.
- Mantén el patrón `AuditLogger::logDelete()` para borrados del panel.
- Portal público: rutas sin auth en `HomeController`; datos vía `App\Models\PublicPortal`. `getCommonLayoutData()` alimenta navbar/footer con `$enlacesServicios`, `$enlacesInvestigacion`, `$enlacesFooterInstitucional`, `$enlacesRedesSociales` (JOIN `enlace_servicio`/`ubicacion_enlace`, `estado=1`) y `$telefonoSoporte`; `renderPublicView()` los inyecta en todas las vistas.
- Portal público: respeta `mostrar_cel`/`mostrar_email`. La vista se resuelve por ruta exacta (`/carrera` → `public/carrera/nosotros`, no `public/carrera.php`); varias rutas públicas aún no tienen archivo y el resolver termina con `die("Vista no encontrada")`.
- `/logros` es público; el CRUD de logros vive en `/admin/logros` con guard de módulo.

## Subidas
- Usa siempre `App\Core\FileUploader`; no reimplementes validaciones. Imágenes: jpeg/png/webp; documentos: PDF. Renombra con `uniqid()` (la constitución menciona UUID, el código no lo usa) y devuelve solo el nombre de archivo.
- Carpetas en uso: imágenes `academic`, `admission`, `menciones`, `logros`, `laboratorios`, `extension`, `comunicados`, `portadas`, `institucion`; PDFs `documents`, `laboratorios`, `comunicados`.
- `uploadImage($file, 'academic')` → `public/uploads/img/academic/`; `uploadPdf($file, 'documents')` → `public/uploads/pdf/documents/`.
- `delete($filename, 'img/academic')`/`delete($filename, 'pdf/documents')`: el segundo argumento es la carpeta compuesta, no solo `img`/`pdf`.
- Actualización: archivo nuevo > checkbox `remove_*` > conservar existente; borra el anterior. Nunca borres los `default*` (`default.png`, `default-lab.png`).

## Módulos
- Cada módulo: modelo en `src/Models`, controlador en `src/Controllers`, vistas en `src/Views/{modulo}` y rutas.
- CRUD base: `GET /x`, `GET /x/create`, `POST /x`, `GET /x/{id}/edit`, `POST /x/{id}/edit`, `POST /x/{id}`, `POST /x/{id}/delete`, cada una con su guard.
- Galerías/categorías usan rutas con `{id_lab}`, `{id_foto}`, `{id_ext}`, `{id_cat}`; replica esos nombres en los argumentos del controlador.
