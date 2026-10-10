# Spec 002: Arquitectura MVC Base y Autenticación Segura

## 1. Contexto y Objetivos
Con la base de datos normalizada (`informaticabd_v2`), el backend PHP necesita una estructura moderna. El código espagueti actual expone múltiples puntos de entrada (ej. `admin.php`, `lab_*.php`). El objetivo es implementar un Front Controller (Punto de Entrada Único) ligero, un enrutador básico, y un sistema de autenticación blindado contra ataques de fuerza bruta y fijación de sesión.

## 2. Requisitos Funcionales (RF)
*   **RF1 - Front Controller:** Todas las peticiones deben pasar por un único archivo `index.php` en la raíz. Este archivo enrutará el tráfico a controladores específicos (ej. `AuthController`, `HomeController`).
*   **RF2 - Conexión a BD (Segura):** Se debe crear una clase `Database` usando patrón Singleton con PDO. Las credenciales deben cargarse desde un archivo `.env` o un archivo de configuración excluido de control de versiones.
*   **RF3 - Autenticación Segura:** El controlador de Login debe usar `password_verify()` contra la tabla `usuario`.
*   **RF4 - Manejo de Sesiones:** Al iniciar sesión exitosamente, se debe llamar a `session_regenerate_id(true)` para prevenir Session Fixation. La sesión debe almacenar el `usu_id` y el `rol_id` del usuario.
*   **RF5 - UI de Login:** Crear una vista de login usando Tailwind CSS puro, con diseño moderno y minimalista, mostrando mensajes de error claros ("Credenciales inválidas") sin revelar si el usuario existe.

## 3. Requisitos No Funcionales (RNF)
*   Compatibilidad estricta con PHP 7.4+ en CPanel.
*   Prohibido el uso de librerías externas pesadas (como Laravel o Symfony components) para mantener el despliegue simple vía FTP.

## 4. Criterios de Finalización (DoD)
1. Un agente IA generó el plan técnico `plan-002.md`.
2. Se implementó la estructura de carpetas: `/config`, `/controllers`, `/views`.
3. Un usuario puede iniciar sesión correctamente y es redirigido a un dashboard en blanco si el hash bcrypt coincide.