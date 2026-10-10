# Constitución del Proyecto: Refactorización Web Informática UNSXX

Este documento establece las reglas y principios innegociables para el proceso de refactorización de la plataforma web en entorno CPanel. Toda especificación, plan técnico y tarea deberá cumplir obligatoriamente con estos criterios.

1. Stack Tecnológico Objetivo
- Backend: PHP (MVC o API ligera, sin frameworks pesados incompatibles con hosting compartido).
- Base de Datos: MySQL / MariaDB vía PDO.
- Frontend: HTML5, JavaScript modular.
- Estilos: Tailwind CSS.

2. Reglas Estrictas de Seguridad (Tolerancia Cero)
- Base de Datos: Uso obligatorio de Prepared Statements (PDO). Prohibida la concatenación SQL.
- Contraseñas: Uso exclusivo de `password_hash()` (bcrypt) y `password_verify()`. Prohibido el texto plano.
- Archivos: Validación estricta de MIME types. Los archivos subidos deben renombrarse con UUIDs.
- Salidas: Sanitización obligatoria con `htmlspecialchars` para evitar XSS.
- Autenticación: Manejo estricto de `$_SESSION` con validación de roles en cada endpoint.

3. Arquitectura y Base de Datos
- Normalización: La base de datos debe estar normalizada (3NF) con claves foráneas (FOREIGN KEY) estrictas.
- Componentización: Evitar redundancia en vistas (usar partials/includes).