# Plan Técnico: Master Layout UI/UX y CRUD Plantel Docente (plan-003.md)

---

## 1. Actualización del Router para Rutas Dinámicas

### Problema Actual
El `Router.php` actual hace **match exacto** de rutas (`$this->routes[$method][$uri]`). No soporta parámetros dinámicos como `/academics/{id}/edit`.

### Solución: Match con Expresiones Regulares Simples

#### Cambios en `src/Core/Router.php`

1. **Convertir rutas estáticas a patrones regex** al registrarlas:
```php
// En get() y post():
$pattern = $this->convertPathToRegex($path);  // "/academics/{id}/edit" → "#^/academics/([^/]+)/edit$#"
$route = new Route($method, $path, $handler, $pattern);
```

2. **Método auxiliar `convertPathToRegex`**:
```php
private function convertPathToRegex(string $path): string
{
    // Escapar caracteres regex excepto {param}
    $pattern = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', 
        fn($m) => '(?P<' . $m[1] . '>[^/]+)', 
        preg_quote($path, '#')
    );
    return '#^' . $pattern . '$#';
}
```

3. **En `dispatch()`: iterar y match regex + extraer parámetros**:
```php
foreach ($this->routes[$method] as $route) {
    if (preg_match($route->pattern, $uri, $matches)) {
        // Filtrar solo named groups (parámetros)
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        $route->params = $params;
        // Ejecutar middlewares + handler con $params
    }
}
```

4. **Route class: añadir propiedades `pattern` y `params`**:
```php
class Route {
    public string $pattern;
    public array $params = [];
    // ... existing properties
}
```

5. **Pasar parámetros al handler**:
   - Closure: `function($id) { ... }` recibe `$params['id']`
   - Controller array: se inyecta en el método como argumentos nombrados

#### Rutas a Registrar en `src/routes.php` (actualizado)
```php
$router->get('/academics', [AcademicController::class, 'index'])->middleware(...);
$router->get('/academics/create', [AcademicController::class, 'create'])->middleware(...);
$router->post('/academics', [AcademicController::class, 'store'])->middleware(...);
$router->get('/academics/{id}/edit', [AcademicController::class, 'edit'])->middleware(...);
$router->post('/academics/{id}', [AcademicController::class, 'update'])->middleware(...);
$router->post('/academics/{id}/delete', [AcademicController::class, 'destroy'])->middleware(...);
```

---

## 2. Master Layout: `src/Views/layouts/app.php`

### Estructura General (Tailwind CSS + Alpine.js CDN)

```html
<!-- src/Views/layouts/app.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Ingeniería Informática UNSXX' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { /* unsxx-blue, unsxx-yellow */ } } }
        }
    </script>
</head>
<body class="min-h-screen bg-unsxx-blue-50" x-data="{ sidebarOpen: false }">
    <!-- Sidebar (fixed desktop, drawer mobile) -->
    <aside 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        @click="sidebarOpen = false"
        aria-hidden="true"
    ></aside>
    
    <aside 
        class="fixed inset-y-0 left-0 z-50 w-64 bg-unsxx-blue-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0"
        :class="{ '-translate-x-full': !sidebarOpen }"
        @click.outside="sidebarOpen = false"
    >
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b border-unsxx-blue-800">
            <a href="/dashboard" class="flex items-center gap-3 px-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-unsxx-blue-700 to-unsxx-blue-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-unsxx-yellow-300" ...></svg>
                </div>
                <span class="text-lg font-bold text-white">Informática UNSXX</span>
            </a>
        </div>
        
        <!-- Navigation -->
        <nav class="p-4 space-y-1" role="navigation" aria-label="Menú principal">
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                <svg class="w-5 h-5" ...></svg>
                <span>Dashboard</span>
            </a>
            <a href="/academics" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-unsxx-blue-100 hover:bg-unsxx-blue-800 hover:text-white transition">
                <svg class="w-5 h-5" ...></svg>
                <span>Plantel Docente</span>
            </a>
            <!-- Más enlaces: Laboratorios, Extensión, Comunicados, etc. -->
        </nav>
    </aside>

    <!-- Overlay para móvil -->
    <div class="lg:hidden" x-show="sidebarOpen" x-transition @click="sidebarOpen = false"></div>

    <!-- Main Wrapper -->
    <div class="lg:pl-64 min-h-screen">
        <!-- Top Navbar -->
        <header class="sticky top-0 z-30 bg-white border-b border-unsxx-blue-100 shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <!-- Mobile menu button -->
                <button 
                    @click="sidebarOpen = true" 
                    class="lg:hidden p-2 rounded-lg text-unsxx-blue-600 hover:bg-unsxx-blue-100 transition"
                    aria-label="Abrir menú"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                <!-- Page Title -->
                <h1 class="text-xl font-bold text-unsxx-blue-900 lg:pl-4"><?= $pageTitle ?? 'Panel' ?></h1>
                
                <!-- User Menu -->
                <div class="flex items-center gap-4">
                    <span class="text-sm text-unsxx-blue-600 hidden sm:block">
                        <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Usuario') ?>
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-unsxx-yellow-100 text-unsxx-yellow-800 border border-unsxx-yellow-300">
                        <?= htmlspecialchars($_SESSION['user']['role'] ?? 'user') ?>
                    </span>
                    <a href="/logout" class="px-4 py-2 text-sm font-medium text-unsxx-blue-700 hover:text-unsxx-blue-900 transition">
                        Cerrar sesión
                    </a>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8">
            <?= $content ?? '' ?>
        </main>
    </div>

    <!-- Scripts adicionales -->
    @stack('scripts')
</body>
</html>
```

### Uso en Vistas (extender layout)
```php
<!-- En cada vista: src/Views/academic/index.php -->
<?php
$title = 'Plantel Docente';
$pageTitle = 'Académicos';
$content = <<<'HTML'
    <!-- Contenido específico de la vista -->
HTML;
require __DIR__ . '/../../layouts/app.php';
```

---

## 3. Modelo `src/Models/Academic.php` - Métodos CRUD

### Estructura de la Tabla (recordatorio)
```sql
academico:
  id_acad (PK), nom_acad, foto_acad (UUID), cel_acad (VARCHAR20), 
  email_acad (UNIQUE), cat_acad (FK→categoria_academico.cat_id), 
  desc_acad, usu_id (FK→usuario.usu_id, NULL)
  
categoria_academico:
  cat_id (PK), cat_nombre (UNIQUE)
```

### Métodos Requeridos

| Método | Descripción | SQL / Lógica |
|--------|-------------|--------------|
| `findAll(): array` | Listar todos con categoría (JOIN) | `SELECT a.*, c.cat_nombre FROM academico a JOIN categoria_academico c ON a.cat_acad = c.cat_id ORDER BY a.nom_acad` |
| `findById(int $id): ?array` | Obtener uno por ID con categoría | `SELECT a.*, c.cat_nombre FROM academico a JOIN categoria_academico c ON a.cat_acad = c.cat_id WHERE a.id_acad = ?` |
| `getCategories(): array` | Catálogo para selects del formulario | `SELECT cat_id, cat_nombre FROM categoria_academico ORDER BY cat_nombre` |
| `create(array $data): int` | Insertar nuevo académico | `INSERT INTO academico (nom_acad, foto_acad, cel_acad, email_acad, cat_acad, desc_acad, usu_id) VALUES (?, ?, ?, ?, ?, ?, NULL)` → retorna `lastInsertId` |
| `update(int $id, array $data): bool` | Actualizar académico | `UPDATE academico SET nom_acad=?, foto_acad=?, cel_acad=?, email_acad=?, cat_acad=?, desc_acad=? WHERE id_acad=?` |
| `delete(int $id): bool` | Eliminar (validar existencia) | `DELETE FROM academico WHERE id_acad=?` (verificar `rowCount()` > 0) |
| `emailExists(string $email, ?int $excludeId = null): bool` | Validar email único | `SELECT COUNT(*) FROM academico WHERE email_acad = ? AND id_acad != ?` |

### Validaciones en Modelo
- `nom_acad`: requerido, max 300 chars
- `email_acad`: formato válido, único (excepto en update)
- `cel_acad`: requerido, max 20 chars
- `cat_acad`: debe existir en `categoria_academico`
- `foto_acad`: UUID generado en PHP (`bin2hex(random_bytes(16))` + extensión)
- `desc_acad`: requerido, LONGTEXT

---

## 4. Controlador `AcademicController.php` - Métodos Actualizados

| Método | Ruta | Acción |
|--------|------|--------|
| `index()` | `GET /academics` | `$academics = Academic::findAll(); view('academic/index', compact('academics'))` |
| `create()` | `GET /academics/create` | `$categories = Academic::getCategories(); view('academic/create', compact('categories'))` |
| `store()` | `POST /academics` | Validar → `Academic::create($data)` → redirect `/academics` con flash success |
| `edit(int $id)` | `GET /academics/{id}/edit` | `$academic = Academic::findById($id)` (404 si null) → `$categories = Academic::getCategories()` → view |
| `update(int $id)` | `POST /academics/{id}` | Validar → `Academic::update($id, $data)` → redirect `/academics` |
| `destroy(int $id)` | `POST /academics/{id}/delete` | `$academic = Academic::findById($id)` (404 si null) → `Academic::delete($id)` → redirect |

---

## 5. Vistas Requeridas (extienden `layouts/app.php`)

| Vista | Descripción |
|-------|-------------|
| `src/Views/academic/index.php` | Tabla responsiva con columnas: Foto, Nombre, Email, Categoría, Celular, Acciones (Editar/Eliminar) |
| `src/Views/academic/create.php` | Formulario con select de categorías cargado dinámicamente, input file para foto (placeholder UUID) |
| `src/Views/academic/edit.php` | Igual a create pero pre-poblado, muestra foto actual, permite cambiarla |

---

## 6. Pasos de Implementación (Orden Lógico)

| Paso | Acción | Archivos Afectados |
|------|--------|-------------------|
| 1 | Actualizar `Router.php` para regex dinámicos | `src/Core/Router.php` |
| 2 | Crear `layouts/app.php` (Master Layout) | `src/Views/layouts/app.php` |
| 3 | Refactorizar vistas existentes a usar layout | `src/Views/academic/index.php`, `create.php`, `edit.php`, `dashboard.php`, `admin.php`, `auth/login.php` |
| 4 | Implementar `Academic.php` modelo completo | `src/Models/Academic.php` |
| 5 | Actualizar `AcademicController.php` con lógica CRUD real | `src/Controllers/AcademicController.php` |
| 5 | Actualizar `routes.php` con nuevas rutas dinámicas | `src/routes.php` |
| 6 | Probar: listado, crear, editar, eliminar, responsive mobile | Verificación manual |

---

## 7. Detalles UI/UX Clave

- **Colores**: `unsxx-blue-900/800/700` (sidebar, headers), `unsxx-yellow-400/300` (accent, buttons, focus rings)
- **Responsive**: Sidebar fijo `lg:pl-64`, drawer mobile con Alpine.js `x-data="{sidebarOpen:false}"`
- **Accesibilidad**: `aria-label`, `focus:outline-none focus:ring-2`, semántica HTML5
- **JS Puro/Alpine**: Solo toggle sidebar, sin dependencias pesadas