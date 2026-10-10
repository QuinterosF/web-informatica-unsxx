-- ============================================================================
-- database_v2.sql — Esquema v2 Base de Datos Informática UNSXX
-- Compatible: MariaDB 10.6+ / MySQL 8.0+
-- Motor: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- Generado según: spec-001.md, plan-001.md, tasks-001.md (T1.1–T1.4)
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 1. CATÁLOGOS MAESTROS (sin dependencias externas)
-- ----------------------------------------------------------------------------

-- -----------------------------------------------------
-- Tabla: rol
-- Catálogo de roles del sistema (RBAC)
-- -----------------------------------------------------
CREATE TABLE `rol` (
    `rol_id`        TINYINT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `rol_nombre`    VARCHAR(50)         NOT NULL,
    `rol_descripcion` VARCHAR(255)      NULL,
    PRIMARY KEY (`rol_id`),
    UNIQUE KEY `uk_rol_nombre` (`rol_nombre`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Catálogo de roles del sistema (RBAC)';

-- -----------------------------------------------------
-- Tabla: categoria_academico
-- Normaliza la categorización del plantel docente
-- -----------------------------------------------------
CREATE TABLE `categoria_academico` (
    `cat_id`        TINYINT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `cat_nombre`    VARCHAR(100)        NOT NULL,
    PRIMARY KEY (`cat_id`),
    UNIQUE KEY `uk_cat_nombre` (`cat_nombre`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Categorías académicas (Director, Titular, Invitado, Coordinadores, IIDAI)';

-- ----------------------------------------------------------------------------
-- 2. TABLAS PRINCIPALES (dependen solo de catálogos)
-- ----------------------------------------------------------------------------

-- -----------------------------------------------------
-- Tabla: usuario
-- Entidad central de autenticación y autorización
-- -----------------------------------------------------
CREATE TABLE `usuario` (
    `usu_id`            INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `usu_nom`           VARCHAR(150)        NOT NULL,
    `usu_correo`        VARCHAR(150)        NOT NULL,
    `usu_pass`          VARCHAR(255)        NOT NULL COMMENT 'Hash bcrypt/argon2 (mínimo 60 chars)',
    `estado`            TINYINT(1)          NOT NULL DEFAULT 1 COMMENT '1=activo, 0=inactivo',
    `creado_en`         TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `actualizado_en`    TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`usu_id`),
    UNIQUE KEY `uk_usuario_correo` (`usu_correo`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Usuarios del sistema (auth + perfil base)';

-- -----------------------------------------------------
-- Tabla: academico
-- Plantel docente / investigadores
-- -----------------------------------------------------
CREATE TABLE `academico` (
    `id_acad`       INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `nom_acad`      VARCHAR(300)        NOT NULL,
    `foto_acad`     VARCHAR(255)        NOT NULL COMMENT 'UUID filename (RF4)',
    `cel_acad`      VARCHAR(20)         NOT NULL COMMENT 'Formato libre: +591, guiones, espacios',
    `email_acad`    VARCHAR(150)        NOT NULL,
    `cat_acad`      TINYINT UNSIGNED    NOT NULL COMMENT 'FK → categoria_academico.cat_id',
    `desc_acad`     LONGTEXT            NOT NULL,
    `usu_id`        INT UNSIGNED        NULL COMMENT 'FK opcional → usuario.usu_id (si tiene acceso al sistema)',
    PRIMARY KEY (`id_acad`),
    UNIQUE KEY `uk_academico_email` (`email_acad`),
    KEY `idx_academico_cat` (`cat_acad`),
    KEY `idx_academico_usu` (`usu_id`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Plantel docente e investigadores';

-- -----------------------------------------------------
-- Tabla: extension
-- Actividades de extensión universitaria
-- -----------------------------------------------------
CREATE TABLE `extension` (
    `id_ext`        INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `nom_ext`       VARCHAR(500)        NOT NULL,
    `fech_ext`      DATE                NOT NULL,
    `desc_ext`      LONGTEXT            NOT NULL,
    `creado_por`    INT UNSIGNED        NULL COMMENT 'FK → usuario.usu_id (auditoría)',
    PRIMARY KEY (`id_ext`),
    KEY `idx_extension_creado_por` (`creado_por`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Eventos y actividades de extensión';

-- -----------------------------------------------------
-- Tabla: comunicado
-- Comunicados oficiales / noticias
-- -----------------------------------------------------
CREATE TABLE `comunicado` (
    `id_com`        INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `nom_com`       VARCHAR(300)        NOT NULL COMMENT 'Título (normalizado de LONGTEXT)',
    `desc_com`      LONGTEXT            NOT NULL,
    `doc_com`       VARCHAR(255)        NULL COMMENT 'UUID filename PDF (RF4)',
    `foto_com`      VARCHAR(255)        NULL COMMENT 'UUID filename imagen (RF4)',
    `fech_com`      DATE                NOT NULL,
    `publicado_por` INT UNSIGNED        NULL COMMENT 'FK → usuario.usu_id (auditoría)',
    `estado`        TINYINT(1)          NOT NULL DEFAULT 1 COMMENT '1=publicado, 0=borrador',
    PRIMARY KEY (`id_com`),
    KEY `idx_comunicado_fecha` (`fech_com`),
    KEY `idx_comunicado_publicado_por` (`publicado_por`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Comunicados y noticias oficiales';

-- -----------------------------------------------------
-- Tabla: laboratorio
-- Laboratorios de la carrera (6 fijos)
-- -----------------------------------------------------
CREATE TABLE `laboratorio` (
    `id_lab`            INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `nombre_lab`        VARCHAR(100)        NOT NULL COMMENT 'Nombre canónico del laboratorio',
    `resp_usu_id`       INT UNSIGNED        NULL COMMENT 'FK → usuario.usu_id (responsable con acceso sistema)',
    `resp_acad_id`      INT UNSIGNED        NULL COMMENT 'FK → academico.id_acad (responsable académico)',
    `horario_lab`       VARCHAR(255)        NULL COMMENT 'UUID filename PDF horario (RF4)',
    `activo`            TINYINT(1)          NOT NULL DEFAULT 1,
    PRIMARY KEY (`id_lab`),
    UNIQUE KEY `uk_laboratorio_nombre` (`nombre_lab`),
    KEY `idx_laboratorio_resp_usu` (`resp_usu_id`),
    KEY `idx_laboratorio_resp_acad` (`resp_acad_id`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Laboratorios de la carrera (Seguridad, Móvil, Hardware, Software I/II/III)';

-- -----------------------------------------------------
-- Tabla: admision
-- Procesos de admisión (prueba y preuniversitario)
-- -----------------------------------------------------
CREATE TABLE `admision` (
    `id_adm`            INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `proc_adm`          LONGTEXT            NOT NULL,
    `req_adm`           LONGTEXT            NOT NULL,
    `img_adm`           VARCHAR(255)        NULL COMMENT 'UUID filename afiche/imagen (RF4)',
    `tipo`              ENUM('prueba','preuni') NOT NULL COMMENT 'Tipo de proceso de admisión',
    `actualizado_por`   INT UNSIGNED        NULL COMMENT 'FK → usuario.usu_id (auditoría)',
    PRIMARY KEY (`id_adm`),
    KEY `idx_admision_tipo` (`tipo`),
    KEY `idx_admision_actualizado_por` (`actualizado_por`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Procesos de admisión (prueba de aptitud y preuniversitario)';

-- -----------------------------------------------------
-- Tabla: celular
-- Números de contacto institucionales
-- -----------------------------------------------------
CREATE TABLE `celular` (
    `id_cel`        INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `nom_cel`       VARCHAR(150)        NOT NULL,
    `num_cel`       VARCHAR(20)         NOT NULL COMMENT 'VARCHAR para formatos con +, guiones, espacios',
    PRIMARY KEY (`id_cel`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Teléfonos de contacto institucionales';

-- -----------------------------------------------------
-- Tabla: logro
-- Logros y reconocimientos de la carrera
-- -----------------------------------------------------
CREATE TABLE `logro` (
    `id_log`            INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `nom_log`           VARCHAR(300)        NOT NULL,
    `lugar_log`         VARCHAR(150)        NULL,
    `fech_log`          DATE                NOT NULL COMMENT 'Corregido: era VARCHAR(50)',
    `registrado_por`    INT UNSIGNED        NULL COMMENT 'FK → usuario.usu_id (auditoría)',
    PRIMARY KEY (`id_log`),
    KEY `idx_logro_fecha` (`fech_log`),
    KEY `idx_logro_registrado_por` (`registrado_por`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Logros y reconocimientos académicos/deportivos';

-- -----------------------------------------------------
-- Tabla: config_inicio
-- Configuración singleton de la página de inicio (id_inc siempre = 1)
-- -----------------------------------------------------
CREATE TABLE `config_inicio` (
    `id_inc`            TINYINT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `afiche`            VARCHAR(255)        NULL COMMENT 'UUID filename afiche principal (RF4)',
    `presentacion`      LONGTEXT            NOT NULL,
    `perfil`            LONGTEXT            NOT NULL,
    `mision`            LONGTEXT            NOT NULL,
    `vision`            LONGTEXT            NOT NULL,
    `objetivo`          LONGTEXT            NOT NULL,
    `historia`          LONGTEXT            NOT NULL,
    `actualizado_por`   INT UNSIGNED        NULL COMMENT 'FK → usuario.usu_id (auditoría)',
    `actualizado_en`    TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_inc`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Configuración singleton de la home page (una sola fila, id_inc=1)';

-- ----------------------------------------------------------------------------
-- 3. TABLAS HIJAS / INTERMEDIAS (dependen de tablas principales)
-- ----------------------------------------------------------------------------

-- -----------------------------------------------------
-- Tabla: usuario_rol
-- Tabla intermedia N:M para RBAC (RF2)
-- -----------------------------------------------------
CREATE TABLE `usuario_rol` (
    `usu_id`        INT UNSIGNED        NOT NULL,
    `rol_id`        TINYINT UNSIGNED    NOT NULL,
    `asignado_en`   TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`usu_id`, `rol_id`),
    KEY `idx_usuario_rol_rol` (`rol_id`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Asignación de roles a usuarios (RBAC)';

-- -----------------------------------------------------
-- Tabla: foto_extension
-- Galería de fotos por actividad de extensión (RF3: CASCADE)
-- -----------------------------------------------------
CREATE TABLE `foto_extension` (
    `id_foto`       INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    `nom_foto`      VARCHAR(255)        NOT NULL COMMENT 'UUID filename (RF4)',
    `id_ext`        INT UNSIGNED        NOT NULL COMMENT 'FK → extension.id_ext (CASCADE)',
    `subido_por`    INT UNSIGNED        NULL COMMENT 'FK → usuario.usu_id (auditoría)',
    `subido_en`     TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_foto`),
    KEY `idx_foto_extension_ext` (`id_ext`),
    KEY `idx_foto_extension_subido_por` (`subido_por`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Fotos de actividades de extensión (borrado en cascada con extensión)';

-- ----------------------------------------------------------------------------
-- 4. FOREIGN KEYS (orden topológico: padres → hijos)
-- ----------------------------------------------------------------------------

-- 4.1 usuario_rol → usuario (CASCADE) / rol (RESTRICT)
ALTER TABLE `usuario_rol`
    ADD CONSTRAINT `fk_usuario_rol_usuario`
        FOREIGN KEY (`usu_id`) REFERENCES `usuario` (`usu_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_usuario_rol_rol`
        FOREIGN KEY (`rol_id`) REFERENCES `rol` (`rol_id`)
        ON DELETE RESTRICT ON UPDATE CASCADE;

-- 4.2 academico → categoria_academico (RESTRICT) / usuario (SET NULL)
ALTER TABLE `academico`
    ADD CONSTRAINT `fk_academico_categoria`
        FOREIGN KEY (`cat_acad`) REFERENCES `categoria_academico` (`cat_id`)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_academico_usuario`
        FOREIGN KEY (`usu_id`) REFERENCES `usuario` (`usu_id`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- 4.3 laboratorio → usuario (SET NULL) / academico (SET NULL)
ALTER TABLE `laboratorio`
    ADD CONSTRAINT `fk_laboratorio_resp_usuario`
        FOREIGN KEY (`resp_usu_id`) REFERENCES `usuario` (`usu_id`)
        ON DELETE SET NULL ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_laboratorio_resp_academico`
        FOREIGN KEY (`resp_acad_id`) REFERENCES `academico` (`id_acad`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- 4.4 extension → usuario (SET NULL)
ALTER TABLE `extension`
    ADD CONSTRAINT `fk_extension_creado_por`
        FOREIGN KEY (`creado_por`) REFERENCES `usuario` (`usu_id`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- 4.5 comunicado → usuario (SET NULL)
ALTER TABLE `comunicado`
    ADD CONSTRAINT `fk_comunicado_publicado_por`
        FOREIGN KEY (`publicado_por`) REFERENCES `usuario` (`usu_id`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- 4.6 admision → usuario (SET NULL)
ALTER TABLE `admision`
    ADD CONSTRAINT `fk_admision_actualizado_por`
        FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- 4.7 logro → usuario (SET NULL)
ALTER TABLE `logro`
    ADD CONSTRAINT `fk_logro_registrado_por`
        FOREIGN KEY (`registrado_por`) REFERENCES `usuario` (`usu_id`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- 4.8 config_inicio → usuario (SET NULL)
ALTER TABLE `config_inicio`
    ADD CONSTRAINT `fk_config_inicio_actualizado_por`
        FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- 4.9 foto_extension → extension (CASCADE) / usuario (SET NULL)
ALTER TABLE `foto_extension`
    ADD CONSTRAINT `fk_foto_extension_extension`
        FOREIGN KEY (`id_ext`) REFERENCES `extension` (`id_ext`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `fk_foto_extension_subido_por`
        FOREIGN KEY (`subido_por`) REFERENCES `usuario` (`usu_id`)
        ON DELETE SET NULL ON UPDATE CASCADE;

-- ----------------------------------------------------------------------------
-- 5. DATOS SEMILLA (Catálogos)
-- ----------------------------------------------------------------------------

-- 5.1 Roles del sistema (RF2)
INSERT INTO `rol` (`rol_id`, `rol_nombre`, `rol_descripcion`) VALUES
(1, 'admin',        'Acceso total al sistema: gestión de usuarios, roles, y todos los módulos'),
(2, 'docente',      'Acceso a módulos académicos: perfil, laboratorios asignados, comunicados'),
(3, 'editor',       'Gestión de contenido: comunicados, extensión, admisión, inicio'),
(4, 'coordinador',  'Gestión de mención/laboratorio: responsables, horarios, plantel docente');

-- 5.2 Categorías académicas (normalizan cat_acad 1–6 del sistema anterior)
INSERT INTO `categoria_academico` (`cat_id`, `cat_nombre`) VALUES
(1, 'Director de Carrera'),
(2, 'Docente Titular'),
(3, 'Docente Invitado'),
(4, 'Coordinador Mención Computación Móvil'),
(5, 'Coordinador Mención Seguridad Informática'),
(6, 'IIDAI (Instituto de Investigación)');

-- ----------------------------------------------------------------------------
-- FIN DEL SCRIPT
-- ----------------------------------------------------------------------------
-- Verificación rápida post-importación:
--   SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE();
--   → Debe listar 13 tablas
--   SELECT COUNT(*) FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE();
--   → Debe devolver 11 (una por cada FK declarada arriba)
--   SELECT rol_nombre FROM rol; → 4 filas
--   SELECT cat_nombre FROM categoria_academico; → 6 filas
-- ============================================================================