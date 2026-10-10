# Plan Técnico: Rediseño y Normalización de Base de Datos (plan-001.md)

---

## 1. Modelo Relacional Objetivo

Todas las tablas usarán **InnoDB**, `utf8mb4` / `utf8mb4_unicode_ci`, `AUTO_INCREMENT` en PKs.

| Tabla | Columnas Principales | Tipo MariaDB | PK | Notas |
|-------|---------------------|--------------|----|-------|
| **`usuario`** | `usu_id` | `INT UNSIGNED` | ✅ | |
| | `usu_nom` | `VARCHAR(150)` | | NOT NULL |
| | `usu_correo` | `VARCHAR(150)` | | UNIQUE, NOT NULL |
| | `usu_pass` | `VARCHAR(255)` | | **RF1**: bcrypt/argon2 hash |
| | `estado` | `TINYINT(1)` | | DEFAULT 1 (activo) |
| | `creado_en` | `TIMESTAMP` | | DEFAULT CURRENT_TIMESTAMP |
| | `actualizado_en` | `TIMESTAMP` | | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |
| **`rol`** | `rol_id` | `TINYINT UNSIGNED` | ✅ | |
| | `rol_nombre` | `VARCHAR(50)` | | UNIQUE, NOT NULL (ej: 'admin', 'docente', 'editor', 'coordinador') |
| | `rol_descripcion` | `VARCHAR(255)` | | |
| **`usuario_rol`** | `usu_id` | `INT UNSIGNED` | ✅ (PK compuesta) | **RF2**: Tabla intermedia RBAC |
| | `rol_id` | `TINYINT UNSIGNED` | ✅ (PK compuesta) | |
| | `asignado_en` | `TIMESTAMP` | | DEFAULT CURRENT_TIMESTAMP |
| **`academico`** | `id_acad` | `INT UNSIGNED` | ✅ | Renombrado de `academicos` (singular, convención) |
| | `nom_acad` | `VARCHAR(300)` | | NOT NULL |
| | `foto_acad` | `VARCHAR(255)` | | **RF4**: UUID filename |
| | `cel_acad` | `VARCHAR(20)` | | Cambiado de INT: permite +591, guiones, espacios |
| | `email_acad` | `VARCHAR(150)` | | UNIQUE |
| | `cat_acad` | `TINYINT UNSIGNED` | | FK → `categoria_academico.cat_id` (ver abajo) |
| | `desc_acad` | `LONGTEXT` | | |
| | `usu_id` | `INT UNSIGNED` | | **FK opcional** → `usuario.usu_id` (si el académico tiene acceso al sistema) |
| **`categoria_academico`** | `cat_id` | `TINYINT UNSIGNED` | ✅ | **Nueva**: normaliza `cat_acad` (1=Director, 2=Titular, 3=Invitado, 4=Coord. Móvil, 5=Coord. Seguridad, 6=IIDAI) |
| | `cat_nombre` | `VARCHAR(100)` | | UNIQUE |
| **`laboratorio`** | `id_lab` | `INT UNSIGNED` | ✅ | Singular |
| | `nombre_lab` | `VARCHAR(100)` | | NOT NULL (ej: 'Seguridad Informática', 'Computación Móvil', 'Hardware', 'Software I', 'Software II', 'Software III') |
| | `resp_usu_id` | `INT UNSIGNED` | | **RF3**: FK → `usuario.usu_id` (responsable con acceso al sistema) |
| | `resp_acad_id` | `INT UNSIGNED` | | FK → `academico.id_acad` (responsable académico, puede no tener usuario) |
| | `horario_lab` | `VARCHAR(255)` | | **RF4**: UUID filename |
| | `activo` | `TINYINT(1)` | | DEFAULT 1 |
| **`extension`** | `id_ext` | `INT UNSIGNED` | ✅ | |
| | `nom_ext` | `VARCHAR(500)` | | NOT NULL |
| | `fech_ext` | `DATE` | | NOT NULL |
| | `desc_ext` | `LONGTEXT` | | |
| | `creado_por` | `INT UNSIGNED` | | FK → `usuario.usu_id` |
| **`foto_extension`** | `id_foto` | `INT UNSIGNED` | ✅ | Renombrado de `fotos` |
| | `nom_foto` | `VARCHAR(255)` | | **RF4**: UUID filename |
| | `id_ext` | `INT UNSIGNED` | | **RF3**: FK → `extension.id_ext` ON DELETE CASCADE |
| | `subido_por` | `INT UNSIGNED` | | FK → `usuario.usu_id` |
| | `subido_en` | `TIMESTAMP` | | DEFAULT CURRENT_TIMESTAMP |
| **`comunicado`** | `id_com` | `INT UNSIGNED` | ✅ | Singular |
| | `nom_com` | `VARCHAR(300)` | | NOT NULL (era LONGTEXT, normalizado) |
| | `desc_com` | `LONGTEXT` | | |
| | `doc_com` | `VARCHAR(255)` | | **RF4**: UUID filename (PDF) |
| | `foto_com` | `VARCHAR(255)` | | **RF4**: UUID filename (imagen) |
| | `fech_com` | `DATE` | | NOT NULL |
| | `publicado_por` | `INT UNSIGNED` | | FK → `usuario.usu_id` |
| | `estado` | `TINYINT(1)` | | DEFAULT 1 (publicado/borrador) |
| **`admision`** | `id_adm` | `INT UNSIGNED` | ✅ | |
| | `proc_adm` | `LONGTEXT` | | NOT NULL |
| | `req_adm` | `LONGTEXT` | | NOT NULL |
| | `img_adm` | `VARCHAR(255)` | | **RF4**: UUID filename |
| | `tipo` | `ENUM('prueba','preuni')` | | NOT NULL (diferencia prueba vs preuniversitario) |
| | `actualizado_por` | `INT UNSIGNED` | | FK → `usuario.usu_id` |
| **`celular`** | `id_cel` | `INT UNSIGNED` | ✅ | Singular |
| | `nom_cel` | `VARCHAR(150)` | | NOT NULL |
| | `num_cel` | `VARCHAR(20)` | | NOT NULL (VARCHAR para formatos) |
| **`logro`** | `id_log` | `INT UNSIGNED` | ✅ | Singular |
| | `nom_log` | `VARCHAR(300)` | | NOT NULL |
| | `lugar_log` | `VARCHAR(150)` | | |
| | `fech_log` | `DATE` | | **Corregido**: era VARCHAR(50) |
| | `registrado_por` | `INT UNSIGNED` | | FK → `usuario.usu_id` |
| **`config_inicio`** | `id_inc` | `TINYINT UNSIGNED` | ✅ | Singleton (siempre 1) |
| | `afiche` | `VARCHAR(255)` | | **RF4**: UUID filename |
| | `presentacion` | `LONGTEXT` | | |
| | `perfil` | `LONGTEXT` | | |
| | `mision` | `LONGTEXT` | | |
| | `vision` | `LONGTEXT` | | |
| | `objetivo` | `LONGTEXT` | | |
| | `historia` | `LONGTEXT` | | |
| | `actualizado_por` | `INT UNSIGNED` | | FK → `usuario.usu_id` |
| | `actualizado_en` | `TIMESTAMP` | | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP |

---

## 2. Mapeo de Claves Foráneas (Integridad Referencial)

| Tabla Hija | Columna FK | Tabla Padre | Columna Padre | Acción DELETE | Acción UPDATE | Justificación |
|------------|------------|-------------|---------------|---------------|---------------|---------------|
| `usuario_rol` | `usu_id` | `usuario` | `usu_id` | **CASCADE** | CASCADE | Si se borra usuario, borrar sus roles |
| `usuario_rol` | `rol_id` | `rol` | `rol_id` | **RESTRICT** | CASCADE | No borrar rol si tiene usuarios asignados |
| `academico` | `cat_acad` | `categoria_academico` | `cat_id` | **RESTRICT** | CASCADE | Categoría es catálogo maestro |
| `academico` | `usu_id` | `usuario` | `usu_id` | **SET NULL** | CASCADE | Académico puede existir sin usuario sistema |
| `laboratorio` | `resp_usu_id` | `usuario` | `usu_id` | **SET NULL** | CASCADE | **RF3**: Responsable con usuario sistema |
| `laboratorio` | `resp_acad_id` | `academico` | `id_acad` | **SET NULL** | CASCADE | Responsable académico (puede no tener usuario) |
| `extension` | `creado_por` | `usuario` | `usu_id` | **SET NULL** | CASCADE | Auditoría |
| `foto_extension` | `id_ext` | `extension` | `id_ext` | **CASCADE** | CASCADE | **RF3**: Fotos dependen de extensión |
| `foto_extension` | `subido_por` | `usuario` | `usu_id` | **SET NULL** | CASCADE | Auditoría |
| `comunicado` | `publicado_por` | `usuario` | `usu_id` | **SET NULL** | CASCADE | Auditoría |
| `admision` | `actualizado_por` | `usuario` | `usu_id` | **SET NULL** | CASCADE | Auditoría |
| `logro` | `registrado_por` | `usuario` | `usu_id` | **SET NULL** | CASCADE | Auditoría |
| `config_inicio` | `actualizado_por` | `usuario` | `usu_id` | **SET NULL** | CASCADE | Auditoría |

> **Nota**: `ON DELETE RESTRICT` en catálogos (`rol`, `categoria_academico`) evita borrar referencias maestras. `CASCADE` solo donde la entidad hija no tiene sentido sin la padre (`usuario_rol`, `foto_extension`).

---

## 3. Migración de Datos: Estrategia

| Paso | Acción | Detalle |
|------|--------|---------|
| **1. Backup** | `mysqldump` completo | Antes de cualquier cambio, backup de `informaticabd-35303235cca3` |
| **2. Esquema nuevo** | Ejecutar `database_v2.sql` | Crea tablas nuevas **vacías** junto a las actuales (prefijo `v2_` o BD aparte) |
| **3. Catálogos** | Poblar `rol`, `categoria_academico` | `INSERT` fijo: roles (admin, docente, editor, coordinador), categorías (1-6 según datos actuales) |
| **4. Usuarios** | Migrar `usuario` → `v2_usuario` | - `usu_pass`: re-hashear con `password_hash('S@lud4dejulio', PASSWORD_BCRYPT)`<br>- `estado`: mantener<br>- **Eliminar columnas permisos** (`acerca_de`...`usuarios`) |
| **5. RBAC** | Poblar `usuario_rol` | Mapear usuario actual (id=1, admin total) → `rol_id=1` (admin). Futuros usuarios se asignan vía UI. |
| **6. Académicos** | Migrar `academicos` → `academico` | - `foto_acad`: renombrar archivos físicos a UUID, actualizar BD<br>- `cel_acad`: formatear a string<br>- `cat_acad`: validar existe en `categoria_academico`<br>- `usu_id`: NULL por defecto (vincular luego si aplica) |
| **7. Laboratorios** | Migrar `laboratorios` → `laboratorio` | - Separar en 6 filas fijas (uno por lab) con `nombre_lab` explícito<br>- `resp_lab` actual (10,11,12,14) → mapear a `academico.id_acad` → `resp_acad_id`<br>- `resp_usu_id`: NULL (asignar luego si el responsable tiene usuario) |
| **8. Extensión + Fotos** | Migrar `extension` + `fotos` | - `extension` → `extension` (añadir `creado_por` = 1)<br>- `fotos` → `foto_extension` con `ON DELETE CASCADE`, renombrar archivos a UUID |
| **9. Comunicados** | Migrar `comunicados` → `comunicado` | - `nom_com`: recortar a VARCHAR(300)<br>- `doc_com`/`foto_com`: renombrar a UUID<br>- `publicado_por` = 1 |
| **10. Admisión** | Migrar `admision` → `admision` | - Separar en dos filas (`tipo='prueba'`, `tipo='preuni'`) según lógica actual<br>- `img_adm`: UUID |
| **11. Celulares/Logros** | Migrar directos | Añadir columnas auditoría (`registrado_por` = 1) |
| **12. Config Inicio** | Migrar `inicio` → `config_inicio` | Singleton `id_inc=1`, `actualizado_por` = 1 |
| **13. Validación** | Checks post-migración | - `SELECT COUNT(*)` comparar filas origen vs destino<br>- `FOREIGN KEY` checks: `SET FOREIGN_KEY_CHECKS=1;`<br>- Verificar hashes: `SELECT usu_pass FROM v2_usuario WHERE usu_id=1;` |
| **14. Cutover** | Renombrar tablas / Switch | En ventana de mantenimiento: `RENAME TABLE usuario TO usuario_old, v2_usuario TO usuario;` (y así para cada tabla) |
| **15. Limpieza** | Archivos huérfanos | Script para detectar archivos en `images/`, `docs/horarios/`, `docs/comunicados/` no referenciados en BD nueva → mover a `_orphaned/` |

### Datos Vulnerables - Tratamiento Específico

| Dato | Riesgo | Acción |
|------|--------|--------|
| `usuario.usu_pass` en texto plano | **Crítico** | Re-hashear **todos** con `password_hash()` durante migración. No conservar original. |
| Nombres de archivo con espacios/acentos | Medio | Renombrar a UUIDv4 en filesystem + actualizar BD. Mantener mapeo `old_name → new_uuid` en tabla temporal para rollback. |
| `laboratorios.resp_lab` apunta a `academicos.id_acad` | Inconsistencia | Migrar a `laboratorio.resp_acad_id` (FK real). Si el académico tiene usuario sistema, poblar también `resp_usu_id`. |
| `infor_unsxx.sql` usa `latin1` DB default | Encoding | Nueva BD/tabla explícita `utf8mb4_unicode_ci`. Datos actuales ya están en `utf8mb4` por tabla (OK). |

---

## 4. Entregables del Plan

1. **`database_v2.sql`** — Script único `CREATE TABLE` + `FOREIGN KEY` + `INSERT` catálogos + `ALTER TABLE` para FKs (orden topológico).
2. **`migracion_datos.sql`** — Scripts `INSERT INTO ... SELECT ...` con transformaciones (hash, UUID, CAST).
3. **`rename_files.php`** — Script CLI para renombrar archivos físicos a UUID y actualizar BD.
4. **`check_migracion.sql`** — Queries de validación post-migración (conteos, FKs, hashes).