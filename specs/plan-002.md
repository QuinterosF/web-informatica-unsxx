# Plan Técnico: Arquitectura MVC Base y Autenticación Segura (plan-002.md)

---

## 1. Estructura de Directorios Objetivo

```
informatica-unsxx-main/
├── .env                          # Credenciales BD (NO en git)
├── .env.example                  # Plantilla sin secretos
├── index.php                     # FRONT CONTROLLER (único punto de entrada)
├── composer.json                 # Autoload PSR-4
├── public/                       # Assets estáticos (css, js, images, fonts)
│   ├── css/
│   ├── js/
│   ├── images/
│   └── fonts/
├── src/                          # Código PHP de aplicación (fuera de public/)
│   ├── Config/
│   │   ├── Database.php          # Clase Singleton PDO
│   │   └── EnvLoader.php         # Carga .env simple
│   ├── Core/
│   │   ├── Router.php            # Enrutador simple (GET/POST → Controller@method)
│   │   ├── Controller.php        # Base Controller con helpers (view, redirect, json)
│   │   ├── Request.php           # Wrapper $_GET/$_POST/$_FILES sanitizado
│   │   ├── Response.php          # Wrapper headers + body
│   │   └── Session.php           # Wrapper seguro $_SESSION
│   ├── Middleware/
│   │   ├── AuthMiddleware.php    # Verifica sesión + rol
│   │   ├── CsrfMiddleware.php    # Token CSRF en formularios
│   │   └── RateLimitMiddleware.php # Login rate limiting (opcional)
│   ├── Controllers/
│   │   ├── AuthController.php    # login, logout, register (futuro)
│   │   ├── HomeController.php    # Dashboard público + admin
│   │   ├── AcademicController.php
│   │   ├── LabController.php
│   │   ├── ExtensionController.php
│   │   ├── ComunicadoController.php
│   │   ├── AdmissionController.php
│   │   └── ConfigController.php  # config_inicio singleton
│   ├── Models/
│   │   ├── User.php              # Usuario + roles (usuario, usuario_rol, rol)
│   │   ├── Academic.php
│   │   ├── Laboratory.php
│   │   ├── Extension.php
│   │   ├── Comunicado.php
│   │   ├── Admission.php
│   │   ├── Cellular.php
│   │   ├── Achievement.php       # logro
│   │   └── ConfigInicio.php
│   └── Views/
│       ├── layouts/
│       │   ├── app.php           # Layout principal (header, footer, nav)
│       │   └── auth.php          # Layout login (sin nav)
│       ├── auth/
│       │   └── login.php         # Vista login con Tailwind (RF5)
│       ├── home/
│       │   ├── index.php         # Dashboard público
│       │   └── admin.php         # Dashboard admin (si rol=admin)
│       ├── academic/
│       │   ├── index.php
│       │   ├── create.php
│       │   └── edit.php
│       └── ... (resto de vistas por módulo)
├── storage/                      # Archivos subidos (fuera de public/)
│   ├── extension/
│   ├── comunicados/
│   ├── admission/
│   ├── academics/
│   └── labs/
├── vendor/                       # composer install (gitignore)
└── docs/                         # Documentación existente
```

### Convenciones de Nombrado
| Elemento | Convención | Ejemplo |
|----------|------------|---------|
| Clases PHP | PascalCase | `AuthController`, `Database` |
| Métodos | camelCase | `loginAction`, `getUserByEmail` |
| Archivos | PascalCase (clases), kebab-case (vistas) | `User.php`, `login.php` |
| Namespaces | `App\Config`, `App\Core`, `App\Controllers`, `App\Models`, `App\Middleware` |
| Rutas URL | kebab-case | `/admin/academics`, `/login` |

---

## 2. Clase Database: Conexión PDO Segura (Singleton)

### Archivo: `src/Config/Database.php`

```php
namespace App\Config;

class Database
{
    private static ?\PDO $instance = null;

    private function __construct() {}
    private function __clone() {}
    public function __wakeup() {}

    public static function getInstance(): \PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $_ENV['DB_HOST'] ?? 'localhost',
                $_ENV['DB_NAME'] ?? 'informaticabd'
            );

            self::$instance = new \PDO(
                $dsn,
                $_ENV['DB_USER'] ?? 'root',
                $_ENV['DB_PASS'] ?? '',
                [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES   => false,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ]
            );
        }
        return self::$instance;
    }
}
```

### Archivo: `src/Config/EnvLoader.php` (Simple, sin dependencias)

```php
namespace App\Config;

class EnvLoader
{
    public static function load(string $path = __DIR__ . '/../../.env'): void
    {
        if (!file_exists($path)) return;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            if (strpos($line, '=') === false) continue;

            [$key, $value] = array_map('trim', explode('=', $line, 2));
            $value = trim($value, '"\'');
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}
```

### `.env.example` (comiteable)
```env
DB_HOST=localhost
DB_NAME=informaticabd
DB_USER=root
DB_PASS=
APP_ENV=production
APP_DEBUG=false
SESSION_LIFETIME=7200
```

---

## 3. Flujo de Autenticación Segura (RF3, RF4)

### 3.1 Login Flow (POST `/login`)

```
1. Request → index.php → Router → AuthController@loginAction()
2. RateLimitMiddleware (opcional): max 5 intentos/15min por IP
3. Validación entrada:
   - email: filter_var(FILTER_VALIDATE_EMAIL)
   - password: strlen >= 8
4. User Model: findByEmail($email) → SELECT usu_id, usu_nom, usu_correo, usu_pass, estado, rol_id FROM usuario u JOIN usuario_rol ur ON u.usu_id=ur.usu_id WHERE u.usu_correo=? AND u.estado=1 LIMIT 1
5. Verificación:
   if ($user && password_verify($password, $user['usu_pass'])) {
       // ÉXITO
   } else {
       // FALLO: mensaje genérico "Credenciales inválidas"
   }
6. ÉXITO → Session::regenerate() (session_regenerate_id(true))
7. Poblar $_SESSION:
   $_SESSION['user'] = [
       'id'       => $user['usu_id'],
       'name'     => $user['usu_nom'],
       'email'    => $user['usu_correo'],
       'role_id'  => $user['rol_id'],
       'role'     => $user['rol_nombre'],
       'logged_at'=> time()
   ];
8. Redirect → /dashboard (o /admin si role=admin)
```

### 3.2 Session Wrapper (`src/Core/Session.php`)

```php
namespace App\Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? '1' : '0');
            ini_set('session.use_strict_mode', '1');
            session_start();
        }
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        self::start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public static function getUser(): ?array
    {
        return self::get('user');
    }

    public static function isLoggedIn(): bool
    {
        return self::has('user');
    }

    public static function getRole(): ?string
    {
        return self::get('user.role');
    }

    public static function hasRole(string ...$roles): bool
    {
        $userRole = self::getRole();
        return $userRole && in_array($userRole, $roles, true);
    }
}
```

### 3.3 Auth Middleware (`src/Middleware/AuthMiddleware.php`)

```php
namespace App\Middleware;

use App\Core\Session;

class AuthMiddleware
{
    public function handle(): bool
    {
        Session::start();
        if (!Session::isLoggedIn()) {
            header('Location: /login');
            exit;
        }
        $loggedAt = Session::get('user.logged_at');
        $lifetime = (int)($_ENV['SESSION_LIFETIME'] ?? 7200);
        if ($loggedAt && (time() - $loggedAt > $lifetime)) {
            Session::destroy();
            header('Location: /login?expired=1');
            exit;
        }
        return true;
    }

    public function requireRole(string ...$roles): void
    {
        if (!$this->handle() || !Session::hasRole(...$roles)) {
            header('Location: /unauthorized');
            exit;
        }
    }
}
```

### 3.4 Logout Flow (GET `/logout`)

```
AuthController@logoutAction()
→ Session::destroy()
→ Redirect /login?logout=1
```

---

## 4. Router Simple (`src/Core/Router.php`)

```php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        $uri = parse_url($uri, PHP_URL_PATH);

        if (isset($this->routes[$method][$uri])) {
            $this->callHandler($this->routes[$method][$uri]);
            return;
        }

        http_response_code(404);
        require __DIR__ . '/../../src/Views/404.php';
    }

    private function callHandler(mixed $handler): void
    {
        if (is_callable($handler)) {
            $handler();
        } elseif (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;
            (new $controller())->$method();
        }
    }
}
```

### Registro de Rutas (en `index.php`)

```php
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/dashboard', [HomeController::class, 'dashboard'])->middleware(AuthMiddleware::class);
$router->get('/admin', [HomeController::class, 'adminDashboard'])->middleware([AuthMiddleware::class, 'requireRole', 'admin']);

$router->get('/academics', [AcademicController::class, 'index']);
$router->get('/academics/create', [AcademicController::class, 'create']);
$router->post('/academics', [AcademicController::class, 'store']);
$router->get('/academics/{id}/edit', [AcademicController::class, 'edit']);
$router->post('/academics/{id}', [AcademicController::class, 'update']);
$router->post('/academics/{id}/delete', [AcademicController::class, 'destroy']);
// ... resto de módulos
```

---

## 5. Front Controller (`index.php`)

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

\App\Config\EnvLoader::load();

$router = new \App\Core\Router();
require_once __DIR__ . '/src/routes.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$router->dispatch($method, $uri);
```

---

## 6. Controlador Base (`src/Core/Controller.php`)

```php
namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../../Views/{$view}.php";
    }

    protected function redirect(string $url, int $code = 302): void
    {
        header("Location: {$url}", true, $code);
        exit;
    }

    protected function json(mixed $data, int $code = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
}
```

---

## 7. AuthController (`src/Controllers/AuthController.php`)

```php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login', ['error' => $_GET['error'] ?? null]);
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $this->redirect('/login?error=empty');
        }

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['usu_pass'])) {
            Session::regenerate();
            Session::set('user', [
                'id'       => $user['usu_id'],
                'name'     => $user['usu_nom'],
                'email'    => $user['usu_correo'],
                'role_id'  => $user['rol_id'],
                'role'     => $user['rol_nombre'],
                'logged_at'=> time()
            ]);
            $this->redirect($user['rol_nombre'] === 'admin' ? '/admin' : '/dashboard');
        }

        $this->redirect('/login?error=invalid');
    }

    public function logout(): void
    {
        Session::destroy();
        $this->redirect('/login?logout=1');
    }
}
```

---

## 8. Modelo User (`src/Models/User.php`)

```php
namespace App\Models;

use App\Config\Database;

class User
{
    public static function findByEmail(string $email): ?array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT u.usu_id, u.usu_nom, u.usu_correo, u.usu_pass, u.estado,
                   ur.rol_id, r.rol_nombre
            FROM usuario u
            JOIN usuario_rol ur ON u.usu_id = ur.usu_id
            JOIN rol r ON ur.rol_id = r.rol_id
            WHERE u.usu_correo = ? AND u.estado = 1
            LIMIT 1
        ");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public static function findById(int $id): ?array
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("
            SELECT u.usu_id, u.usu_nom, u.usu_correo, u.estado,
                   ur.rol_id, r.rol_nombre
            FROM usuario u
            JOIN usuario_rol ur ON u.usu_id = ur.usu_id
            JOIN rol r ON ur.rol_id = r.rol_id
            WHERE u.usu_id = ? AND u.estado = 1
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
```

---

## 9. Vista Login con Tailwind (`src/Views/auth/login.php`)

```php
<!-- RF5: UI Login moderna, minimalista, Tailwind CDN -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Informática UNSXX</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Ingeniería Informática</h1>
            <p class="text-gray-500 mt-1">Universidad Nacional "Siglo XX"</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <?php
                    $msg = match($error) {
                        'empty'   => 'Complete todos los campos.',
                        'invalid' => 'Credenciales inválidas.',
                        'expired' => 'Su sesión ha expirado. Inicie sesión nuevamente.',
                        default   => 'Error desconocido.'
                    };
                    echo htmlspecialchars($msg);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['logout'])): ?>
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                Sesión cerrada correctamente.
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                <input type="email" id="email" name="email" required autocomplete="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="usuario@unsxx.edu.bo">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="••••••••">
            </div>
            <button type="submit"
                class="w-full py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                Iniciar sesión
            </button>
        </form>
    </div>
</body>
</html>
```

---

## 10. Checklist de Implementación (DoD)

| # | Entregable | Verificación |
|---|------------|--------------|
| 1 | Estructura `/src` creada con namespaces PSR-4 | `composer dump-autoload` OK |
| 2 | `Database::getInstance()` devuelve PDO con prepared statements | `SELECT 1` ejecuta sin error |
| 3 | `.env` cargado, credenciales no en código | `var_dump($_ENV['DB_PASS'])` no muestra pass en repo |
| 4 | `AuthController::login()` usa `password_verify()` | Login con hash bcrypt funciona |
| 5 | `session_regenerate_id(true)` en login exitoso | Session ID cambia tras login |
| 6 | `$_SESSION['user']` contiene `id`, `role_id`, `role` | `var_dump($_SESSION)` muestra estructura |
| 7 | Vista login con Tailwind CDN, mensaje genérico | `/login` renderiza sin errores |
| 8 | Middleware Auth protege `/dashboard` y `/admin` | Sin login → redirect `/login` |
| 9 | Logout destruye sesión y redirige | `/logout` → `/login?logout=1` |

---

## 11. Próximos Pasos (Fase 3+)

- **Spec 003**: CRUD genérico + DataTables server-side (Ajax)
- **Spec 004**: Subida de archivos con UUID + validación MIME
- **Spec 005**: API JSON para frontend admin (vue/alpine)
- **Spec 006**: Tests PHPUnit + E2E Cypress