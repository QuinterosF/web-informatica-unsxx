# Tasks: Implementación Base de Datos v2 (tasks-001.md)

> Orden estricto de dependencia. Cada tarea desbloquea la siguiente.

---

## Fase 1: Esquema y Catálogos (Fundación)

- [x] **T1.1** Crear `database_v2.sql` con `CREATE TABLE` de tablas base sin FKs  
  **RF**: RF1, RF2, RF3, RF4 | **Archivo**: `database_v2.sql`  
  **Éxito**: `mysql -u root -p < database_v2.sql` crea 13 tablas vacías en BD de prueba sin errores; `SHOW TABLES` lista: `usuario`, `rol`, `usuario_rol`, `categoria_academico`, `academico`, `laboratorio`, `extension`, `foto_extension`, `comunicado`, `admision`, `celular`, `logro`, `config_inicio`.

- [x] **T1.2** Añadir `PRIMARY KEY`, `AUTO_INCREMENT`, `UNIQUE`, `DEFAULT`, `CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci` a cada tabla en `database_v2.sql`  
  **RF**: RNF (Compatibilidad, Codificación) | **Archivo**: `database_v2.sql` (actualizado)  
  **Éxito**: `SHOW CREATE TABLE usuario` muestra `ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`; `usu_id` es `INT UNSIGNED AUTO_INCREMENT PRIMARY KEY`; `usu_correo` tiene `UNIQUE`.

- [x] **T1.3** Añadir `FOREIGN KEY` en orden topológico (padres antes que hijos) en `database_v2.sql`  
  **RF**: RF3 | **Archivo**: `database_v2.sql` (actualizado)  
  **Éxito**: `SET FOREIGN_KEY_CHECKS=1;` + importación exitosa; `SELECT * FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA='test_bd'` devuelve 11 filas (una por FK de la tabla de mapeo del plan).

- [x] **T1.4** Insertar datos semilla en catálogos `rol` y `categoria_academico` dentro de `database_v2.sql`  
  **RF**: RF2 | **Archivo**: `database_v2.sql` (actualizado)  
  **Éxito**: `SELECT rol_nombre FROM rol` devuelve 4 filas (`admin`, `docente`, `editor`, `coordinador`); `SELECT cat_nombre FROM categoria_academico` devuelve 6 filas con IDs 1–6.

---

## Fase 2: Scripts de Migración de Datos

- [ ] **T2.1** Crear `migracion_datos.sql` con `INSERT INTO ... SELECT` para `usuario` (hash bcrypt, sin columnas permisos)  
  **RF**: RF1 | **Archivo**: `migracion_datos.sql`  
  **Éxito**: Ejecutado sobre BD con tablas v2 vacías + datos originales → `SELECT usu_pass FROM v2_usuario WHERE usu_id=1` devuelve hash `$2y$10$...` (bcrypt); columnas `acerca_de`..`usuarios` **no existen** en `v2_usuario`.

- [ ] **T2.2** Añadir a `migracion_datos.sql` poblamiento de `usuario_rol` (admin → rol_id=1)  
  **RF**: RF2 | **Archivo**: `migracion_datos.sql` (actualizado)  
  **Éxito**: `SELECT * FROM v2_usuario_rol` devuelve 1 fila: `usu_id=1, rol_id=1`.

- [ ] **T2.3** Añadir a `migracion_datos.sql` migración `academicos` → `academico` (UUID placeholder en `foto_acad`, CAST `cel_acad`, FK `cat_acad`)  
  **RF**: RF3, RF4 | **Archivo**: `migracion_datos.sql` (actualizado)  
  **Éxito**: `SELECT COUNT(*) FROM v2_academico` = 19 (igual que origen); `foto_acad` valores son UUIDs válidos (regex `^[0-9a-f-]{36}$`); `cat_acad` todos existen en `categoria_academico`.

- [ ] **T2.4** Añadir a `migracion_datos.sql` migración `laboratorios` → `laboratorio` (6 filas fijas, `resp_acad_id` mapeado, `horario_lab` UUID placeholder)  
  **RF**: RF3, RF4 | **Archivo**: `migracion_datos.sql` (actualizado)  
  **Éxito**: `SELECT COUNT(*) FROM v2_laboratorio` = 6; `nombre_lab` valores explícitos; `resp_acad_id` apuntan a `academico.id_acad` válidos.

- [ ] **T2.5** Añadir a `migracion_datos.sql` migración `extension` + `fotos` → `extension` + `foto_extension` (FK CASCADE, UUID placeholders)  
  **RF**: RF3, RF4 | **Archivo**: `migracion_datos.sql` (actualizado)  
  **Éxito**: Conteos coinciden; `SELECT * FROM v2_foto_extension fe JOIN v2_extension e ON fe.id_ext=e.id_ext` no devuelve huérfanos.

- [ ] **T2.6** Añadir a `migracion_datos.sql` migración `comunicados` → `comunicado` (VARCHAR(300), UUID placeholders, `publicado_por=1`)  
  **RF**: RF3, RF4 | **Archivo**: `migracion_datos.sql` (actualizado)  
  **Éxito**: `SELECT COUNT(*) FROM v2_comunicado` = 4 (o filas origen); `nom_com` longitud ≤ 300; `doc_com`/`foto_com` son UUIDs.

- [ ] **T2.7** Añadir a `migracion_datos.sql` migración `admision` (split por `tipo`, UUID placeholder)  
  **RF**: RF4 | **Archivo**: `migracion_datos.sql` (actualizado)  
  **Éxito**: `SELECT tipo, COUNT(*) FROM v2_admision GROUP BY tipo` devuelve 2 filas (`prueba`, `preuni`) con conteos correctos.

- [ ] **T2.8** Añadir a `migracion_datos.sql` migración `celulares` → `celular` y `logros` → `logro` (auditoría `registrado_por=1`, `fech_log` a DATE)  
  **RF**: RF3 | **Archivo**: `migracion_datos.sql` (actualizado)  
  **Éxito**: Conteos idénticos a origen; `fech_log` es tipo `DATE` válido; columnas auditoría pobladas con 1.

- [ ] **T2.9** Añadir a `migracion_datos.sql` migración `inicio` → `config_inicio` (singleton id_inc=1, UUID placeholder `afiche`)  
  **RF**: RF4 | **Archivo**: `migracion_datos.sql` (actualizado)  
  **Éxito**: `SELECT * FROM v2_config_inicio` devuelve 1 fila con `id_inc=1`; `afiche` es UUID válido.

---

## Fase 3: Renovación de Archivos Físicos (UUID)

- [ ] **T3.1** Crear `rename_files.php` (CLI) que recorre `images/`, `docs/horarios/`, `docs/comunicados/`, `images/perfil_academico/`, `images/extension/` y renombra cada archivo referenciado en BD v2 a UUIDv4, actualizando la columna correspondiente  
  **RF**: RF4 | **Archivo**: `rename_files.php`  
  **Éxito**: Ejecución sin errores; `SELECT foto_acad FROM v2_academico` → todos los archivos existen en disco con nombre UUID; tabla temporal `tmp_file_map(old_name, new_uuid)` creada con mapeo completo para rollback.

- [ ] **T3.2** Verificar que no quedan archivos referenciados en BD v2 con nombre original (espacios, acentos)  
  **RF**: RF4 | **Archivo**: (validación)  
  **Éxito**: `SELECT COUNT(*) FROM v2_academico WHERE foto_acad LIKE '% %' OR foto_acad LIKE '%.jpg' OR foto_acad LIKE '%.png'` devuelve 0; idem para `horario_lab`, `doc_com`, `foto_com`, `nom_foto`, `img_adm`, `afiche`.

---

## Fase 4: Validación y Cutover

- [ ] **T4.1** Crear `check_migracion.sql` con queries de validación (conteos, FKs, hashes, tipos)  
  **RF**: RNF, DoD | **Archivo**: `check_migracion.sql`  
  **Éxito**: Ejecutado sobre BD v2 poblada → todas las queries devuelven "OK" (0 errores, conteos coinciden, FKs válidas, hashes bcrypt, UUIDs válidos).

- [ ] **T4.2** Ejecutar `check_migracion.sql` en entorno de staging y confirmar 0 errores  
  **RF**: DoD #3 | **Archivo**: (ejecución)  
  **Éxito**: Salida limpia; `FOREIGN_KEY_CHECKS=1` no arroja violaciones.

- [ ] **T4.3** Generar script de cutover `cutover.sql` (`RENAME TABLE ...` atómico en transacción)  
  **RF**: DoD | **Archivo**: `cutover.sql`  
  **Éxito**: Script listo; prueba en staging: `START TRANSACTION; RENAME TABLE ...; COMMIT;` completa en <1s; tablas antiguas con sufijo `_old`, nuevas sin prefijo.

- [ ] **T4.4** Documentar procedimiento de rollback (restaurar `_old`, revertir archivos con `tmp_file_map`) en `ROLLBACK.md`  
  **RF**: RNF (Caso Límite) | **Archivo**: `ROLLBACK.md`  
  **Éxito**: Documento existe con pasos claros; simulación de rollback en staging recupera estado original en <5 min.

---

## Fase 5: Limpieza Post-Cutover

- [ ] **T5.1** Ejecutar script de detección de archivos huérfanos y mover a `_orphaned/`  
  **RF**: RF4 | **Archivo**: `cleanup_orphans.php`  
  **Éxito**: Carpeta `_orphaned/` creada; archivos no referenciados en BD final movidos allí; BD final no tiene referencias rotas.

- [ ] **T5.2** Eliminar tablas `_old` y tabla temporal `tmp_file_map` tras ventana de estabilidad (48h)  
  **RF**: — | **Archivo**: (operación manual documentada)  
  **Éxito**: `SHOW TABLES LIKE '%_old%'` y `SHOW TABLES LIKE 'tmp_file_map'` devuelven vacío.

---

## Resumen de Entregables Finales

| Archivo | Generado en Tarea | Descripción |
|---------|-------------------|-------------|
| `database_v2.sql` | T1.1–T1.4 | Esquema completo (DDL + catálogos) |
| `migracion_datos.sql` | T2.1–T2.9 | DML de migración con transformaciones |
| `rename_files.php` | T3.1 | Renombrado físico a UUID + sync BD |
| `check_migracion.sql` | T4.1 | Validación automatizada |
| `cutover.sql` | T4.3 | Switch atómico producción |
| `ROLLBACK.md` | T4.4 | Procedimiento de reversión |
| `cleanup_orphans.php` | T5.1 | Limpieza archivos huérfanos |

---

**Nota**: Las tareas T1.x deben completarse **antes** de T2.x; T2.x antes de T3.1; T3.1 antes de T4.1; T4.x antes de T5.x. No paralelizar entre fases.