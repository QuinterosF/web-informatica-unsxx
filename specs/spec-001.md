# Spec 001: Rediseño, Normalización y Seguridad de la Base de Datos

## 1. Contexto y Objetivos
La base de datos actual (`informaticabd`) presenta vulnerabilidades críticas documentadas en la Fase 0: almacenamiento de contraseñas en texto plano, falta de integridad referencial (claves foráneas ausentes) y un esquema de permisos hardcodeado en la tabla de usuarios que impide la escalabilidad.
El objetivo de esta especificación es rediseñar el esquema relacional para un entorno MariaDB/MySQL (CPanel), garantizando normalización hasta la 3NF y preparando el terreno para un backend PHP seguro.

## 2. Requisitos Funcionales (RF)

*   **RF1 - Normalización de Autenticación:** La tabla `usuario` debe modificar su campo `usu_pass` para soportar hashes bcrypt/argon2 (mínimo VARCHAR 255).
*   **RF2 - Sistema de Roles (RBAC):** Se deben eliminar las columnas booleanas de permisos (`acerca_de`, `carrera`, `comunicados`, etc.) de la tabla `usuario`[cite: 1]. Se debe implementar un modelo relacional de Roles: tabla `roles` (ej. admin, docente, editor) y una tabla intermedia `usuario_rol` para asignar permisos.
*   **RF3 - Integridad Referencial Estricta:** Todas las relaciones lógicas deben convertirse en restricciones físicas de base de datos (`FOREIGN KEY`).
    *   Ejemplo: La tabla `fotos` debe tener una clave foránea `id_ext` que referencie a `extension(id_ext)` con reglas `ON DELETE CASCADE` o `RESTRICT`[cite: 1].
    *   Ejemplo: La tabla `laboratorios` debe relacionarse formalmente con el usuario responsable (`resp_lab` referenciando a `usuario(usu_id)`)[cite: 1].
*   **RF4 - Estandarización de Archivos:** Las columnas que almacenan nombres de archivos (`foto_acad`, `doc_com`, `horario_lab`) deben soportar nombres generados por UUID (VARCHAR 255) en lugar de cadenas de texto con espacios o caracteres especiales[cite: 1].

## 3. Requisitos No Funcionales (RNF) y Casos Límite
*   **Compatibilidad:** El script SQL final debe ser 100% compatible con MariaDB 10.6+ (motor InnoDB por defecto para soporte de transacciones y foreign keys).
*   **Codificación:** Todas las tablas deben usar explícitamente `utf8mb4` y `utf8mb4_unicode_ci` para soportar caracteres especiales sin truncamientos.
*   **Caso Límite:** Si se elimina un registro principal (ej. un comunicado), la base de datos debe impedir la eliminación si existen archivos adjuntos sin procesar, o debe eliminar en cascada dependiendo de la regla de negocio definida en el plan técnico.

## 4. Criterios de Finalización (Definition of Done)
1.  Un agente IA ha consumido esta Spec y generado un archivo `database_v2.sql`.
2.  El archivo SQL incluye las instrucciones `CREATE TABLE` con todas las `PRIMARY KEY` y `FOREIGN KEY` explícitas.
3.  El script se puede importar en phpMyAdmin (CPanel) sin arrojar errores de sintaxis o de restricciones.