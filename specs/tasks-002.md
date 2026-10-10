# Tasks: Implementación MVC Base y Autenticación Segura (tasks-002.md)

> Orden estricto de dependencias. Cada tarea desbloquea la siguiente.

---

## Fase 1: Estructura de Carpetas y Configuración Base

- [x] **T1.1** Crear estructura de directorios completa `/src`, `/public`, `/storage`, `/vendor` según plan-002.md  
  **RF**: RF1, RF2 | **Archivos**: Directorios vacíos + `.gitkeep` donde aplique  
  **Éxito**: `tree src public storage` muestra la jerarquía exacta del plan (Config, Core, Middleware, Controllers, Models, Views con subcarpetas)

- [x] **T1.2** Crear `composer.json` con autoload PSR-4 para namespace `App\`  
  **RF**: RF2 | **Archivo**: `composer.json`  
  **Éxito**: `composer dump-autoload` genera `vendor/autoload.php` sin errores; `App\Config\Database` resoluble

- [x] **T1.3** Crear `.env.example` con variables de plantilla (sin secretos)  
  **RF**: RF2 | **Archivo**: `.env.example`  
  **Éxito**: Archivo existe con `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`, `APP_ENV`, `APP_DEBUG`, `SESSION_LIFETIME`

- [x] **T1.4** Crear `.env` real (copia de .env.example con credenciales de CPanel) — **NO comitear**  
  **RF**: RF2 | **Archivo**: `.env` (gitignore)  
  **Éxito**: `.env` existe localmente; `git status` lo muestra como ignorado; variables cargadas en $_ENV

---

## Fase 2: Configuración y Core Wrappers

- [x] **T2.1** Implementar `src/Config/EnvLoader.php` — Carga simple de .env sin dependencias  
  **RF**: RF2 | **Archivo**: `src/Config/EnvLoader.php`  
  **Éxito**: `require 'vendor/autoload.php'; App\Config\EnvLoader::load(); var_dump($_ENV['DB_HOST'])` muestra valor correcto

- [x] **T2.2** Implementar `src/Config/Database.php` — Singleton PDO con prepared statements reales  
  **RF**: RF2 | **Archivo**: `src/Config/Database.php`  
  **Éxito**: `App\Config\Database::getInstance()` devuelve instancia PDO; `ATTR_EMULATE_PREPARES=false`; `ERRMODE_EXCEPTION`; charset utf8mb4

- [x] **T2.3** Implementar `src/Core/Session.php` — Wrapper seguro con regeneración y helpers de rol  
  **RF**: RF4 | **Archivo**: `src/Core/Session.php`  
  **Éxito**: `Session::start()` configura httponly, secure, strict_mode; `Session::regenerate()` llama `session_regenerate_id(true)`; `Session::hasRole('admin')` funciona

- [x] **T2.4** Implementar `src/Core/Router.php` — Enrutador GET/POST exacto con dispatch y 404  
  **RF**: RF1 | **Archivo**: `src/Core/Router.php`  
  **Éxito**: `$router->get('/test', fn() => echo 'ok'); $router->dispatch('GET', '/test')` imprime 'ok'; ruta inexistente → 404

- [x] **T2.5** Implementar `src/Core/Controller.php` — Base abstracta con view(), redirect(), json(), back()  
  **RF**: RF1 | **Archivo**: `src/Core/Controller.php`  
  **Éxito**: Herencia funciona; `$this->view('auth/login', ['error'=>'x'])` incluye vista con variable; `redirect()` hace exit

---

## Fase 3: Modelo de Usuario y Autenticación

- [x] **T3.1** Implementar `src/Models/User.php` — findByEmail() y findById() con JOIN a usuario_rol y rol  
  **RF**: RF3 | **Archivo**: `src/Models/User.php`  
  **Éxito**: `User::findByEmail('contactos@informatica-unsxx.net')` devuelve array con `usu_id`, `usu_pass`, `rol_id`, `rol_nombre`; prepared statement usado

- [x] **T3.2** Implementar `src/Controllers/AuthController.php` — showLogin, login, logout con Session wrapper  
  **RF**: RF3, RF4 | **Archivo**: `src/Controllers/AuthController.php`  
  **Éxito**: `showLogin()` redirige a /dashboard si ya logueado; `login()` valida email/password, usa `password_verify()`, `Session::regenerate()`, puebla `$_SESSION['user']` con id, name, email, role_id, role, logged_at; `logout()` destruye sesión

---

## Fase 4: Vistas y Front Controller

- [x] **T4.1** Crear vista `src/Views/auth/login.php` — Tailwind CDN, formulario POST /login, mensajes de error genéricos  
  **RF**: RF5 | **Archivo**: `src/Views/auth/login.php`  
  **Éxito**: `/login` renderiza HTML válido; muestra error "Credenciales inválidas" para `?error=invalid`; "Complete todos los campos" para `?error=empty`; "Sesión cerrada" para `?logout=1`; `htmlspecialchars` en mensajes

- [x] **T4.2** Crear `src/routes.php` — Registro de todas las rutas (públicas, protegidas, admin)  
  **RF**: RF1 | **Archivo**: `src/routes.php`  
  **Éxito**: Rutas registradas: `/`, `/login` (GET/POST), `/logout`, `/dashboard` (auth), `/admin` (auth+role admin), `/academics` CRUD básico

- [x] **T4.3** Crear `index.php` (raíz) — Front Controller único: carga .env, instancia Router, include routes.php, dispatch  
  **RF**: RF1 | **Archivo**: `index.php`  
  **Éxito**: `php -S localhost:8000` sirve la app; `GET /` → HomeController@index; `GET /login` → AuthController@showLogin; `POST /login` → AuthController@login

---

## Fase 5: Verificación End-to-End (DoD)

- [x] **T5.1** Verificar login exitoso con hash bcrypt real en BD  
  **RF**: RF3, RF4 | **Validación**: `UPDATE usuario SET usu_pass = '$2y$10$...hash_real...' WHERE usu_id=1;` → POST /login con credenciales correctas → redirect a `/admin` (rol=admin) → `$_SESSION['user']['role'] === 'admin'`

- [x] **T5.2** Verificar protección de rutas y regeneración de sesión  
  **RF**: RF4 | **Validación**: Sin login → `GET /dashboard` → 302 `/login`; Login → `session_id()` cambia (regenerate); Logout → sesión destruida, redirect `/login?logout=1`

- [x] **T5.3** Verificar que credenciales no están en código (solo en .env)  
  **RF**: RF2 | **Validación**: `grep -r "DB_PASS\|S@lud4dejulio" src/ .env.example` → solo .env.example muestra placeholder; .env no en git

---

## Resumen de Entregables por Fase

| Fase | Archivos Generados | Dependencia |
|------|-------------------|-------------|
| 1 | Directorios, `composer.json`, `.env.example`, `.env` | — |
| 2 | `EnvLoader.php`, `Database.php`, `Session.php`, `Router.php`, `Controller.php` | Fase 1 |
| 3 | `User.php`, `AuthController.php` | Fase 2 |
| 4 | `login.php`, `routes.php`, `index.php` | Fase 3 |
| 5 | Validaciones manuales (no archivos) | Fase 4 |

---

**Nota**: No paralelizar entre fases. T2.x requiere T1.x completado; T3.x requiere T2.x; T4.x requiere T3.x; T5.x requiere T4.x.