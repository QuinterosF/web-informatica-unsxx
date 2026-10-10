-- ============================================================================
-- migracion_datos.sql — Migración de datos legacy → esquema v2
-- Compatible: MariaDB 10.6+ / MySQL 8.0+
-- Requiere: database_v2.sql ya importado (tablas v2 vacías + catálogos poblados)
-- Origen: tablas legacy en la misma BD (sin prefijo) o BD legacy adjunta
-- Generado según: tasks-001.md (T2.1–T2.9)
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET FOREIGN_KEY_CHECKS = 0;  -- Se reactiva al final

-- ----------------------------------------------------------------------------
-- NOTA CRÍTICA SOBRE PASSWORD HASH (RF1)
-- ----------------------------------------------------------------------------
-- MariaDB/MySQL NO tienen función nativa BCRYPT en SQL puro.
-- La contraseña 'S@lud4dejulio' DEBE hashearse en capa de aplicación (PHP):
--   $hash = password_hash('S@lud4dejulio', PASSWORD_BCRYPT);
--   -- Resultado ejemplo: $2y$10$9x... (60 chars)
--
-- EN ESTE SCRIPT se inserta un PLACEHOLDER seguro.
-- EJECUTAR INMEDIATAMENTE DESPUÉS DE LA MIGRACIÓN (en PHP o CLI):
--   UPDATE `usuario` SET `usu_pass` = '$2y$10$...hash_real...' WHERE `usu_id` = 1;
--   O vía PHP:  password_hash('S@lud4dejulio', PASSWORD_BCRYPT);
-- ----------------------------------------------------------------------------

-- ----------------------------------------------------------------------------
-- T2.1 — Migración: usuario (RF1)
-- Elimina columnas permisos legacy (acerca_de, carrera, comunicados, laboratorios, docentes, extension, usuarios)
-- Hashea password con placeholder; se debe reemplazar con hash real bcrypt post-migración
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `usuario` (
    `usu_id`, `usu_nom`, `usu_correo`, `usu_pass`, `estado`, `creado_en`, `actualizado_en`
)
SELECT
    `usu_id`,
    `usu_nom`,
    `usu_correo`,
    -- PLACEHOLDER: Reemplazar con hash bcrypt real via PHP password_hash()
    -- Ejemplo hash válido: '$2y$10$abcdefghijklmnopqrstuvwxyz1234567890abcd'
    '$2y$10$REPLACE_WITH_REAL_BCRYPT_HASH_VIA_PHP_PASSWORD_HASH',
    `estado`,
    CURRENT_TIMESTAMP,
    CURRENT_TIMESTAMP
FROM `informaticabd`.`usuario`  -- Tabla legacy (sin prefijo)
WHERE `usu_id` = 1;  -- Solo el admin actual; futuros usuarios via registro

-- ----------------------------------------------------------------------------
-- T2.2 — Poblamiento: usuario_rol (RF2)
-- Admin (usu_id=1) → rol_id=1 (admin)
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `usuario_rol` (`usu_id`, `rol_id`, `asignado_en`)
VALUES (1, 1, CURRENT_TIMESTAMP);

-- ----------------------------------------------------------------------------
-- T2.3 — Migración: academicos → academico (RF3, RF4)
-- - UUID placeholder para foto_acad (formato: xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx)
-- - CAST cel_acad de INT a VARCHAR(20)
-- - cat_acad ya coincide con categoria_academico.cat_id (1-6)
-- - usu_id = NULL (vinculación posterior si el académico tiene usuario sistema)
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `academico` (
    `id_acad`, `nom_acad`, `foto_acad`, `cel_acad`, `email_acad`, `cat_acad`, `desc_acad`, `usu_id`
)
SELECT
    `id_acad`,
    `nom_acad`,
    -- UUID v4 placeholder por cada fila (generado determinísticamente para reproducibilidad)
    -- En producción, reemplazar con UUID real generado al renombrar archivo físico (ver rename_files.php)
    CONCAT(
        LPAD(HEX(`id_acad`), 8, '0'), '-',
        LPAD(HEX(`cat_acad`), 4, '0'), '-4',
        LPAD(HEX(`id_acad` * 7 % 0x1000), 3, '0'), '-',
        LPAD(HEX((`id_acad` * 13) % 0x4000 + 0x8000), 4, '0'), '-',
        LPAD(HEX((`id_acad` * 17) % 0x100000000), 12, '0')
    ),
    CAST(`cel_acad` AS CHAR(20)),
    `email_acad`,
    `cat_acad`,
    `desc_acad`,
    NULL
FROM `informaticabd`.`academicos`;  -- Tabla legacy

-- ----------------------------------------------------------------------------
-- T2.4 — Migración: laboratorios → laboratorio (RF3, RF4)
-- 6 laboratorios fijos con nombres canónicos.
-- Mapeo resp_lab (legacy academicos.id_acad) → resp_acad_id
-- resp_usu_id = NULL (asignar luego si el responsable tiene usuario sistema)
-- horario_lab: UUID placeholder
-- ----------------------------------------------------------------------------
-- Primero, insertar los 6 laboratorios canónicos
INSERT IGNORE INTO `laboratorio` (`id_lab`, `nombre_lab`, `resp_acad_id`, `horario_lab`, `activo`)
VALUES
(1, 'Seguridad Informática',     10, CONCAT('lab-horario-seg-', LPAD(HEX(1), 12, '0')), 1),
(2, 'Computación Móvil',         11, CONCAT('lab-horario-mov-', LPAD(HEX(2), 12, '0')), 1),
(3, 'Hardware',                  14, CONCAT('lab-horario-hd-',  LPAD(HEX(3), 12, '0')), 1),
(4, 'Software I',                14, CONCAT('lab-horario-swi-', LPAD(HEX(4), 12, '0')), 1),
(5, 'Software II',               12, CONCAT('lab-horario-swii-', LPAD(HEX(5), 12, '0')), 1),
(6, 'Software III',              12, CONCAT('lab-horario-swiii-', LPAD(HEX(6), 12, '0')), 1);

-- Nota: Los IDs 10, 11, 12, 14 son academicos.id_acad legacy (resp_lab en tabla laboratorios original)
-- Verificar que existen en academico migrado:
-- SELECT * FROM academico WHERE id_acad IN (10,11,12,14);

-- ----------------------------------------------------------------------------
-- T2.5 — Migración: extension + fotos → extension + foto_extension (RF3, RF4)
-- extension: creado_por = 1 (admin)
-- foto_extension: FK id_ext con CASCADE, subido_por = 1, UUID placeholder nom_foto
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `extension` (`id_ext`, `nom_ext`, `fech_ext`, `desc_ext`, `creado_por`)
SELECT
    `id_ext`,
    `nom_ext`,
    `fech_ext`,
    `desc_ext`,
    1  -- admin
FROM `informaticabd`.`extension`;  -- Tabla legacy

-- Migrar fotos (tabla legacy `fotos`)
INSERT IGNORE INTO `foto_extension` (`id_foto`, `nom_foto`, `id_ext`, `subido_por`, `subido_en`)
SELECT
    `id_foto`,
    CONCAT('ext-foto-', LPAD(HEX(`id_foto`), 12, '0')),  -- UUID placeholder
    `id_ext`,
    1,  -- admin
    CURRENT_TIMESTAMP
FROM `informaticabd`.`fotos`;  -- Tabla legacy

-- ----------------------------------------------------------------------------
-- T2.6 — Migración: comunicados → comunicado (RF3, RF4)
-- nom_com: recortar a VARCHAR(300) si excede (era LONGTEXT)
-- doc_com / foto_com: UUID placeholders (archivos legacy en docs/comunicados/)
-- publicado_por = 1 (admin)
-- estado = 1 (publicado)
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `comunicado` (
    `id_com`, `nom_com`, `desc_com`, `doc_com`, `foto_com`,
    `fech_com`, `publicado_por`, `estado`
)
SELECT
    `id_com`,
    LEFT(`nom_com`, 300),  -- RF4: normalizado a VARCHAR(300)
    `desc_com`,
    CASE WHEN `doc_com` != '' AND `doc_com` IS NOT NULL
         THEN CONCAT('com-doc-', LPAD(HEX(`id_com`), 12, '0'))
         ELSE NULL END,
    CASE WHEN `foto_com` != '' AND `foto_com` IS NOT NULL
         THEN CONCAT('com-img-', LPAD(HEX(`id_com`), 12, '0'))
         ELSE NULL END,
    `fech_com`,
    1,  -- admin
    1   -- publicado
FROM `informaticabd`.`comunicados`;  -- Tabla legacy

-- ----------------------------------------------------------------------------
-- T2.7 — Migración: admision → admision (RF4)
-- Split en dos filas por tipo: 'prueba' y 'preuni'
-- La tabla legacy `admision` tiene 1 fila con proc_adm, req_adm, img_adm
-- Asumimos que esa fila corresponde a 'prueba' y duplicamos para 'preuni'
-- (ajustar según datos reales; si hay datos separados, usar INSERT ... SELECT separado)
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `admision` (`id_adm`, `proc_adm`, `req_adm`, `img_adm`, `tipo`, `actualizado_por`)
SELECT
    `id_adm`,
    `proc_adm`,
    `req_adm`,
    CASE WHEN `img_adm` != '' AND `img_adm` IS NOT NULL
         THEN CONCAT('adm-img-', LPAD(HEX(`id_adm`), 12, '0'))
         ELSE NULL END,
    'prueba',
    1
FROM `informaticabd`.`admision`;  -- Tabla legacy

-- Si existe preuniversitario separado (verificar datos), insertar segunda fila:
-- INSERT INTO `admision` (...) SELECT ..., 'preuni', 1 FROM ... WHERE ...;

-- ----------------------------------------------------------------------------
-- T2.8 — Migración: celulares → celular y logros → logro (RF3)
-- Auditoría: registrado_por = 1
-- fech_log: CAST de VARCHAR(50) a DATE (formato asumido 'YYYY-MM-DD' o 'DD/MM/YYYY')
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `celular` (`id_cel`, `nom_cel`, `num_cel`)
SELECT `id_cel`, `nom_cel`, CAST(`num_cel` AS CHAR(20))
FROM `informaticabd`.`celulares`;  -- Tabla legacy

INSERT IGNORE INTO `logro` (`id_log`, `nom_log`, `lugar_log`, `fech_log`, `registrado_por`)
SELECT
    `id_log`,
    `nom_log`,
    `lugar_log`,
    -- Conversión segura VARCHAR→DATE: intenta formato ISO, si falla NULL
    CASE
        WHEN STR_TO_DATE(`fech_log`, '%Y-%m-%d') IS NOT NULL THEN STR_TO_DATE(`fech_log`, '%Y-%m-%d')
        WHEN STR_TO_DATE(`fech_log`, '%d/%m/%Y') IS NOT NULL THEN STR_TO_DATE(`fech_log`, '%d/%m/%Y')
        ELSE NULL
    END,
    1  -- admin
FROM `informaticabd`.`logros`;  -- Tabla legacy

-- ----------------------------------------------------------------------------
-- T2.9 — Migración: inicio → config_inicio (RF4)
-- Singleton id_inc = 1
-- afiche: UUID placeholder
-- actualizado_por = 1
-- ----------------------------------------------------------------------------
INSERT IGNORE INTO `config_inicio` (
    `id_inc`, `afiche`, `presentacion`, `perfil`, `mision`,
    `vision`, `objetivo`, `historia`, `actualizado_por`, `actualizado_en`
)
SELECT
    1,  -- singleton
    CASE WHEN `afiche` != '' AND `afiche` IS NOT NULL
         THEN CONCAT('inicio-afiche-', LPAD(HEX(1), 12, '0'))
         ELSE NULL END,
    `presentacion`,
    `perfil`,
    `mision`,
    `vision`,
    `objetivo`,
    `historia`,
    1,  -- admin
    CURRENT_TIMESTAMP
FROM `informaticabd`.`inicio`  -- Tabla legacy
WHERE `id_inc` = 1
LIMIT 1;

-- ----------------------------------------------------------------------------
-- POST-MIGRACIÓN: Reactivar FK checks y verificar
-- ----------------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- VERIFICACIONES RÁPIDAS (ejecutar manualmente tras importar)
-- ============================================================================
-- 1. Conteo de filas por tabla (debe coincidir con origen):
--    SELECT 'usuario' AS t, COUNT(*) FROM usuario UNION ALL
--    SELECT 'usuario_rol', COUNT(*) FROM usuario_rol UNION ALL
--    SELECT 'academico', COUNT(*) FROM academico UNION ALL
--    SELECT 'laboratorio', COUNT(*) FROM laboratorio UNION ALL
--    SELECT 'extension', COUNT(*) FROM extension UNION ALL
--    SELECT 'foto_extension', COUNT(*) FROM foto_extension UNION ALL
--    SELECT 'comunicado', COUNT(*) FROM comunicado UNION ALL
--    SELECT 'admision', COUNT(*) FROM admision UNION ALL
--    SELECT 'celular', COUNT(*) FROM celular UNION ALL
--    SELECT 'logro', COUNT(*) FROM logro UNION ALL
--    SELECT 'config_inicio', COUNT(*) FROM config_inicio;

-- 2. Verificar password placeholder (DEBE actualizarse):
--    SELECT usu_id, usu_pass FROM usuario WHERE usu_id = 1;
--    → Debe mostrar '$2y$10$REPLACE_WITH_REAL_BCRYPT_HASH_VIA_PHP_PASSWORD_HASH'
--    → EJECUTAR: UPDATE usuario SET usu_pass = '$2y$10$...hash_real...' WHERE usu_id = 1;

-- 3. Verificar UUID placeholders (patrón xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx):
--    SELECT foto_acad FROM academico LIMIT 3;
--    SELECT horario_lab FROM laboratorio;
--    SELECT doc_com, foto_com FROM comunicado;
--    SELECT nom_foto FROM foto_extension;
--    SELECT img_adm FROM admision;
--    SELECT afiche FROM config_inicio;

-- 4. Verificar FKs válidas (sin huérfanos):
--    SELECT 'academico→categoria' AS fk, COUNT(*) FROM academico a LEFT JOIN categoria_academico c ON a.cat_acad=c.cat_id WHERE c.cat_id IS NULL UNION ALL
--    SELECT 'academico→usuario', COUNT(*) FROM academico a LEFT JOIN usuario u ON a.usu_id=u.usu_id WHERE a.usu_id IS NOT NULL AND u.usu_id IS NULL UNION ALL
--    SELECT 'laboratorio→academico', COUNT(*) FROM laboratorio l LEFT JOIN academico a ON l.resp_acad_id=a.id_acad WHERE l.resp_acad_id IS NOT NULL AND a.id_acad IS NULL UNION ALL
--    SELECT 'extension→usuario', COUNT(*) FROM extension e LEFT JOIN usuario u ON e.creado_por=u.usu_id WHERE u.usu_id IS NULL UNION ALL
--    SELECT 'comunicado→usuario', COUNT(*) FROM comunicado c LEFT JOIN usuario u ON c.publicado_por=u.usu_id WHERE u.usu_id IS NULL UNION ALL
--    SELECT 'foto_extension→extension', COUNT(*) FROM foto_extension f LEFT JOIN extension e ON f.id_ext=e.id_ext WHERE e.id_ext IS NULL;
--    → Todos deben devolver 0

-- ============================================================================
-- FIN DEL SCRIPT DE MIGRACIÓN
-- ============================================================================