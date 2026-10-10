# Informe de Contexto de Continuidad de Proyecto

**Rol:** Arquitecto de Software Principal  
**Proyecto:** Refactorización Web Institucional — Carrera de Ingeniería Informática UNSXX  
**Objetivo del Prompt:** Inicializar sesión en un nuevo chat de IA proporcionando el contexto técnico, arquitectónico y operativo exacto del sistema.

---

## 1. Stack Tecnológico Definitivo

* **Arquitectura Base:** Patron MVC (Modelo-Vista-Controlador) personalizado desarrollado en **PHP nativo (>8.0)**.
* **Base de Datos:** **MySQL / MariaDB**, administrada directamente a través de **phpMyAdmin**.
* **Entorno de Despliegue:** Servidor web para entorno de **hosting tradicional (cPanel)**.
* **Aclaración de Exclusión de Stack (CRÍTICO):** Se descarta explícitamente cualquier uso de **Next.js, React, Node.js o Supabase / Firebase**. Todas las directivas previas de migración a la nube o stacks modernos basadas en JS contenidas en borradores de la "Constitución SDD" han sido **oficialmente descartadas** en favor de este stack nativo en PHP.

---

## 2. Arquitectura y Enrutamiento Resuelto

Para asegurar un funcionamiento correcto en el servidor integrado de desarrollo y en entornos de producción sin fallos de rutas o archivos estáticos:

* **Proxy de Servidor (`server.php`):** Ubicado en la raíz del proyecto, actúa como proxy para servir archivos estáticos (PDFs, imágenes, CSS, JS) y evitar errores 404 al omitir la intercepción de rutas dinámicas.
* **Front Controller (`public/index.php`):** Punto único de entrada de todas las peticiones HTTP. Incluye la directiva `chdir(dirname(__DIR__));` al inicio para resolver de forma consistente el directorio de trabajo raíz de la aplicación.
* **Controlador Base (`src/Core/Controller.php`):** Proporciona los métodos `render()` y `view()`, utilizando rutas absolutas resueltas mediante `realpath(__DIR__ . '/../Views')` para garantizar la localización de plantillas y vistas sin importar el nivel de anidamiento de las URLs.

---

## 3. Módulos y Seguridad Completados

* **Autenticación y Sesiones:** El sistema de autenticación (`AuthController`) y el middleware de protección (`AuthMiddleware`) están totalmente operativos. Manejan control de acceso mediante variables de sesión `$_SESSION['user']`, con redirecciones según el rol asignado (`/admin` vs `/dashboard`).
* **Renderizado Condicional en UI:** La plantilla maestra (`src/Views/layouts/app.php`) implementa renderizado condicional en el menú lateral adaptando las opciones visibles según los permisos del rol del usuario activo.
* **Módulo de Documentos Oficiales:** Completado al 100% (CRUD completo). Soporta la gestión y almacenamiento físico de archivos PDF en disco, integrando el flujo de carga, actualización con eliminación del archivo previo y borrado físico.

---

## 4. Tarea Inmediata a Desarrollar

* **Módulo En Cuestión:** Continuar con la **Gestión Académica (Plantel Docente)**.
* **Estructura de la Tabla MySQL (`academico`):**
  * `id_acad` (INT UNSIGNED, PK, AUTO_INCREMENT)
  * `nom_acad` (VARCHAR(300), NOT NULL)
  * `foto_acad` (VARCHAR(255), NOT NULL, guarda el nombre único generado)
  * `cel_acad` (VARCHAR(20), NOT NULL)
  * `email_acad` (VARCHAR(150), NOT NULL, UNIQUE)
  * `cat_acad` (TINYINT(3) UNSIGNED, FK -> `categoria_academico.cat_id`)
  * `desc_acad` (LONGTEXT, NOT NULL)
  * `usu_id` (INT(10) UNSIGNED, NULLABLE, FK -> `usuario.usu_id`)
* **Objetivo de Desarrollo:**
  1. Construir el Modelo (`src/Models/Academic.php`), Controlador (`src/Controllers/AcademicController.php`) y Vistas (`src/Views/academic/`).
  2. Implementar los métodos de soporte en `FileUploader.php` (`uploadImage` y `deleteImage`) para validar imágenes (JPG, PNG, WebP), generar nombres únicos seguros (tipo UUID) y guardarlas físicamente en el directorio `public/uploads/images/academics/`.