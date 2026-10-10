# Spec 003: Master Layout UI/UX y Módulo CRUD de Plantel Docente

## 1. Contexto y Objetivos
El sistema de autenticación MVC base está funcionando. Sin embargo, el dashboard actual es una vista estática sin navegación, y presenta solapamiento de elementos en dispositivos móviles. 
El objetivo de este ciclo es doble: 
1. Construir un `Master Layout` responsivo (Sidebar + Navbar) que sirva como plantilla base para todo el panel de administración, solucionando los problemas de UI en móviles.
2. Implementar el primer módulo de gestión (CRUD) para la tabla `academico` (Plantel Docente), conectándolo con la tabla `categoria_academico`.

## 2. Requisitos Funcionales (RF)
*   **RF1 - Master Layout (UI/UX):** Crear un archivo `src/Views/layouts/app.php`. Debe incluir:
    *   Un **Sidebar** lateral izquierdo (fijo en escritorio, oculto en móvil con botón hamburguesa para desplegarlo).
    *   Un **Navbar** superior con el nombre del usuario logueado (`$_SESSION['user']['name']`) y botón de cerrar sesión.
    *   Uso estricto de colores institucionales (Azul oscuro para la barra/sidebar, acentos Amarillos para hover/botones activos).
    *   Javascript nativo o Alpine.js (vía CDN) para el toggle del menú móvil.
*   **RF2 - Listado de Docentes (Read):** Ruta `GET /academics`. Una tabla Tailwind moderna que muestre nombre, email, celular y categoría del académico.
*   **RF3 - Creación de Docentes (Create):** Ruta `GET /academics/create` (formulario) y `POST /academics`. El formulario debe cargar dinámicamente las categorías desde la tabla `categoria_academico`. *Nota: Para la foto (`foto_acad`), por ahora se insertará un UUID generado por PHP genérico hasta que hagamos el módulo de subida de archivos.*
*   **RF4 - Edición y Eliminación (Update/Delete):** Rutas `GET /academics/{id}/edit`, `POST /academics/{id}` y `POST /academics/{id}/delete`. Validar que el registro exista antes de operar.

## 3. Requisitos No Funcionales (RNF)
*   Todo el HTML debe extender del layout principal para no repetir cabeceras (DRY).
*   La interacción de la base de datos debe hacerse a través del modelo `src/Models/Academic.php`.
*   Mantener el enrutador simple. Las rutas dinámicas con `{id}` deben ser parseadas correctamente en `Router.php` si no lo están soportando actualmente.

## 4. Criterios de Finalización (DoD)
1. Un agente IA generó el plan técnico `plan-003.md`.
2. El layout móvil no presenta solapamiento y el menú hamburguesa funciona.
3. Se puede crear, ver, editar y eliminar un registro de docente en la base de datos desde la interfaz web.