# Tasks: Master Layout UI/UX y CRUD Plantel Docente (tasks-003.md)

> Orden estricto de ejecución por fases. Cada tarea desbloquea la siguiente.

---

## Fase 1: Actualización del Core - Router con Parámetros Dinámicos

- [ ] **T1.1** Añadir propiedades `pattern` (string) y `params` (array) a la clase `Route`  
  **Archivo**: `src/Core/Router.php`  
  **Éxito**: `Route` tiene `public string $pattern = '';` y `public array $params = [];`

- [ ] **T1.2** Modificar constructor de `Route` para aceptar y almacenar `$pattern`  
  **Archivo**: `src/Core/Router.php`  
  **Éxito**: `new Route($method, $path, $handler, $pattern)` almacena el patrón regex

- [ ] **T1.3** Implementar método privado `convertPathToRegex(string $path): string`  
  **Archivo**: `src/Core/Router.php`  
  **Éxito**: `convertPathToRegex('/academics/{id}/edit')` retorna `#^/academics/(?P<id>[^/]+)/edit$#`

- [ ] **T1.4** Actualizar `get()` y `post()` para generar patrón regex y pasarlo a `Route`  
  **Archivo**: `src/Core/Router.php`  
  **Éxito**: `$router->get('/academics/{id}/edit', ...)` crea ruta con pattern regex válido

- [ ] **T1.5** Reescribir `dispatch()` para iterar rutas, hacer `preg_match()` y extraer parámetros named groups  
  **Archivo**: `src/Core/Router.php`  
  **Éxito**: `GET /academics/42/edit` matchea route con pattern y `$route->params = ['id' => '42']`

- [ ] **T1.6** Pasar parámetros al handler: closures reciben `$params['id']`, controllers reciben argumentos nombrados  
  **Archivo**: `src/Core/Router.php`  
  **Éxito**: Handler `function($id) { ... }` recibe el valor; Controller `edit(int $id)` recibe el valor

- [ ] **T1.7** Verificar que rutas estáticas existentes siguen funcionando (`/`, `/login`, `/dashboard`, `/academics`, etc.)  
  **Archivo**: `src/Core/Router.php`  
  **Éxito**: Todas las rutas previas responden 200 OK

---

## Fase 2: Master Layout y Refactorización de Dashboards

- [ ] **T2.1** Crear directorio `src/Views/layouts/`  
  **Archivo**: Directorio  
  **Éxito**: `mkdir -p src/Views/layouts` existe

- [ ] **T2.2** Crear `src/Views/layouts/app.php` con estructura completa: Sidebar (Alpine.js), Navbar, Main wrapper, colores institucionales, Alpine.js CDN, Tailwind config  
  **Archivo**: `src/Views/layouts/app.php`  
  **Éxito**: Archivo existe con sidebar responsive, navbar con user menu, `x-data="{sidebarOpen:false}"`, Alpine.js CDN

- [ ] **T2.3** Refactorizar `src/Views/home/dashboard.php` para usar layout: definir `$title`, `$pageTitle`, `$content` y `require layouts/app.php`  
  **Archivo**: `src/Views/home/dashboard.php`  
  **Éxito**: Vista usa layout, muestra "Bienvenido" en main, sin HTML duplicado

- [ ] **T2.4** Refactorizar `admin.php` (raíz) para usar layout: mover a `src/Views/home/admin.php` y adaptar  
  **Archivo**: `src/Views/home/admin.php` (nuevo), `admin.php` (raíz se elimina o redirige)  
  **Éxito**: Panel admin usa layout, cards de stats funcionan, sidebar navega a `/academics`

- [ ] **T2.5** Refactorizar `src/Views/auth/login.php` para usar layout `auth.php` (layout sin sidebar) o mantener standalone si es página pública  
  **Archivo**: `src/Views/auth/login.php`  
  **Éxito**: Login mantiene diseño Tailwind azul/amarillo, funciona sin layout admin

- [ ] **T2.6** Verificar responsive: sidebar fijo en desktop (`lg:pl-64`), drawer en móvil con botón hamburguesa, Alpine.js toggle  
  **Validación**: Chrome DevTools device toolbar - móvil < 1024px muestra drawer, desktop > 1024px muestra sidebar fijo

- [ ] **T2.7** Verificar colores institucionales: `unsxx-blue-900/800` en sidebar/header, `unsxx-yellow-400/300` en botones/hover/focus rings  
  **Validación**: Inspeccionar elementos - variables CSS correctas aplicadas

---

## Fase 3: Modelo Academic.php con Validaciones

- [ ] **T3.1** Crear `src/Models/Academic.php` con namespace `App\Models`, use `App\Config\Database`  
  **Archivo**: `src/Models/Academic.php`  
  **Éxito**: Clase existe, usa `Database::getInstance()`, prepared statements

- [ ] **T3.2** Implementar `findAll(): array` con JOIN a `categoria_academico` ordenado por `nom_acad`  
  **Archivo**: `src/Models/Academic.php`  
  **Éxito**: Retorna array con `id_acad`, `nom_acad`, `foto_acad`, `cel_acad`, `email_acad`, `cat_acad`, `cat_nombre`, `desc_acad`

- [ ] **T3.3** Implementar `findById(int $id): ?array` con JOIN, retorna null si no existe  
  **Archivo**: `src/Models/Academic.php`  
  **Éxito**: `Academic::findById(1)` retorna array completo con `cat_nombre`; ID inexistente → `null`

- [ ] **T3.4** Implementar `getCategories(): array` para select del formulario (cat_id, cat_nombre)  
  **Archivo**: `src/Models/Academic.php`  
  **Éxito**: Retorna 6 filas (Director, Titular, Invitado, Coord. Móvil, Coord. Seguridad, IIDAI)

- [ ] **T3.5** Implementar `create(array $data): int` con validaciones: campos requeridos, email único, cat_acad válido, UUID para foto_acad  
  **Archivo**: `src/Models/Academic.php`  
  **Éxito**: Inserta registro, retorna `lastInsertId`, lanza Exception si validación falla

- [ ] **T3.6** Implementar `update(int $id, array $data): bool` con validaciones (email único excluyendo ID actual)  
  **Archivo**: `src/Models/Academic.php`  
  **Éxito**: Actualiza campos, retorna `true` si `rowCount() > 0`

- [ ] **T3.7** Implementar `delete(int $id): bool` verificando existencia previa  
  **Archivo**: `src/Models/Academic.php`  
  **Éxito**: Retorna `true` si eliminado, `false` si no existía

- [ ] **T3.8** Implementar `emailExists(string $email, ?int $excludeId = null): bool` para validación única  
  **Archivo**: `src/Models/Academic.php`  
  **Éxito**: `Academic::emailExists('test@test.com')` → `true/false`; con excludeId funciona en update

- [ ] **T3.9** Generar UUID para `foto_acad` usando `bin2hex(random_bytes(16))` + extensión detectada  
  **Archivo**: `src/Models/Academic.php` (método helper privado)  
  **Éxito**: `generatePhotoUuid('jpg')` → `a1b2c3d4e5f6...jpg` (32 chars hex + extensión)

---

## Fase 4: Controlador AcademicController.php CRUD Completo

- [ ] **T4.1** Actualizar `index()`: `$academics = Academic::findAll(); view('academic/index', compact('academics'))`  
  **Archivo**: `src/Controllers/AcademicController.php`  
  **Éxito**: `GET /academics` muestra listado con datos reales de BD

- [ ] **T4.2** Actualizar `create()`: `$categories = Academic::getCategories(); view('academic/create', compact('categories'))`  
  **Archivo**: `src/Controllers/AcademicController.php`  
  **Éxito**: `GET /academics/create` carga formulario con select de 6 categorías

- [ ] **T4.3** Implementar `store()`: validar `$_POST`, subir foto (placeholder UUID), `Academic::create()`, redirect con flash message  
  **Archivo**: `src/Controllers/AcademicController.php`  
  **Éxito**: `POST /academics` crea registro, redirige a `/academics` con mensaje éxito

- [ ] **T4.4** Actualizar `edit(int $id)`: buscar académico (404 si null), cargar categorías, view con datos pre-poblados  
  **Archivo**: `src/Controllers/AcademicController.php`  
  **Éxito**: `GET /academics/42/edit` muestra formulario con datos actuales y foto actual

- [ ] **T4.5** Implementar `update(int $id)`: validar, `Academic::update()`, redirect con flash  
  **Archivo**: `src/Controllers/AcademicController.php`  
  **Éxito**: `POST /academics/42` actualiza registro, redirige a listado

- [ ] **T4.6** Implementar `destroy(int $id)`: buscar (404), `Academic::delete()`, redirect con flash  
  **Archivo**: `src/Controllers/AcademicController.php`  
  **Éxito**: `POST /academics/42/delete` elimina, redirige a listado

- [ ] **T4.7** Añadir validación server-side en store/update: campos requeridos, email único, cat_acad existe, formatos  
  **Archivo**: `src/Controllers/AcademicController.php`  
  **Éxito**: Errores de validación muestran mensajes claros, no rompen la app

---

## Fase 5: Vistas CRUD (Listado, Crear, Editar) - UI UNSXX

- [ ] **T5.1** Refactorizar `src/Views/academic/index.php` para usar layout `app.php`: tabla responsiva con columnas Foto, Nombre, Email, Categoría, Celular, Acciones (Editar/Eliminar con iconos SVG)  
  **Archivo**: `src/Views/academic/index.php`  
  **Éxito**: Tabla usa layout, colores azul/amarillo, badges categoría, botones acción con hover amarillo

- [ ] **T5.2** Refactorizar `src/Views/academic/create.php` para usar layout: formulario con select categorías, input file foto, validación HTML5, botón submit azul/amarillo  
  **Archivo**: `src/Views/academic/create.php`  
  **Éxito**: Formulario usa layout, select carga 6 categorías, colores institucionales en focus/submit

- [ ] **T5.3** Refactorizar `src/Views/academic/edit.php` para usar layout: igual a create pero pre-poblado, muestra foto actual, permite cambiarla, hidden `_method=PUT`  
  **Archivo**: `src/Views/academic/edit.php`  
  **Éxito**: Formulario editado usa layout, foto actual visible, select categoría preseleccionada

- [ ] **T5.4** Añadir flash messages (success/error) en layout: variable `$flash` en sesión, mostrar toast arriba del main  
  **Archivo**: `src/Views/layouts/app.php` + `Session` helper  
  **Éxito**: Tras crear/editar/eliminar aparece toast verde/rojo 3s arriba a la derecha

- [ ] **T5.5** Verificar UI UNSXX en las 3 vistas:  
  - Azul (`unsxx-blue-900/800/700`) en headers, bordes, textos principales  
  - Amarillo (`unsxx-yellow-400/300`) en botones submit, focus rings, badges activos, hover sidebar  
  - Focus visible: `focus:ring-2 focus:ring-unsxx-yellow-400` en todos los inputs/botones  
  **Validación**: Checklist visual en las 3 páginas

- [ ] **T5.6** Verificar responsive móvil en las 3 vistas: tabla con scroll horizontal en móvil, formularios apilados, botones full-width  
  **Validación**: Chrome DevTools < 640px - sin overflow horizontal, usabilidad correcta

---

## Fase 6: Rutas y Integración Final

- [ ] **T6.1** Actualizar `src/routes.php` con rutas dinámicas y middlewares auth  
  **Archivo**: `src/routes.php`  
  **Éxito**: 6 rutas académicas registradas con pattern dinámico, middlewares aplicados

- [ ] **T6.2** Probar flujo completo E2E:  
  1. Login → redirect `/dashboard`  
  2. Sidebar → "Plantel Docente" → `/academics` lista datos  
  3. "Nuevo Académico" → formulario → submit → aparece en lista  
  4. Editar → cambia nombre/categoría → guarda → lista actualizada  
  5. Eliminar → confirma → desaparece de lista  
  6. Responsive móvil: menú hamburguesa, drawer, formularios usables  
  **Validación**: Checklist manual completo sin errores

---

## Resumen de Archivos por Fase

| Fase | Archivos Nuevos | Archivos Modificados |
|------|-----------------|---------------------|
| 1 | — | `src/Core/Router.php` |
| 2 | `src/Views/layouts/app.php` | `src/Views/home/dashboard.php`, `src/Views/home/admin.php` (nuevo), `admin.php` (raíz) |
| 3 | `src/Models/Academic.php` | — |
| 4 | — | `src/Controllers/AcademicController.php` |
| 5 | — | `src/Views/academic/index.php`, `create.php`, `edit.php`, `src/Views/layouts/app.php` (flash) |
| 6 | — | `src/routes.php` |

---

**Nota**: Ejecutar fases en orden. No paralelizar. Verificar cada fase antes de pasar a la siguiente.