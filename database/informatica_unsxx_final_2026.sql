-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2026 a las 15:05:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `informaticabd_v2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `academico`
--

CREATE TABLE `academico` (
  `id_acad` int(10) UNSIGNED NOT NULL,
  `nom_acad` varchar(300) NOT NULL,
  `foto_acad` varchar(255) NOT NULL COMMENT 'UUID filename (RF4)',
  `cel_acad` varchar(20) NOT NULL COMMENT 'Formato libre: +591, guiones, espacios',
  `mostrar_cel` tinyint(1) NOT NULL DEFAULT 1,
  `email_acad` varchar(150) NOT NULL,
  `sitio_web` varchar(255) DEFAULT NULL COMMENT 'URL del sitio web del docente (opcional)',
  `mostrar_email` tinyint(1) NOT NULL DEFAULT 1,
  `cat_acad` tinyint(3) UNSIGNED NOT NULL COMMENT 'FK → categoria_academico.cat_id',
  `desc_acad` longtext NOT NULL,
  `usu_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK opcional → usuario.usu_id (si tiene acceso al sistema)',
  `activo` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=Visible en portal, 0=Inactivo/Histórico',
  `creado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `actualizado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Plantel docente e investigadores';

--
-- Volcado de datos para la tabla `academico`
--

INSERT INTO `academico` (`id_acad`, `nom_acad`, `foto_acad`, `cel_acad`, `mostrar_cel`, `email_acad`, `sitio_web`, `mostrar_email`, `cat_acad`, `desc_acad`, `usu_id`, `activo`, `creado_por`, `actualizado_por`, `actualizado_en`) VALUES
(4, 'Santos Ireneo Juchasara Colque, Ph.D.', 'img_6adfd9b1cb1e9.jpg', '60494294', 1, 'sijucol@gmail.com', 'https://dr-juchasara.sijucol.workers.dev/', 1, 14, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Doctor en Ciencias de la Computación y Magister en Ciencias de la Computación Mención Seguridad Informática y Software Libre, con más de 16 años de experiencia en docencia universitaria, investigación aplicada y gestión académica. Lideró transformaciones institucionales en la carrera Ingeniería Informática UNSXX: el Rediseño Curricular 2021, la primera Acreditación de la Carrera Ingeniería Informática al Sistema CEUB (2024). Docente titular en Inteligencia Artificial, apasionado en Desarrollo Web, Seguridad Informática, Ethical Hacking, Ciberseguridad, Ciencia de Datos, Redes y Software Libre. Actualmente se desempeña como Coordinador Académico de la carrera y como Coordinador Académico de Doctorado Multidisciplinario en Posgrado UNSXX.</span></p>', NULL, 1, NULL, 1, '2026-10-26 22:52:33'),
(11, 'M.Sc. Ing. Richard Pascual Castro', 'default.png', '68351030', 1, 'richard@gmail.com', NULL, 1, 13, 'Ingeniero Informático, Docente Titular, M.Sc. Gestión, Elaboración y Evaluación de Proyectos, diplomados en Seguridad informática y otros.', NULL, 1, NULL, NULL, '2026-10-21 02:57:14'),
(12, 'M.Sc. Ing. Jhillma Margot Portanda Zurita', 'default.png', '74259358', 1, 'jhillmapz.010980@gmail.com', NULL, 1, 7, 'Ingeniero en Sistemas, Docente Titular (Ingeniería de Software), Maestría en Educación Superior, realizo varios Diplomados', NULL, 1, NULL, 1, '2026-10-26 17:41:36'),
(15, 'M.Sc. Ing. Jorge Villcaez Castillo', 'default.png', '77148375', 1, 'jorgevill2015@gmail.com', NULL, 1, 13, 'Ingeniero de Sistemas, Docente Titular, M.Sc. en Ciencias de Computación mención Computación Móvil y Telecomunicaciones, Maestrante en Rebotica, Doctorante en Ciencias de la Computación, realizo varios diplomados.', 10, 1, NULL, NULL, '2026-10-21 04:26:08'),
(16, 'Leyna Roxana Salinas Veyzaga, Ph.D', 'img_6adfd8f8a5cbe.jpg', '78619651', 0, 'leynasud@gmail.com', NULL, 0, 13, '<p><em>Ingeniero Informático, Universidad Nacional Siglo XX</em></p><p><strong>🧰DIPLOMADOS </strong></p><p><br></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Educación Superior, Universidad Nacional Siglo XX</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Formación Basada en Competencias en la Universidad Boliviana, Universidad Nacional Siglo XX</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Gestión de Seguridad y Auditoria de Sistemas, Universidad Empresarial de los Andes</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Metodología de la Investigación, Universidad Nacional Siglo XX</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Herramientas Tecnológicas para la Educación Superior Virtual, Universidad Nacional Siglo XX</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Internet de las Cosas y la Industria 4.0, Universidad Privada del Valle Santa Cruz</li></ol><p><br></p><p><strong>🧰MAESTRIA </strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Maestría en Ciencias de la Computación Mención Seguridad Informática y Software Libre</li></ol><p><br></p><p><strong>🧰DOCTORADO</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Doctorado en Ciencias de la Computación</li></ol><p><br></p><p><strong>🧰EXPERIENCIA LABORAL </strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>2008-2010 Técnico Informático en la Institución Equipo Kallpa – Proyecto de Gestión y Recursos Naturales.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>2010-2012 Supervisora de Cd Interactivos en la Editorial A.V Magisterio.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>2010-2011 Encargada del Laboratorio de Hardware de la Universidad Nacional Siglo XX.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>2017-2018 Coordinadora de la Mención Sistemas Carrera Ingeniería Informática de la Universidad Nacional Siglo XX</li></ol><p><br></p><p><strong>🧰EXPERIENCIA DOCENTE </strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>2008-2026 Docente Universitaria Carrera Ingeniería Informática, asumí varias materias de la carrera y en la actualidad soy Docente Titular de la materia Estructura de Datos</li></ol><p><br></p><p><strong>🧰EXPERIENCIA DOCENTE POSGRADO </strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>2024 Modulo IV: “Desarrollo Backend” del Programa del Diplomado en Desarrollo Web con software Libre – Versión I</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>2025 Modulo XVI : “Análisis de Riesgos y Gestión de la Seguridad” del programa de Doctorado en ciencias de la Informática e Ingeniería con titulación Intermedia – Versión I.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>2026 Modulo III: “Ciber Seguridad de la Información” del Programa Diplomado en Redes y Seguridad Informática – Versión I</li></ol><p><br></p><p><strong>🧰PRODUCCIÓN INTELECTUAL </strong></p><p><strong>ARTICULO CIENTÍFICOS </strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Salinas, L. (2024). El peligro de utilizar la Redes Sociales y los Vacíos de la Legislación boliviana. Revista Académica Multidisciplinaria SINERGIA N° 23</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Salinas, L. (2024). Modelo Predictivo para Identificar Delitos de Acoso en la Red Social Facebook aplicando Big Data . Revista Académica Multidisciplinaria SINERGIA N° 23</li></ol><p><strong>LIBROS </strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Salinas, L (2024). Big Data Aplicada a un Modelo Predictivo Para identificar Delitos de Acoso en la Red Social Facebook, La Paz – Bolivia: Nexo Editores Bolivia. ISBN: 978-9917-0-4482-6</li></ol>', NULL, 1, NULL, 1, '2026-10-27 00:12:27'),
(17, 'Dra. Yolanda Soto Leyva Ph.D', 'img_6adfd79394af0.png', '73401234', 1, 'ysotole@gmail.com', NULL, 1, 13, '<p><strong>FORMACIÓN ACADÉMICA</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Phd. en Ciencias de la Computación </li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Magister Scientiarum en Ciencias de la Computación</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Magister en Educación Superior</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Licenciado en Ingeniería de Sistemas</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Ingeniero en Sistemas</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Licenciado en Contaduría Pública</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Contador Público</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Licenciado en Bioquímico Farmacéutico</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Bioquímico Farmacéutico</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Educación Superior </li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Temas Curriculares y de Gestión Educativa</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Modelo Curricular para la Formación Basada en Competencias</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Metodología de la Investigación</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomada de la Enseñanza en la Lengua Quechua</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Investigación Científica</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Herramientas Tecnológicas para la Educación Superior Virtual</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Liderazgo Político</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Oncología Pediátrica</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Banca y Fianzas</li></ol><p><br></p><p><strong>EXPERIENCIA DOCENTE: </strong>29 años de experiencia docente</p><p><br></p><p><strong>RECONOCIMIENTOS</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento por su compromiso académico y su participación activa en la semana de orientación vocacional</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento por organizar las 1ras olimpiadas de ajedrez</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento por su destacado trabajo en el proceso de autoevaluación de la carrera ingeniería informática</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento en merito a su desprendido ejercicio como docente universitario e invaluable aporte con la enseñanza y formación en favor de estudiantes</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento en merito a su desprendido ejercicio como docente universitario e invaluable aporte con la enseñanza y formación en favor de nuestros estudiantes</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento en merito a su desprendido ejercicio como docente universitario e invaluable aporte con la enseñanza y formación en favor de nuestros estudiantes</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento por alto sentido de la responsabilidad docente, su dedicación y constancia en el cumplimiento de la más alta función del estado como es la educación y por coadyuvar en la recuperación del derecho a la educación</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento por el esfuerzo y sacrificio demostrado en la enseñanza y formación de profesionales orgánicos en el área de las ciencias de la computación</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento al arduo y delicado labor efectuado en el proceso enseñanza – aprendizaje en beneficio de nuestra casa superior de estudios</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Reconocimiento a su labor de profesor universitario de la UNSXX por el esfuerzo, sacrificio y entrega brindada, en la más alta y noble función como es la educación superior</li></ol><p><br></p><p><strong>PRODUCCIÓN INTELECTUAL EN EL ÁREA</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Modelo Aprendizaje Automático Empleando Modelos Predictivos para la Farmacocinética y  la Interacción de Fármacos En Pacientes Diabeticos de Tipo 2</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Modelos Predictivos en la Farmacocinética</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Hábitos de Estudio con el Uso de Tecnologías de la Información y Comunicación</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Aprendizaje Automático  el Proceso Farmacocinetico de Farmacos</li></ol><p><br></p>', NULL, 1, NULL, 1, '2026-10-26 22:43:31'),
(18, 'Ing. David Cazorla Valdivia', 'img_6adfd235243de.jpg', '74116315', 1, 'hey@davidcv.dev', 'https://davidcv.dev/', 1, 18, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Ingeniero Informático titulado de la Universidad Nacional Siglo XX, con diplomados en Programación de Aplicaciones Móviles y Educación Superior Basada en Competencias. Cuenta con experiencia docente universitaria, desarrollo de sistemas informáticos, mantenimiento de redes de telecomunicaciones y administración de infraestructura tecnológica. Posee conocimientos en desarrollo de software con PHP, JavaScript, Java, Kotlin y Dart, configuración de redes MikroTik, VLAN, radioenlaces y servidores. Sus áreas de interés incluyen la ciberseguridad, redes informáticas, desarrollo de aplicaciones móviles y formación académica.</span></p>', 11, 1, NULL, 1, '2026-10-27 02:13:27'),
(19, 'M.Sc. Ing.Freddy Rocabado Ibañez', 'img_6adfd5fb04eb3.jpg', '68294945', 1, 'rocosfree@gmail.com', NULL, 1, 13, '<ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">UNIVERSIDAD MAYOR DE SAN ANDRES | PREGRADO EN LICENCIADO EN INFORMATICA-MENCION INGENIERIA DE SISTEMAS INFORMATICOS 2005</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">UNIVERSIDAD NACIONAL “SIGLO XX” | MAESTRÍA EN GESTIÓN, ELABORACIÓN Y EVALUACIÓN DE PROYECTOS 2023</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">UNIVERSIDAD NACIONAL “SIGLO XX” | DIPLOMADO: VARIOS 2008 - 2022</span></li></ol><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">DIRECTOR | CARRERA INGENIEIRA INFORMATICA 2016-2020</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Encargado de administrar, planificar, organizar y gestionar recursos humanos y económicos para el buen funcionamiento del mismo referente a la parte académica, investigativa, extensión universitaria, equipamiento e infraestructura.</span></li></ol><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">DIRECTOR | DTIC - UNSXX 2013-2015</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Encargado de administrar, planificar, organizar y gestionar recursos humanos y económicos para la implementación de los diferentes servicios que brinda a la universidad en su conjunto.</span></li></ol><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">ENCARGADO DEL ÁREA DE HARDWARE | CENTRO DE INVESTIGACIÓN Y PRODUCCIÓN EN MEDIOS 2009-2010</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Encargado del Área de Hardware realizando la actualización, mantenimiento preventivo y correctivo de los equipos informáticos.</span></li></ol><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">ENCARGADO DEL ÁREA DE HARDWARE | CENTRO DE INVESTIGACIÓN Y PRODUCCIÓN EN MEDIOS 2008</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Encargado del Área de Hardware realizando la actualización, mantenimiento preventivo y correctivo de los equipos informáticos.</span></li></ol><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">EQUIPO DE DESARROLLO DE PROYECTOS | CELIN-BOLIVIA 2007</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Personal de apoyo en la realización de cuestionarios además del trabajo de campo y codificación mediante el programa SPSS.</span></li></ol><p><strong style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">DOCENTE UNIVERSITARIO | CARRERA INGENIERIA INFORMATICA - UNSXX 2008 - 2026</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Como profesional se asumió varias asignaturas en la carrera ingeniería informática, en la que actualmente me encuentro en calidad de DOCENTE TITULAR en la asignatura de ANALISIS Y DISEÑO DE SISTEMAS. COORDINADOR | CARRERA INGENIERIA INFORMATICA - UNSXX</span></li></ol><p><br></p><p><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">A lo largo de mi trayectoria de docencia universitaria se tuvo que asumir en varias gestiones coordinaciones de mención al interior de la carrera Ingeniería Informática, en las que se pueden destacar; COORDINADOR MENCION INFORMATICA INDUSTRIAL, REDES Y TELEINFORMATICA Y COMPUTACION MOVIL.</span></p>', 29, 1, NULL, 1, '2026-10-27 13:25:24'),
(20, 'M.Sc. Ing. Fernando Miranda Choque', 'default.png', '67237857', 1, 'fernando@gmail.com', NULL, 1, 13, 'Ingeniero Informático, Docente Titular (Telecomunicaciones), M.Ss. en Evaluación de Proyectos y Ciencias de la Computación.', 30, 1, NULL, NULL, '2026-10-24 21:29:24'),
(21, 'Ing. Mary Lovera Copa', 'default.png', '72318145', 1, 'Mar_lc@hotmail.com', NULL, 1, 13, 'Ingeniero de Sistemas, Docentes Titular (Estadística y Probabilidad), Diplomado en Educación Superior y Formación Basada en Competencias.', 15, 1, NULL, 1, '2026-10-24 21:31:47'),
(22, 'Juan Pablo Luna Felipez, Ph.D.', 'default.png', '75418754', 1, 'iidai.informatica.unsxx@gmail.com', NULL, 1, 16, 'Doctor en Ciencias de la Computación, Máster en Ciencias de la Computación con mención en Seguridad Informática y Software Libre, Máster en Educación Superior, cuenta con Diplomados en Preparación Evaluación y Gestión de Proyectos, Formación Basada por Competencias y Metodología de la Investigación Científica.\r\n\r\nHa sido director de la carrera Ingeniería Informática y actualmente es Presidente y fundador de la Sociedad Científica de Docentes de la Universidad Nacional “Siglo XX”, Coordinador del Instituto de Investigación y Desarrollo de Aplicaciones IIDAI, Director de la Revista “Ciencia y Tecnología Informática” y Docente Universitario Titular en la carrera Ingeniería Informática de la Universidad Nacional “Siglo XX”, obtuvo varios premios y reconocimientos.', NULL, 1, NULL, NULL, '2026-10-21 02:57:14'),
(33, 'Lic. Griselda Copa García', 'default.png', '71234567', 1, 'gris@gmail.com', NULL, 1, 17, 'Lic. en Secretariado Ejecutivo', NULL, 1, NULL, NULL, '2026-10-27 00:27:00'),
(34, 'Ing. Fernando Quinteros Coca', 'img_6adfdb3b4ddf9.jpg', '67264027', 1, 'fquinteroscoca@gmail.com', NULL, 1, 13, '<p><em>Ingeniero Informático con mención en Informática Industrial, titulado por Excelencia Académica en la Universidad Nacional “Siglo XX”.</em></p><p><br></p><p><strong>FORMACIÓN ACADÉMICA Y DISTINCIONES</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Diplomado en Educación Superior Basado en Competencias — Posgrado, Universidad Nacional “Siglo XX” </li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Ingeniero Informático con Mención en Informática Industrial — Universidad Nacional “Siglo XX” (Graduado por la modalidad de <strong>Excelencia Académica</strong>).</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Especialización continua en Desarrollo de Software Full-Stack, Desarrollo de Aplicaciones Móviles Multiplataforma, Ciencia de Datos, DevOps y Gestión de Seguridad de la Información.</li></ol><p><strong>EXPERIENCIA DOCENTE UNIVERSITARIO</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>Docente Invitado — Carrera de Ingeniería Informática, U.N.S.XX (2025, 2026):</strong> Docente de las asignaturas de <em>Estructura de Datos</em>, <em>Taller de Desarrollo de Aplicaciones para Dispositivos Móviles</em>, <em>DevOps</em>, <em>Realidad Virtual y Aumentada</em> y <em>Sistemas de Gestión de Seguridad de Tecnologías de la Información</em>.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>Docente Universitario — Escuela Militar de Ingeniería (EMI) en Ingeniería Comercial (2026):</strong> Docente de la asignatura de <em>Analítica de Datos</em> (SQL, Python, Pandas, visualización de datos y estadística multivariante).</li></ol><p><strong>EXPERIENCIA PROFESIONAL Y DESARROLLO TECNOLÓGICO</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>Ingeniero de Software Full-Stack y Móvil:</strong> Arquitecto y desarrollador principal de sistemas web institucionales y aplicaciones móviles utilizando PHP (Arquitectura MVC, Laravel), Dart, Flutter, Java (Spring Boot), Python, JavaScript, Tailwind CSS, MySQL/MariaDB y Supabase (REST APIs / PostgREST).</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>Infraestructura, Redes y Soporte Técnico:</strong> Experiencia profesional en configuración y administración de servidores (Windows/Linux), redes de datos, soporte técnico especializado de hardware y software, y electrónica aplicada / sistemas embebidos (ESP32 / CAN Bus).</li></ol><p><strong>PRODUCCIÓN INTELECTUAL E INVESTIGACIÓN</strong></p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Autor de la investigación monográfica: <em>Análisis de los desafíos pedagógicos en el uso de herramientas de Inteligencia Artificial en la formación por competencias en la Carrera Ingeniería Informática de la Universidad Nacional \"Siglo XX\"</em> (2026).</li></ol>', 12, 1, NULL, 1, '2026-10-27 13:19:30'),
(42, 'Ing. Miguel Reynaldo Callizaya Meave', '0c76bfc87e9314811ac26dc0e323e1ec.jpg', '78605538', 0, 'miguel.callizaya@gmail.com', NULL, 1, 13, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Soy Ingeniero Informático, con formación técnica en Programación de Sistemas y un Diplomado en Educación Superior. Cuento con experiencia en docencia universitaria, soporte técnico, administración de sistemas y gestión de plataformas virtuales. Me especializo en el desarrollo de páginas web y aplicaciones móviles, utilizando tecnologías como Java, Kotlin, JavaScript, PHP, React, Laravel y Node.js. También tengo conocimientos en mantenimiento de equipos informáticos, redes, administración de servidores Windows y Linux.</span></p>', NULL, 1, 1, NULL, '2026-10-26 22:22:59'),
(43, 'Ing. Lenny Lineth Mendoza Gutierrez', '8fda56b31f1a051da90d5f9175f520d7.jpg', '72302692', 1, 'linethli0801@gmail.com', NULL, 1, 13, '<ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Diplomado en Educación Superior por Competencias - Universidad Pública de El Alto</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Diplomado en FullStack Developer Back-end y Front-end - Universidad Simón I. Patiño</span></li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Ingeniero informático, Titulo en Provisión Nacional - Universidad Nacional “Siglo XX”</span></li></ol>', NULL, 1, 1, NULL, '2026-10-26 22:24:59'),
(44, 'Dr. Tito Reinaldo Delgadillo López, Ph.D', 'e7850fd43a1a95d88b1116edc57dd756.jpg', '71764195', 1, 'reinaldodelgadillo@gmail.com', NULL, 0, 13, '<p>Actual Rector de la Universidad Nacional \"Siglo XX\"</p><p>Docente Titular de la asignatura \"Redes I\"</p>', NULL, 1, 1, 1, '2026-10-27 06:41:46'),
(45, 'José Luis Arturo Torrico Villarroel, Ph.D', 'f7819da2dd160a26b231ded852abd25b.jpg', '67211219', 1, 'jatvillarroel@gmail.com', NULL, 1, 13, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Formado como Informático en la Universidad Mayor de San Andrés, Especializado con las maestrías en Educación Superior, Maestría en Ciencias de la Computación, diplomado en Ciberseguridad y Seguridad de la Información. Desde el año 2003 ha impartido docencia en Universidades con experiencia de 23 años del Sistema Universitario, Publicación de Libro: Navegador semántico de Gestión de Conocimiento, Artículos referidos a Arquitectura de procesamiento analítico multidimensional y ciencia de datos. Docente de Postgrado en Doctorado en Ciencias de la Informática e Ingeniería y Diplomados. Especializado en desarrollo de Software Tecnología Linux, desarrollo de proyectos como consultor independiente y como asesor web en empresas privadas, organizaciones asociadas a la cooperación internacional, combina estrategia técnica, estrategia y liderazgo, elaboración de informes ejecutivos y transferencia de conocimiento.</span></p>', NULL, 1, 1, NULL, '2026-10-26 22:30:37'),
(46, 'M.Sc. Ing. Nelson Ayaviri Padilla', 'f5a714b05facc0eb7a3c89f20480228c.jpg', '75402249', 0, 'nelsonayavirip@gmail.com', NULL, 0, 15, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Maestría en Preparación, gestión y evaluación de proyectos, Maestría en Redes y Seguridad Informática, Diplomado en Educación Superior, Diplomado en Educación Superior Basado por Competencias.</span></p>', 31, 1, 1, NULL, '2026-10-27 02:09:51'),
(47, 'M.Sc. Elizabeth Mejía García', 'ea40d020f9b36fa776fed71335139535.png', '71180129', 0, 'elymej15@gmail.com', NULL, 0, 19, '<p><span style=\"background-color: rgb(255, 255, 255); color: rgb(31, 31, 31);\">Master en Ciencias de la Computación – Mención Seguridad Informática y Software Libre de la Universidad Nacional “Siglo XX”, Master en Educación Superior de la Universidad Mayor de San Andrés, Ingeniero de Sistemas, docente de pregrado y posgrado, docente titular en la carrera Ing. Informática de la UNSXX.</span></p>', 32, 1, 1, 1, '2026-10-27 02:10:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admision`
--

CREATE TABLE `admision` (
  `id_adm` int(10) UNSIGNED NOT NULL,
  `proc_adm` longtext NOT NULL,
  `req_adm` longtext NOT NULL,
  `img_adm` varchar(255) DEFAULT NULL COMMENT 'UUID filename afiche/imagen (RF4)',
  `gestion` int(4) NOT NULL DEFAULT 2026,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `tipo` enum('prueba','preuni') NOT NULL COMMENT 'Tipo de proceso de admisión',
  `actualizado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK → usuario.usu_id (auditoría)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Procesos de admisión (prueba de aptitud y preuniversitario)';

--
-- Volcado de datos para la tabla `admision`
--

INSERT INTO `admision` (`id_adm`, `proc_adm`, `req_adm`, `img_adm`, `gestion`, `estado`, `tipo`, `actualizado_por`) VALUES
(4, '<p>El Curso Preuniversitario es una modalidad preparatoria diseñada para nivelar y fortalecer los conocimientos en materias básicas del nivel secundario, garantizando un adecuado inicio en la educación superior. Este curso tiene una duración mínima de 240 horas académicas y aborda asignaturas fundamentales para la carrera, incluyendo Formación Político Sindical. El proceso de evaluación es continuo y comprende pruebas parciales, trabajos prácticos y una evaluación final. Para aprobar el curso, el postulante debe alcanzar una nota mínima del 51 por ciento en cada una de las asignaturas. Por la naturaleza de este curso, no existen pruebas de segunda instancia ni exámenes de recuperación.</p>', '<p>Para habilitarse y cursar el Curso Preuniversitario, el postulante deberá presentar en el Departamento de Registros e Inscripciones una fotocopia simple del Título de Bachiller o libreta de sexto de secundaria que acredite la culminación del nivel secundario, una fotocopia de la Cédula de Identidad vigente, y el pago por derecho al curso preuniversitario.</p>', 'img_6ae032baa8207.jpg', 2026, 1, 'preuni', 1),
(5, '<p>La Prueba de Admisión Académica es una modalidad que tiene por objeto evaluar el nivel de preparación y los conocimientos básicos adquiridos por el postulante durante el ciclo secundario. Asimismo, permite evaluar las aptitudes y facultades del bachiller mediante un diagnóstico psicométrico.</p><p><br></p><p>Esta modalidad permite a los postulantes que aprueben la evaluación el ingreso directo a la Carrera de Ingeniería Informática. La prueba es administrada por una comisión designada por la Dirección de Carrera y la nota mínima de aprobación exigida por el reglamento es del 51 por ciento.</p>', '<p>Para la inscripción oficial a la Prueba de Admisión Académica, el postulante debe presentar en el Departamento de Registros e Inscripciones una fotocopia simple del título de bachiller o, en su defecto, la libreta de sexto de secundaria; una fotocopia de la Cédula de Identidad vigente; y la cancelación del monto económico por derecho a la prueba académica cancelado en la ventanilla de la Universidad.</p>', 'img_6ae03250b99b5.jpg', 2026, 1, 'prueba', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_eliminacion`
--

CREATE TABLE `auditoria_eliminacion` (
  `id_aud` int(10) UNSIGNED NOT NULL,
  `modulo` varchar(60) NOT NULL COMMENT 'Nombre del módulo (Documentos, Comunicados, etc.)',
  `registro_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'ID original del registro eliminado',
  `titulo_registro` varchar(300) NOT NULL COMMENT 'Título o nombre del elemento eliminado',
  `eliminado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `eliminado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro histórico de elementos eliminados físicamente';

--
-- Volcado de datos para la tabla `auditoria_eliminacion`
--

INSERT INTO `auditoria_eliminacion` (`id_aud`, `modulo`, `registro_id`, `titulo_registro`, `eliminado_por`, `eliminado_en`) VALUES
(3, 'Comunicados', 19, 'Calendario Académico 2027', 12, '2026-10-23 02:20:13'),
(4, 'Comunicados', 20, 'Calendario Académico 2021', 12, '2026-10-23 02:20:53'),
(5, 'Documentos', 4, 'Plan de Estudios', 1, '2026-10-24 21:23:55'),
(6, 'Documentos', 8, 'Reglamento de Admisión III', 1, '2026-10-24 21:23:58'),
(7, 'Documentos', 7, 'Reglamento de Admisión II', 1, '2026-10-24 21:24:01'),
(8, 'Comunicados', 16, 'Cronogramas de actividades aniversario', 1, '2026-10-24 21:35:54'),
(9, 'Portadas', 4, 'img_6ad19d43715ba.jpg', 1, '2026-10-26 21:40:39'),
(10, 'Portadas', 3, 'img_6ad19d436e7a3.jpg', 1, '2026-10-26 21:40:42'),
(11, 'Portadas', 1, 'img_6ad19d436967d.jpg', 1, '2026-10-26 21:40:45'),
(12, 'Portadas', 5, 'Bienvenidos a Ing. Informática', 1, '2026-10-26 21:40:47'),
(13, 'Portadas', 6, 'Laboratorios con equipos de última generación', 1, '2026-10-26 21:40:50'),
(14, 'Portadas', 9, 'img_6adfc92aaa24b.jpg', 1, '2026-10-26 21:43:22'),
(15, 'Académicos', 32, 'Ing. Lenny Mendoza Gutierrez', 1, '2026-10-27 00:18:44'),
(16, 'Documentos', 10, 'Horario 1er Año', 1, '2026-10-27 00:36:23'),
(17, 'Documentos', 11, 'Reglamento de Titulación', 1, '2026-10-27 00:36:36'),
(18, 'Documentos', 22, 'Reglamento de Admisión', 1, '2026-10-27 01:23:08'),
(19, 'Laboratorios', 4, 'Foto de galería: Computación Móvil', 1, '2026-10-27 02:10:08'),
(20, 'Laboratorios', 1, 'Foto de galería: Computación Móvil', 1, '2026-10-27 02:10:12'),
(21, 'Laboratorios', 3, 'Foto de galería: Computación Móvil', 1, '2026-10-27 02:10:15'),
(22, 'Laboratorios', 27, 'Foto de galería: Hardware', 1, '2026-10-27 02:10:57'),
(23, 'Laboratorios', 28, 'Foto de galería: Hardware', 1, '2026-10-27 02:11:01'),
(24, 'Laboratorios', 29, 'Foto de galería: Hardware', 1, '2026-10-27 02:11:05'),
(25, 'Laboratorios', 30, 'Foto de galería: Hardware', 1, '2026-10-27 02:11:08'),
(26, 'Laboratorios', 31, 'Foto de galería: Hardware', 1, '2026-10-27 02:11:12'),
(27, 'Laboratorios', 22, 'Foto de galería: Seguridad Informática', 1, '2026-10-27 02:13:37'),
(28, 'Laboratorios', 21, 'Foto de galería: Seguridad Informática', 1, '2026-10-27 02:13:40'),
(29, 'Laboratorios', 23, 'Foto de galería: Seguridad Informática', 1, '2026-10-27 02:13:44'),
(30, 'Laboratorios', 20, 'Foto de galería: Seguridad Informática', 1, '2026-10-27 02:13:47'),
(31, 'Laboratorios', 24, 'Foto de galería: Seguridad Informática', 1, '2026-10-27 02:13:50'),
(32, 'Laboratorios', 25, 'Foto de galería: Seguridad Informática', 1, '2026-10-27 02:13:53'),
(33, 'Laboratorios', 33, 'Foto de galería: Software I', 1, '2026-10-27 02:14:15'),
(34, 'Laboratorios', 32, 'Foto de galería: Software I', 1, '2026-10-27 02:14:18'),
(35, 'Laboratorios', 34, 'Foto de galería: Software I', 1, '2026-10-27 02:14:21'),
(36, 'Laboratorios', 35, 'Foto de galería: Software I', 1, '2026-10-27 02:14:24'),
(37, 'Laboratorios', 36, 'Foto de galería: Software II', 1, '2026-10-27 02:14:49'),
(38, 'Laboratorios', 37, 'Foto de galería: Software II', 1, '2026-10-27 02:14:52'),
(39, 'Laboratorios', 38, 'Foto de galería: Software II', 1, '2026-10-27 02:14:54'),
(40, 'Comunicados', 14, 'REQUISITOS ESTUDIANTES NUEVOS 2024', 1, '2026-10-27 02:34:10'),
(41, 'Comunicados', 18, 'Calendario Académico 2026', 1, '2026-10-27 02:34:13'),
(42, 'Comunicados', 17, 'Calendario Académico 2025', 1, '2026-10-27 02:34:16'),
(43, 'Extensión', 3, 'XV Congreso Nacional de Informática', 1, '2026-10-27 02:44:15'),
(44, 'Extensión', 6, 'Feria de Innovación y Tecnología 2026', 1, '2026-10-27 02:44:18'),
(45, 'Extensión', 5, 'ICPC Fase Regional 2026', 1, '2026-10-27 02:44:21'),
(46, 'Extensión', 4, 'Festival Latinoamericano de Software Libre 2026', 1, '2026-10-27 02:44:25'),
(47, 'Portadas', 14, 'Biblioteca Especializada', 1, '2026-10-27 08:02:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_academico`
--

CREATE TABLE `categoria_academico` (
  `cat_id` tinyint(3) UNSIGNED NOT NULL,
  `cat_nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Categorías académicas (Director, Titular, Invitado, Coordinadores, IIDAI)';

--
-- Volcado de datos para la tabla `categoria_academico`
--

INSERT INTO `categoria_academico` (`cat_id`, `cat_nombre`) VALUES
(14, 'Coord. Académico'),
(15, 'Coord. Computación Móvil'),
(16, 'Coord. IIDAI'),
(18, 'Coord. Seguridad Informática'),
(7, 'Director/a'),
(13, 'Docente'),
(19, 'Responsable Lab. Hardware'),
(17, 'Secretaria/o');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_extension`
--

CREATE TABLE `categoria_extension` (
  `id_cat_ext` int(10) UNSIGNED NOT NULL,
  `nom_cat` varchar(100) NOT NULL COMMENT 'Ej: ICPC, FLISoL, CTF, Congreso',
  `slug_cat` varchar(120) NOT NULL COMMENT 'URL amigable SEO',
  `color_badge` varchar(30) NOT NULL DEFAULT 'blue' COMMENT 'Color visual en UI',
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categoria_extension`
--

INSERT INTO `categoria_extension` (`id_cat_ext`, `nom_cat`, `slug_cat`, `color_badge`, `activo`) VALUES
(1, 'Congreso Nacional de Informática', 'congreso-nacional-de-informatica', 'yellow', 1),
(2, 'ICPC Programación', 'icpc-programacion', 'yellow', 1),
(3, 'Ciberseguridad CTF', 'ciberseguridad-ctf', 'red', 1),
(4, 'FLISoL Software Libre', 'flisol-software-libre', 'green', 1),
(5, 'Feria de Investigación', 'feria-investigacion', 'purple', 1),
(6, 'Extensión Social', 'extension-social', 'teal', 1),
(7, 'CCBOL', 'ccbol', 'blue', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `celular`
--

CREATE TABLE `celular` (
  `id_cel` int(10) UNSIGNED NOT NULL,
  `nom_cel` varchar(150) NOT NULL,
  `num_cel` varchar(20) NOT NULL COMMENT 'VARCHAR para formatos con +, guiones, espacios',
  `icono` varchar(50) DEFAULT 'fab fa-whatsapp',
  `es_soporte` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=Número oficial de Soporte Técnico del sistema',
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `actualizado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Teléfonos de contacto institucionales';

--
-- Volcado de datos para la tabla `celular`
--

INSERT INTO `celular` (`id_cel`, `nom_cel`, `num_cel`, `icono`, `es_soporte`, `estado`, `actualizado_por`) VALUES
(5, 'Soporte Técnico', '67264027', 'fas fa-mobile-alt', 1, 1, 1),
(7, 'Coordinación Académica', '60492494', 'fab fa-whatsapp', 0, 1, NULL),
(8, 'Coorporativo', '72457622', 'fas fa-tty', 0, 1, NULL),
(9, 'Dirección de Carrera', '74259358', 'fab fa-whatsapp', 0, 1, NULL),
(10, 'Secretaría de Carrera', '67261109', 'fab fa-whatsapp', 0, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunicado`
--

CREATE TABLE `comunicado` (
  `id_com` int(10) UNSIGNED NOT NULL,
  `nom_com` varchar(300) NOT NULL COMMENT 'Título (normalizado de LONGTEXT)',
  `desc_com` longtext DEFAULT NULL,
  `doc_com` varchar(255) DEFAULT NULL COMMENT 'UUID filename PDF (RF4)',
  `foto_com` varchar(255) DEFAULT NULL COMMENT 'UUID filename imagen (RF4)',
  `fech_com` date NOT NULL,
  `publicado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK → usuario.usu_id (auditoría)',
  `actualizado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=publicado, 0=borrador',
  `fijado` tinyint(1) NOT NULL DEFAULT 0,
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Comunicados y noticias oficiales';

--
-- Volcado de datos para la tabla `comunicado`
--

INSERT INTO `comunicado` (`id_com`, `nom_com`, `desc_com`, `doc_com`, `foto_com`, `fech_com`, `publicado_por`, `actualizado_por`, `estado`, `fijado`, `actualizado_en`) VALUES
(21, 'Convocatoria Expositores - XVI Congreso Nacional de Informática', '<p>Invitamos a todos los profesionales a participar en calidad de <strong>expositores </strong>en el XVI Congreso Nacional de Informática.</p><p><em>Formulario de Postulación:</em> <u style=\"background-color: transparent; color: rgb(90, 167, 255);\"><a href=\"https://forms.gle/bUhooksnZKxvDads8?fbclid=IwZXh0bgNhZW0CMTAAcGRvZgVicmlkETF5bmg1eHlOSHUydnJKcnFyc3J0YwZhcHBfaWQQMjIyMDM5MTc4ODIwMDg5MgABHoYeqv7Q8JAJQj3zaRst0XCcyUWI-HeyvNs_O7loyYi6tera6G37Qw8NRd95_aem_tHPEj5I8KvfspIEjagR-6Q\" rel=\"noopener noreferrer\" target=\"_blank\">https://forms.gle/bUhooksnZKxvDads8</a></u></p><p><br></p>', NULL, 'img_6ae00eaa614a6.jpg', '2026-10-14', 1, NULL, 1, 0, '2026-10-27 02:38:34'),
(22, 'Convocatoria Artículos Científicos - Revista Ciencia y Tecnología Informática N° 7', '<p>La carrera de Ingeniería de Informática de la Universidad Nacional “Siglo XX”, a través del Instituto de Investigación y Desarrollo de Aplicaciones Informáticas (IIDAI) invita a docentes, investigadores, profesionales y estudiantes a presentar artículos científicos originales para la edición 2026 de la: Revista Ciencia y Tecnología Informática con ISSN versión Impresa: 3078-2449 e ISSN versión Digital: 3078-2457 publicada en el sistema abierto Open Journal System (OJS).</p>', '', 'img_6ae00fd335131.jpg', '2026-10-08', 1, 1, 1, 0, '2026-10-27 02:43:45'),
(23, 'Competencia de Programación Competitiva ICPC Fase Nacional 1 - Sede Llallagua', '', '', 'img_6ae0123f8bbc3.jpg', '2026-08-28', 1, 1, 1, 0, '2026-10-27 06:46:52'),
(24, 'Festival Latinoamericano de Instalación de Software Libre - FLISoL Llallagua - 2026', '<p>Participa de este gran evento Viernes 24/IV/26 a partir de horas 14:00 en el Salón Auditorium de la Carrera Ingeniería Informática mas información en <a href=\"https://flisol.info/FLISOL2026/Bolivia/Llallagua?fbclid=IwZXh0bgNhZW0CMTAAcGRvZgVicmlkETF5bmg1eHlOSHUydnJKcnFyc3J0YwZhcHBfaWQQMjIyMDM5MTc4ODIwMDg5MgABHhbetAdxXeMQYMHb-n-Q6fV2dLtRC8w6EBIuljYsNuP7Y89-jL-CcGpnJqwK_aem_sb74PACiHFkfBj-OVehKgA\" rel=\"noopener noreferrer\" target=\"_blank\" style=\"background-color: transparent; color: rgb(90, 167, 255);\">https://flisol.info/FLISOL2026/Bolivia/Llallagua</a></p>', NULL, 'img_6ae017c743e2b.jpg', '2026-04-20', 1, NULL, 1, 0, '2026-10-27 03:17:27'),
(25, 'Convocatoria Presentación de Trabajos y Proyectos \"Día del Arduino\"', '', '', 'img_6ae018486fb6d.jpg', '2026-03-23', 1, 1, 1, 0, '2026-10-27 03:20:02'),
(26, 'XV Congreso Nacional de Informática - UNSXX', '<p>Este 27 y 28 de noviembre de 2025 participa del Congreso Nacional de Informática, el mayor evento académico-investigativo que busca fortalecer la colaboración y el intercambio de conocimientos entre profesionales, docentes y estudiantes en el campo de la Informática.</p>', NULL, 'img_6ae01c81a1c0f.jpg', '2025-11-24', 1, NULL, 1, 0, '2026-10-27 03:37:37'),
(27, 'Concurso de Trabajos de Informática', '<p>Participa del XV concurso de trabajos de investigación en Informática, registra tu trabajo, se certificarán todos los trabajos y se premiarán a los mejores.</p>', '', 'img_6ae01e731dc91.jpg', '2025-11-17', 1, 1, 1, 0, '2026-10-27 08:22:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_oficial`
--

CREATE TABLE `documento_oficial` (
  `id_doc` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `categoria` enum('plan_estudios','horario_clases','reglamento_titulacion','reglamento_admision','reglamento_interno','reglamento_macro','otro') NOT NULL DEFAULT 'otro',
  `archivo_pdf` varchar(255) NOT NULL,
  `gestion` varchar(20) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `subido_por` int(10) UNSIGNED NOT NULL,
  `actualizado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `documento_oficial`
--

INSERT INTO `documento_oficial` (`id_doc`, `titulo`, `categoria`, `archivo_pdf`, `gestion`, `estado`, `subido_por`, `actualizado_por`, `created_at`, `actualizado_en`) VALUES
(5, 'Malla Curricular', 'plan_estudios', 'doc_6adff2473d459.pdf', '2021', 1, 1, 1, '2026-10-08 04:09:21', '2026-10-27 00:37:27'),
(6, 'Reglamento de Admisión', 'reglamento_admision', 'doc_6acb0e88c0a6d.pdf', '2015', 1, 1, 1, '2026-10-11 04:20:24', '2026-10-27 04:13:06'),
(9, 'Plan de Estudios', 'plan_estudios', 'doc_6add229a05ff0.pdf', '2021', 1, 1, 1, '2026-10-24 21:26:50', '2026-10-27 00:37:37'),
(12, 'Horario Académico - 1° Año', 'horario_clases', 'doc_6adff2d47f19d.pdf', '2026', 1, 1, 1, '2026-10-27 00:39:48', '2026-10-27 00:44:17'),
(13, 'Horario Académico - 2° Año', 'horario_clases', 'doc_6adff2f075066.pdf', '2026', 1, 1, 1, '2026-10-27 00:40:16', '2026-10-27 00:44:09'),
(14, 'Horario Académico - 3° Año', 'horario_clases', 'doc_6adff31394489.pdf', '2026', 1, 1, 1, '2026-10-27 00:40:51', '2026-10-27 00:44:01'),
(15, 'Horario Académico - 4° Año', 'horario_clases', 'doc_6adff330e1aec.pdf', '2026', 1, 1, 1, '2026-10-27 00:41:20', '2026-10-27 00:43:50'),
(16, 'Horario Académico - 5° Año', 'horario_clases', 'doc_6adff3b894faf.pdf', '2026', 1, 1, NULL, '2026-10-27 00:43:36', '2026-10-27 00:43:36'),
(17, 'Reglamento de Titulación - Vía Diplomado', 'reglamento_titulacion', 'doc_6adff44085b1d.pdf', '2021', 1, 1, 1, '2026-10-27 00:45:52', '2026-10-27 00:52:10'),
(18, 'Reglamento de Titulación - Trabajo Dirigido', 'reglamento_titulacion', 'doc_6adff48ebe838.pdf', '2021', 1, 1, 1, '2026-10-27 00:47:10', '2026-10-27 00:51:51'),
(19, 'Reglamento de Titulación - Excelencia Académica', 'reglamento_titulacion', 'doc_6adff596cbdba.pdf', '2021', 1, 1, NULL, '2026-10-27 00:51:34', '2026-10-27 00:51:34'),
(20, 'Reglamento de Titulación - Proyecto de Grado', 'reglamento_titulacion', 'doc_6adff6a77bc34.pdf', '2021', 1, 1, NULL, '2026-10-27 00:56:07', '2026-10-27 00:56:07'),
(21, 'Reglamento de Titulación - Tesis de Grado', 'reglamento_titulacion', 'doc_6adff6d08571c.pdf', '2021', 1, 1, NULL, '2026-10-27 00:56:48', '2026-10-27 00:56:48'),
(23, 'Reglamento de Uso de Laboratorios', 'otro', 'doc_6ae003c8e7f58.pdf', '2021', 1, 1, NULL, '2026-10-27 01:52:08', '2026-10-27 01:52:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enlace_servicio`
--

CREATE TABLE `enlace_servicio` (
  `id_enl` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `url` varchar(500) NOT NULL,
  `icono` varchar(100) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `creado_por` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `enlace_servicio`
--

INSERT INTO `enlace_servicio` (`id_enl`, `nombre`, `url`, `icono`, `estado`, `creado_por`) VALUES
(1, 'Sitio Web IIDAI', 'https://iidai.iinformatica-unsxx.edu.bo/', 'fas fa-graduation-cap', 1, 1),
(2, 'Página de Facebook', 'https://www.facebook.com/inforunsxx', 'fab fa-facebook', 1, 1),
(6, 'Canal de Telegram', 'https://t.me/informatica_unsxx', 'fab fa-telegram', 1, 1),
(7, 'SIAA - UNSXX', 'http://siaa.unsxx.edu.bo/', 'fas fa-book-reader', 1, 1),
(8, 'Moodle Ing. Informática - UNSXX', 'https://tecnologia.unsxx.net/course/index.php?categoryid=70', 'fas fa-chalkboard-teacher', 1, 1),
(9, 'U.N.S.XX', 'https://www.unsxx.bo/index.php?vista=carrera_informatica', 'fas fa-graduation-cap', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extension`
--

CREATE TABLE `extension` (
  `id_ext` int(10) UNSIGNED NOT NULL,
  `id_cat_ext` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> categoria_extension',
  `nom_ext` varchar(500) NOT NULL,
  `slug_ext` varchar(320) DEFAULT NULL COMMENT 'URL amigable SEO',
  `fech_ext` date NOT NULL,
  `lugar_ext` varchar(200) DEFAULT NULL COMMENT 'Ubicación o modalidad del evento',
  `desc_ext` longtext NOT NULL,
  `enlace_externo` varchar(255) DEFAULT NULL COMMENT 'Link a inscripción, transmisión o repo',
  `destacado` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Fijado arriba como post destacado',
  `activo` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Publicado, 0 = Borrador/Oculto',
  `creado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK → usuario.usu_id (auditoría)',
  `actualizado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Eventos y actividades de extensión';

--
-- Volcado de datos para la tabla `extension`
--

INSERT INTO `extension` (`id_ext`, `id_cat_ext`, `nom_ext`, `slug_ext`, `fech_ext`, `lugar_ext`, `desc_ext`, `enlace_externo`, `destacado`, `activo`, `creado_por`, `actualizado_por`, `actualizado_en`) VALUES
(7, 2, 'Competencia de Programación Competitiva ICPC Fase Nacional - Sede Llallagua 2026', 'competencia-de-programacion-competitiva-icpc-fase-nacional-sede-llallagua-2026', '2026-09-19', 'Bloque B - Carrera Ingeniería Informática', '<p>La carrera de Ingeniería Informática llevo adelante y participo con éxito en la Competencia de Programación Competitiva ICPC Fase Nacional - Dede Llallagua 2026</p>', NULL, 0, 1, 1, NULL, '2026-10-27 02:46:41'),
(8, 4, 'Festival Latinoamericano de Software Libre - Llallagua 2026', 'festival-latinoamericano-de-software-libre-llallagua-2026', '2026-04-25', 'Edificio de Ciencia y Tecnología - IIDAI', '<p><strong><em>Así se vivió el segundo día de la FLISOL.</em></strong></p><p>Con la carrera de Ingeniería Informática, en las instalaciones de IDAII, se llevó a cabo la instalación de software libre en los laboratorios, promoviendo el uso de herramientas tecnológicas abiertas para toda la comunidad. </p><p>Gracias a todos los participantes por ser parte de esta gran experiencia y seguir impulsando el conocimiento libre. </p><p><br></p>', NULL, 0, 1, 1, NULL, '2026-10-27 02:57:51'),
(9, 4, 'Entrega de Reconocimientos – FLISoL Llallagua 2026', 'entrega-de-reconocimientos-flisol-llallagua-2026', '2026-04-25', 'Auditorio de la Carrera Ing. Informática - UNSXX', '<p><strong><em>Entrega de Reconocimientos – FLISoL 2026 </em></strong></p><p>En el marco del Festival Latinoamericano de Instalación de Software Libre, se realizó la entrega de reconocimientos a nuestros expositores, en agradecimiento por su valiosa participación y aporte al conocimiento en software libre.</p><p>¡Gracias por compartir su experiencia y contribuir al crecimiento tecnológico!</p><p><br></p>', NULL, 0, 1, 1, 1, '2026-10-27 03:06:10'),
(10, 4, 'Jornada de Exposiciones - Congreso Internacional FLISoL - Llallagua 2026', 'jornada-de-exposiciones-congreso-internacional-flisol-llallagua-2026', '2026-04-24', 'Auditorio de la Carrera Ing. Informática - UNSXX', '<p><strong><em>Congreso Internacional FLISoL 2026 </em></strong></p><p>En el marco del Festival Latinoamericano de Instalación de Software Libre , destacamos la valiosa participación de nuestros expositores, quienes compartieron conocimientos, experiencias e ideas innovadoras en el ámbito del software libre.</p><p><em>¡Gracias por inspirar y aportar al crecimiento tecnológico!</em></p><p><br></p>', NULL, 0, 1, 1, NULL, '2026-10-27 03:08:55'),
(11, 1, 'XV Congreso Nacional de Informática - UNSXX', 'xv-congreso-nacional-de-informatica-unsxx', '2025-11-28', 'Auditorio de la Carrera Ing. Informática - UNSXX', '<p><strong>XV Congreso Nacional de Informática \"UNSXX\"</strong></p><p>Con la presencia del Rector Dr. Reinaldo Delgadillo López, PhD, M.Sc. Igor Hurtado Arias, Director General de Extensión, Director de Carrera e invitados especiales, se llevó a cabo la inauguración del XV Congreso Nacional de Informática, un espacio académico que reúne a especialistas y expositores nacionales e internacionales para compartir los avances más recientes en tecnología, innovación y transformación digital.</p><p>Durante el acto inaugural, las autoridades destacaron la importancia del congreso como un punto de encuentro para el intercambio de conocimientos y el fortalecimiento de la investigación en el campo de la informática.</p><p><br></p>', NULL, 0, 1, 1, 1, '2026-10-27 03:29:58'),
(12, 1, 'Expositores - XV Congreso Nacional de Informática - UNSXX', 'expositores-xv-congreso-nacional-de-informatica-unsxx', '2025-11-27', 'Auditorio de la Carrera Ing. Informática - UNSXX', '<p>El XV Congreso Nacional de Informática, el espacio para intercambiar conocimientos e investigaciones en Informática, te presentamos al selecto grupo de expositores del XV Congreso Nacional de Informática.</p>', NULL, 0, 1, 1, 1, '2026-10-27 03:35:36'),
(13, 7, 'Congreso Nacional de Ciencias de la Computación (CCBOL) 2025', 'congreso-nacional-de-ciencias-de-la-computacion-ccbol-2025', '2025-11-07', 'Oruro, Bolivia', '<p>En días recientes la carrera de Ingeniería Informática obtuvo el PRIMER LUGAR en el concurso de Trabajo de Investigación en el Congreso Nacional de Ciencias de la Computación Bolivia CCBOL 2025.</p><p>CCBOL es el mayor evento académico - investigativo Boliviano en Ciencias de la Computación, donde participan las carreras de Ingeniería Informática, Ingeniería de Sistemas, Ingeniería de Telecomunicación, etc. de todas la universidades del Sistema Universitario Boliviano.</p><p>El Concurso de trabajos de Investigación Científica es uno de los eventos mas importantes de CCBOL, que en la presente gestión siguió varias etapas en su desarrollo: Convocatoria, Presentación, Revisión por pares, Clasificación, Exposición de trabajos clasificados, Calificación por jurado Internacional - Nacional y Premiación.</p><p>Felicitar al docente Juan Pablo Luna Felipez, Ph.D. quien llevo en alto el nombre de nuestra carrera y nuestra Universidad, destacando entre 14 trabajos de Investigación de las distintas Universidades del Sistema Boliviano.</p><p><br></p>', NULL, 0, 1, 1, NULL, '2026-10-27 03:43:36'),
(14, 2, 'Competencia Latinoamericana de Programación ICPC 2025', 'competencia-latinoamericana-de-programacion-icpc-2025', '2025-11-08', 'Santa Cruz, Bolivia', '<p>Felicitar a la delegación de estudiantes por su gran representación y participación en nombre de la carrera Ingeniería Informática y de la LA Universidad Nacional \"Siglo XX\", en la Competencia Internacional de Programación Competitiva ICPC fase Latinoamericana, el cual se llevo adelante para Bolivia en la ciudad de Santa Cruz en ambientes de la UNIVALLE.</p>', NULL, 0, 1, 1, 1, '2026-10-27 03:51:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foto_extension`
--

CREATE TABLE `foto_extension` (
  `id_foto` int(10) UNSIGNED NOT NULL,
  `nom_foto` varchar(255) NOT NULL COMMENT 'UUID filename (RF4)',
  `es_portada` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Foto principal del post/evento',
  `id_ext` int(10) UNSIGNED NOT NULL COMMENT 'FK → extension.id_ext (CASCADE)',
  `subido_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK → usuario.usu_id (auditoría)',
  `subido_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Fotos de actividades de extensión (borrado en cascada con extensión)';

--
-- Volcado de datos para la tabla `foto_extension`
--

INSERT INTO `foto_extension` (`id_foto`, `nom_foto`, `es_portada`, `id_ext`, `subido_por`, `subido_en`) VALUES
(19, 'img_6ae0119a52eb8.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(20, 'img_6ae0119a56ae7.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(21, 'img_6ae0119a58304.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(22, 'img_6ae0119a5baf3.jpg', 1, 7, 1, '2026-10-27 02:51:06'),
(23, 'img_6ae0119a5e177.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(24, 'img_6ae0119a5f939.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(25, 'img_6ae0119a61ff9.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(26, 'img_6ae0119a63c3f.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(27, 'img_6ae0119a65f1c.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(28, 'img_6ae0119a678e2.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(29, 'img_6ae0119a68ff2.jpg', 0, 7, 1, '2026-10-27 02:51:06'),
(30, 'img_6ae013b47d9eb.jpg', 0, 8, 1, '2026-10-27 03:00:04'),
(31, 'img_6ae013b481f07.jpg', 1, 8, 1, '2026-10-27 03:00:04'),
(32, 'img_6ae013b483710.jpg', 0, 8, 1, '2026-10-27 03:00:04'),
(33, 'img_6ae013b485749.jpg', 0, 8, 1, '2026-10-27 03:00:04'),
(34, 'img_6ae013b48949d.jpg', 0, 8, 1, '2026-10-27 03:00:04'),
(35, 'img_6ae014e46acc9.jpg', 1, 9, 1, '2026-10-27 03:05:08'),
(36, 'img_6ae014e46d360.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(37, 'img_6ae014e470fd6.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(38, 'img_6ae014e47328b.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(39, 'img_6ae014e474b76.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(40, 'img_6ae014e47653d.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(41, 'img_6ae014e477b5a.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(42, 'img_6ae014e47923b.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(43, 'img_6ae014e47b0e6.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(44, 'img_6ae014e47c829.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(45, 'img_6ae014e47e863.jpg', 0, 9, 1, '2026-10-27 03:05:08'),
(46, 'img_6ae016467e580.jpg', 0, 10, 1, '2026-10-27 03:11:02'),
(47, 'img_6ae01646807ba.jpg', 0, 10, 1, '2026-10-27 03:11:02'),
(48, 'img_6ae0164682200.jpg', 0, 10, 1, '2026-10-27 03:11:02'),
(49, 'img_6ae016468434c.jpg', 0, 10, 1, '2026-10-27 03:11:02'),
(50, 'img_6ae0164685e4f.jpg', 0, 10, 1, '2026-10-27 03:11:02'),
(51, 'img_6ae016468772d.jpg', 0, 10, 1, '2026-10-27 03:11:02'),
(52, 'img_6ae016468a8e8.jpg', 0, 10, 1, '2026-10-27 03:11:02'),
(53, 'img_6ae016468cfd2.jpg', 0, 10, 1, '2026-10-27 03:11:02'),
(54, 'img_6ae016468e753.jpg', 1, 10, 1, '2026-10-27 03:11:02'),
(55, 'img_6ae01af0b0210.jpg', 1, 11, 1, '2026-10-27 03:30:56'),
(56, 'img_6ae01af0b2fa4.jpg', 0, 11, 1, '2026-10-27 03:30:56'),
(57, 'img_6ae01af0b53a5.jpg', 0, 11, 1, '2026-10-27 03:30:56'),
(58, 'img_6ae01af0b7169.jpg', 0, 11, 1, '2026-10-27 03:30:56'),
(59, 'img_6ae01af0b8ab6.jpg', 0, 11, 1, '2026-10-27 03:30:56'),
(60, 'img_6ae01af0ba130.jpg', 0, 11, 1, '2026-10-27 03:30:56'),
(61, 'img_6ae01af0bcd0d.jpg', 0, 11, 1, '2026-10-27 03:30:56'),
(62, 'img_6ae01af0bf080.jpg', 0, 11, 1, '2026-10-27 03:30:56'),
(63, 'img_6ae01af0c0c3d.jpg', 0, 11, 1, '2026-10-27 03:30:56'),
(64, 'img_6ae01af0c3065.jpg', 0, 11, 1, '2026-10-27 03:30:56'),
(65, 'img_6ae01bf7040e6.jpg', 1, 12, 1, '2026-10-27 03:35:19'),
(66, 'img_6ae01bf706cc9.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(67, 'img_6ae01bf708512.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(68, 'img_6ae01bf70b867.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(69, 'img_6ae01bf70d7ea.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(70, 'img_6ae01bf70ee5f.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(71, 'img_6ae01bf711686.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(72, 'img_6ae01bf713210.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(73, 'img_6ae01bf71596b.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(74, 'img_6ae01bf7172ff.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(75, 'img_6ae01bf718af8.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(76, 'img_6ae01bf71b51d.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(77, 'img_6ae01bf71d28d.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(78, 'img_6ae01bf71e809.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(79, 'img_6ae01bf71fe80.jpg', 0, 12, 1, '2026-10-27 03:35:19'),
(80, 'img_6ae01df682a6f.jpg', 1, 13, 1, '2026-10-27 03:43:50'),
(81, 'img_6ae01df684b48.jpg', 0, 13, 1, '2026-10-27 03:43:50'),
(82, 'img_6ae01df68759e.jpg', 0, 13, 1, '2026-10-27 03:43:50'),
(83, 'img_6ae01fe854823.jpg', 0, 14, 1, '2026-10-27 03:52:08'),
(84, 'img_6ae01fe856769.jpg', 0, 14, 1, '2026-10-27 03:52:08'),
(85, 'img_6ae01fe858ded.jpg', 0, 14, 1, '2026-10-27 03:52:08'),
(86, 'img_6ae01fe85a3d3.jpg', 0, 14, 1, '2026-10-27 03:52:08'),
(87, 'img_6ae01fe85c00d.jpg', 1, 14, 1, '2026-10-27 03:52:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_institucional`
--

CREATE TABLE `info_institucional` (
  `id` int(10) UNSIGNED NOT NULL,
  `presentacion` longtext DEFAULT NULL,
  `mision` longtext DEFAULT NULL,
  `vision` longtext DEFAULT NULL,
  `objetivos` longtext DEFAULT NULL,
  `resena_historica` longtext DEFAULT NULL,
  `perfil_general` longtext DEFAULT NULL,
  `mensaje_director` longtext DEFAULT NULL,
  `foto_director` varchar(255) DEFAULT NULL,
  `actualizado_por` int(10) UNSIGNED NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `info_institucional`
--

INSERT INTO `info_institucional` (`id`, `presentacion`, `mision`, `vision`, `objetivos`, `resena_historica`, `perfil_general`, `mensaje_director`, `foto_director`, `actualizado_por`, `updated_at`) VALUES
(1, '<p><strong style=\"color: rgb(30, 58, 138); background-color: rgb(255, 255, 255);\">¿Por qué estudiar Ingeniería Informática en la UNSXX?</strong></p><p>Estudiar Ingeniería Informática en la Universidad Nacional Siglo XX (UNSXX) ofrece varias ventajas que la convierten en una excelente opción para futuros ingenieros informáticos:</p><ol><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(30, 58, 138);\">Formación Integral y Orientada a la Innovación:</strong><strong><u> </u></strong>La Carrera Ingeniería Informática de la UNSXX ofrece una educación sólida y actualizada en informática, con un enfoque en la tecnología y la innovación. Los estudiantes adquieren no solo conocimientos técnicos, sino también habilidades en análisis, resolución de problemas y desarrollo de software, preparándolos para enfrentar desafíos en diversos sectores.</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(30, 58, 138);\">Docentes Altamente Calificados y Experiencia Práctica:</strong><u style=\"color: rgb(0, 71, 178);\"> </u>La universidad cuenta con docentes capacitados y con experiencia en el campo, brindando una enseñanza de alta calidad. Además, fomenta el aprendizaje práctico a través de proyectos, laboratorios de computación, y prácticas profesionales que permiten a los estudiantes aplicar sus conocimientos en situaciones reales.</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(30, 58, 138);\">Enfoque en el Desarrollo Local e Internacional:</strong><span style=\"color: rgb(30, 58, 138);\"> </span>La Carrera Ingeniería Informática de la UNSXX se distingue por su compromiso con el desarrollo tecnológico tanto a nivel local como global. Los ingenieros informáticos egresados están preparados para atender necesidades específicas de la región, pero también para adaptarse a estándares y desafíos internacionales.</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(30, 58, 138);\">Vinculación con la Industria y el Sector Tecnológico:</strong> La Carrera Ingeniería Informática de la UNSXX promueve vínculos con empresas, instituciones públicas y privadas, lo que facilita el acceso a prácticas profesionales y proyectos colaborativos. Esto permite a los estudiantes crear una red de contactos y familiarizarse con el ambiente profesional antes de graduarse.</li><li data-list=\"ordered\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong style=\"color: rgb(30, 58, 138);\">Oportunidades de Investigación y Especialización:</strong><span style=\"color: rgb(30, 58, 138);\"> </span>La UNSXX ofrece oportunidades de investigación en áreas clave como inteligencia artificial, ciberseguridad, desarrollo de software y desarrollo móvil. Los estudiantes interesados en profundizar en temas específicos tienen la posibilidad de participar en proyectos de investigación y continuar con estudios de posgrado.</li></ol><p>Estudiar Ingeniería Informática en la UNSXX permite formar parte de una comunidad académica comprometida con el desarrollo tecnológico, social y económico de Bolivia y de la región.</p>', '<p>Formar profesionales informáticos orgánicos idóneos con vocación revolucionaria para resolver problemas tecnológicos, coadyuvando en la solución de problemas económicos, políticos y sociales desde el ámbito de las Ciencias de la Computación con menciones de seguridad informática y computación móvil, capaces de emprender, liderar, proponer, desarrollar e innovar soluciones creativas y evaluar los resultados en estas áreas por medio de la integración de los componentes académico, investigativo y de extensión basados en fundamentos filosóficos, psicológicos, pedagógicos y curriculares contemporáneos que correspondan con las perspectivas de desarrollo del país.</p>', '<p>Ser un centro de formación profesional líder en ingeniería informática de alto nivel académico, tecnológico y científico, constituyéndose en el pilar fundamental para el desarrollo de la ciencia y tecnología a nivel regional, nacional e internacional, promoviendo proyectos de investigación y de extensión.</p>', '<p>Formar profesionales orgánicos con capacidad científica, que logren implementar soluciones y aplicaciones tecnológicas en las diferentes áreas de desarrollo de la región y el país.</p>', '<p>El año 1996 a través de un sondeo de opinión, diagnóstico y cursos de orientación vocacional en las poblaciones de Llallagua, Siglo XX y Catavi (departamento de Potosí), se logró detectar un creciente interés de la población por la creación de la carrera Ingeniería Informática. En este sentido el primer intento de fundación de esta carrera, fue en esa gestión, a través de una preinscripción en el área de Tecnología. La gestión 1997, se logró inscribir más de un centenar de personas de la región y del interior del país, demostrándose nuevamente el entusiasmo por proseguir estudios en esta área. A pesar de los esfuerzos realizados, no fue posible poner en funcionamiento esta carrera por diferentes factores institucionales.</p><p><br></p><p><strong><em>La Carrera Ingeniería Informática empieza su funcionamiento oficialmente un 4 de julio de 1998 según resolución del H. C. U. Nº 013/98</em></strong>, bajo el mandato rectoral del Ing. Juan Domingo Rueda Characayo, y dirección de Carrera a cargo del Ing. Miguel Gallegos, teniendo como asiento oficial el Campus María Barzola (Edificio Ex Colegio Junín). Con el transcurso de los años, la carrera Ingeniería Informática de la UNSXX, se ha constituido en el referente principal del desarrollo tecnológico de la región, además de manifestarse firmes en su política de servicio, apoyo y defensa de los sectores más oprimidos de la sociedad, buscando atender a sus necesidades y coadyuvar al mejoramiento regional.</p><p><br></p><p><strong><em>El 6 de Diciembre de 2024, la Universidad Nacional Siglo XX celebró la acreditación de la Carrera de Ingeniería Informática</em></strong>, destacada por su alto puntaje y el cumplimiento de los estándares establecidos en el reglamento de evaluación y acreditación universitaria. En un evento académico que contó con la participación de pares evaluadores de las universidades Juan Misael Saracho de Tarija, José Ballivián de Beni, la Universidad del Alto y San Francisco Xavier de Chuquisaca, junto a representantes de la Secretaría de Evaluación y Acreditación del Comité Ejecutivo de la Universidad Boliviana (CEUB), se confirma la acreditación de la Carrera de Ingeniería Informática de la Universidad Nacional Siglo XX.</p><p>El MSc. Ángel Rojas Valdés, coordinador técnico de la Secretaría de Evaluación y Acreditación, presentó el informe técnico, destacando las fortalezas y oportunidades de la carrera, así como las debilidades y recomendaciones para su mejora continua. <strong><em>Según el artículo 43 del Reglamento General de Acreditación de Carreras y Programas, la carrera obtuvo una calificación de 88,43 puntos</em></strong>, posicionándose en condiciones óptimas para la acreditación.</p>', '<h3><strong style=\"background-color: rgb(255, 255, 255); color: rgb(30, 58, 138);\">Perfil del Ingeniero Informático.</strong></h3><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">El profesional ingeniero informático se desenvuelve en un entorno en constante evolución que son las tecnologías de la información y comunicación, el titulado debe ser una persona capaz de adaptarse a tecnologías emergentes y entornos cambiantes.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">El profesional en ingeniería informática es capaz de innovar y generar nuevas soluciones, en base a sus conocimientos en matemática, computación y gestión desarrollando la disciplina mediante la ciencia e investigación, individualmente o participando en equipos multidisciplinarios.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">El profesional en Informática es capaz de planificar, gestionar y liderar proyectos en el área de ciencias de la computación, con conocimiento actualizado del mercado, así como del código ético profesional y los aspectos legales en el entorno de las tecnologías de la información.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">El profesional en Informática es capaz de proponer, diseñar, desarrollar y administrar, sistemas informáticos de pequeñas empresas como a grandes organizaciones y personas particulares.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">El profesional en Informática será capaz de realizar trabajos de asesoría, auditoria y seguridad de la información.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Es capaz de emprender proyectos innovadores en base a problemas de la realidad y del avance tecnológico.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">El profesional en informática será capaz de elaborar estrategias de solución para la toma de decisiones tomando en cuenta el enfoque sistémico.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Formular propuestas para la solución de problemas reales mediante el uso de tecnologías y las matemáticas aplicadas.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">El profesional es capaz de integrar todos los conocimientos y procedimientos del funcionamiento de todo tipo de ordenador.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Diseña, implementa y administra redes de computadoras LAN y WAN.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">El profesional es capaz de diseñar e implementar sistemas inteligentes artificiales.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Desarrolla sistemas y aplicaciones para entorno web.</span></li></ol><h3><strong style=\"background-color: rgb(255, 255, 255); color: rgb(30, 58, 138);\">Perfil del Técnico Superior Universitario en Análisis de Sistemas Informáticos.</strong></h3><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Modelado y gestión de sistemas de base de datos.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Actuar en equipos de desarrollo de software para las más diversas áreas, con especial atención en la calidad de productos desarrollados.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Participar en actividades relacionadas con el análisis, diseño e implementación de sistemas informáticos.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Desarrollar y diseñar programas aplicando los distintos lenguajes de programación requeridos por el mercado laboral.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Relevar, analizar, implementar, adaptar y controlar con eficiencia y habilidad técnica, la programación de los sistemas informáticos.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Crear, programar y mantener sistemas informáticos y redes informáticas.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Participar en la evaluación e instalación de sistemas operativos.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Desarrolla sistemas y aplicaciones para entorno web.</span></li></ol><h3><strong style=\"background-color: rgb(255, 255, 255); color: rgb(30, 58, 138);\">Perfil del Técnico Medio Universitario en Programación de Sistemas Informáticos.</strong></h3><ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Desarrollo de sistemas y aplicaciones simples para propósito específico.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Desarrollo de sistemas contables, gerenciales y de información con el uso de base de datos y lenguajes de programación de última generación.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Apoyo en el desarrollo de proyectos de todo tipo con el uso de herramientas matemáticas.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Manejo de Tecnologías de Información y Comunicación en el desarrollo de software.</span></li></ol><p><br></p>', '<p>Es un honor darles la más cordial bienvenida al sitio web institucional de la Carrera de Ingeniería Informática de la Universidad Nacional “Siglo XX”.</p><p>Este espacio constituye una ventana al trabajo académico, científico e institucional de nuestra Carrera, donde compartimos información sobre nuestra oferta académica, actividades, proyectos de investigación, actividades de extensión y los logros de nuestra comunidad universitaria.</p><p>Asumimos el compromiso de formar profesionales íntegros, competentes e innovadores, capaces de responder a los desafíos tecnológicos de nuestra sociedad, contribuyendo al desarrollo regional y nacional mediante el conocimiento, la investigación y la aplicación responsable de la tecnología.</p><p>Trabajamos continuamente para fortalecer la calidad académica, promover el pensamiento crítico, impulsar la innovación y consolidar una formación integral que prepare a nuestros estudiantes para afrontar con éxito los retos del mundo profesional.</p><p>Invito a nuestros estudiantes, docentes, graduados y visitantes a recorrer este espacio, conocer nuestras actividades y ser parte activa del crecimiento y fortalecimiento de nuestra Carrera.</p><p><strong><em>¡Bienvenidos a la Carrera de Ingeniería Informática de la Universidad Nacional “Siglo XX”!</em></strong></p><p>Con compromiso y visión de futuro,</p><p><br></p><p><em style=\"color: rgb(13, 148, 136);\">M.Sc. Ing. Jhillma M. Portanda Zurita</em></p><p><em style=\"color: rgb(13, 148, 136);\">Directora de Carrera - Ing. Informática U.N.S.XX</em></p>', NULL, 1, '2026-10-24 23:06:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorio`
--

CREATE TABLE `laboratorio` (
  `id_lab` int(10) UNSIGNED NOT NULL,
  `nombre_lab` varchar(100) NOT NULL COMMENT 'Nombre canónico del laboratorio',
  `resp_acad_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK → academico.id_acad (responsable académico)',
  `horario_lab` varchar(255) DEFAULT NULL COMMENT 'UUID filename PDF horario (RF4)',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `creado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `actualizado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Laboratorios de la carrera (Seguridad, Móvil, Hardware, Software I/II/III)';

--
-- Volcado de datos para la tabla `laboratorio`
--

INSERT INTO `laboratorio` (`id_lab`, `nombre_lab`, `resp_acad_id`, `horario_lab`, `activo`, `creado_por`, `actualizado_por`, `actualizado_en`) VALUES
(2, 'Computación Móvil', 46, 'doc_6ac9a84546f2b.pdf', 1, NULL, 1, '2026-10-27 02:09:51'),
(7, 'Seguridad Informática', 18, 'doc_6ad9203597931.pdf', 1, NULL, 1, '2026-10-23 03:24:49'),
(13, 'Software I', 18, 'doc_6add22e0936d5.pdf', 1, 1, 1, '2026-10-27 02:14:10'),
(14, 'Software II', 46, 'doc_6add230d33201.pdf', 1, 1, 1, '2026-10-27 02:14:43'),
(15, 'Hardware', 47, 'doc_6ae0082795db4.pdf', 1, 1, 1, '2026-10-27 02:10:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorio_galeria`
--

CREATE TABLE `laboratorio_galeria` (
  `id_foto` int(10) UNSIGNED NOT NULL,
  `id_lab` int(10) UNSIGNED NOT NULL COMMENT 'Llave foránea al laboratorio',
  `archivo_url` varchar(255) NOT NULL COMMENT 'Nombre del archivo UUID',
  `es_portada` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 si es la foto principal, 0 si es secundaria',
  `subido_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `subido_el` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `laboratorio_galeria`
--

INSERT INTO `laboratorio_galeria` (`id_foto`, `id_lab`, `archivo_url`, `es_portada`, `subido_por`, `subido_el`) VALUES
(26, 15, 'img_6add234dd52c1.jpg', 1, 1, '2026-10-24 21:29:49'),
(39, 15, 'img_6ae00887a8448.jpg', 0, 1, '2026-10-27 02:12:23'),
(40, 15, 'img_6ae00887ae17d.jpg', 0, 1, '2026-10-27 02:12:23'),
(41, 15, 'img_6ae00887b2ef6.jpg', 0, 1, '2026-10-27 02:12:23'),
(42, 15, 'img_6ae00887b4fb8.jpg', 0, 1, '2026-10-27 02:12:23'),
(43, 15, 'img_6ae00887b6092.jpg', 0, 1, '2026-10-27 02:12:23'),
(44, 15, 'img_6ae00887b70e5.jpg', 0, 1, '2026-10-27 02:12:23'),
(45, 15, 'img_6ae00887b8dc0.jpg', 0, 1, '2026-10-27 02:12:23'),
(46, 15, 'img_6ae00887b9fc9.jpg', 0, 1, '2026-10-27 02:12:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logro`
--

CREATE TABLE `logro` (
  `id_log` int(10) UNSIGNED NOT NULL,
  `posicion` enum('1er Lugar','2do Lugar','3er Lugar','Reconocimiento','Mención Honorífica','Otro') NOT NULL DEFAULT '1er Lugar',
  `nom_log` varchar(300) NOT NULL,
  `categoria_logro` varchar(150) DEFAULT NULL COMMENT 'Ej: Categoría Profesional, Categoría Estudiantes',
  `lugar_log` varchar(150) DEFAULT NULL,
  `foto_logro` varchar(255) DEFAULT NULL COMMENT 'UUID filename de la imagen',
  `fech_log` date NOT NULL COMMENT 'Corregido: era VARCHAR(50)',
  `registrado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK → usuario.usu_id (auditoría)',
  `actualizado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `activo` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Visible en la línea de tiempo',
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Logros y reconocimientos académicos/deportivos';

--
-- Volcado de datos para la tabla `logro`
--

INSERT INTO `logro` (`id_log`, `posicion`, `nom_log`, `categoria_logro`, `lugar_log`, `foto_logro`, `fech_log`, `registrado_por`, `actualizado_por`, `activo`, `actualizado_en`) VALUES
(1, '1er Lugar', 'CCBOL (Congreso en Ciencias de la Computación)', NULL, 'Potosí, Bolivia', NULL, '2006-01-01', 1, NULL, 1, '2026-10-21 02:57:14'),
(2, '3er Lugar', 'CCBOL (Congreso en Ciencias de la Computación)', NULL, 'Sucre, Bolivia', NULL, '2008-01-01', 1, NULL, 1, '2026-10-24 21:42:20'),
(4, '1er Lugar', 'CCBOL (Congreso en Ciencias de la Computación)', NULL, 'La Paz, Bolivia', NULL, '2010-01-01', 1, NULL, 1, '2026-10-24 21:43:24'),
(5, '2do Lugar', 'CONASOL (Congreso Nacional de Software Libre)', NULL, 'La Paz, Bolivia', NULL, '2011-01-01', 1, NULL, 1, '2026-10-24 21:44:16'),
(6, '2do Lugar', 'CCBOL (Congreso en Ciencias de la Computación)', NULL, 'Santa Cruz, Bolivia', NULL, '2012-01-01', 1, NULL, 1, '2026-10-27 02:23:14'),
(7, '1er Lugar', 'JICCBOL (Jornadas de Investigación en Ciencias de la Computación y Telecomunicaciones de Bolivia)', 'Categoría Profesional', 'Potosí, Bolivia', NULL, '2013-01-01', 1, NULL, 1, '2026-10-27 02:25:06'),
(8, '1er Lugar', 'JICCBOL (Jornadas de Investigación en Ciencias de la Computación y Telecomunicaciones de Bolivia)', 'Categoría Estudiantes', 'Potosí, Bolivia', NULL, '2013-01-01', 1, NULL, 1, '2026-10-27 02:25:47'),
(9, '2do Lugar', 'JICCBOL (Jornadas de Investigación en Ciencias de la Computación y Telecomunicaciones de Bolivia)', 'Categoría Estudiantes', 'Pando, Bolivia', NULL, '2014-01-01', 1, NULL, 1, '2026-10-27 02:26:27'),
(10, '3er Lugar', 'Día de la Robótica UPB', NULL, 'Cochabamba, Bolivia', NULL, '2015-01-01', 1, NULL, 1, '2026-10-27 02:27:24'),
(11, '1er Lugar', 'Feria de Ciencias y Tecnología U.N.S.XX', NULL, 'Llallagua, Potosí, Bolivia', NULL, '2011-01-01', 1, NULL, 1, '2026-10-27 02:29:01'),
(12, '1er Lugar', 'Feria de Ciencias y Tecnología U.N.S.XX', NULL, 'Llallagua, Potosí, Bolivia', NULL, '2012-01-01', 1, NULL, 1, '2026-10-27 02:30:00'),
(13, '1er Lugar', 'Feria de Ciencias y Tecnología U.N.S.XX', NULL, 'Llallagua, Potosí, Bolivia', NULL, '2014-01-01', 1, NULL, 1, '2026-10-27 02:30:21'),
(14, '3er Lugar', 'CCBOL (Congreso en Ciencias de la Computación)', NULL, 'La Paz, Bolivia', NULL, '2016-01-01', 1, NULL, 1, '2026-10-27 02:31:32'),
(15, '2do Lugar', 'CCBOL (Congreso en Ciencias de la Computación)', NULL, 'Pando, Bolivia', NULL, '2018-01-01', 1, NULL, 1, '2026-10-27 02:31:31'),
(16, '3er Lugar', 'Torneo Universitario Internacional TUAPP', NULL, 'Huatusco, Veracruz, México', 'img_6ae0a78a439de.png', '2021-01-01', 1, 1, 1, '2026-10-27 13:31:20'),
(17, '1er Lugar', 'CCBOL (Congreso en Ciencias de la Computación)', 'Categoría Profesional', 'Oruro, Bolivia', 'img_6ae01d50247df.jpg', '2025-01-01', 1, NULL, 1, '2026-10-27 03:52:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mencion_instituto`
--

CREATE TABLE `mencion_instituto` (
  `id_men` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `perfil_profesional` longtext DEFAULT NULL,
  `coordinador` int(11) UNSIGNED DEFAULT NULL,
  `escudo_url` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `creado_por` int(10) UNSIGNED NOT NULL,
  `actualizado_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `actualizado_en` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mencion_instituto`
--

INSERT INTO `mencion_instituto` (`id_men`, `nombre`, `perfil_profesional`, `coordinador`, `escudo_url`, `estado`, `creado_por`, `actualizado_por`, `actualizado_en`) VALUES
(1, 'Computación Móvil', '<ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Desarrolla aplicaciones para dispositivos móviles.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Desarrolla videojuegos.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Gestiona y administra redes móviles y almacenamiento de datos.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Desarrolla aplicaciones para SIG en entornos móviles.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Desarrolla aplicaciones de realidad virtual y aumentada.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Desarrolla aplicaciones para internet de las cosas.</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span>Desarrolla aplicaciones móviles basadas en visión artificial.</li></ol>', 46, 'img_6acc723c5dea3.png', 1, 1, 1, '2026-10-27 02:08:42'),
(2, 'Seguridad Informática', '<ol><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Diseña e implementa sistemas de criptografía y esteganografía, para dotar seguridad a las comunicaciones y la información.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Mantiene la Integridad, disponibilidad, privacidad, control y autenticidad de la información.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Aplica técnicas de protección frente a ataques y amenazas en los sistemas operativos, las redes, el software de aplicación, los sistemas Web y las bases de datos.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Aplica diferentes métodos, técnicas y herramientas para la prevención y detección de intrusiones para la seguridad en la web.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Aplica técnicas, métodos y herramientas de la informática forense para la obtención de evidencia digital.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Realiza auditorias informáticas aplicando estándares y normas establecidas.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Será un líder con capacidad crítica y ética en el manejo de los aspectos legales de la seguridad informática.</span></li><li data-list=\"bullet\" class=\"ql-align-justify\"><span class=\"ql-ui\" contenteditable=\"false\"></span><span style=\"background-color: rgb(255, 255, 255); color: rgb(68, 68, 68);\">Diseña políticas de seguridad informática en interconexión de redes de comunicación y transmisión de datos.</span></li></ol>', 18, 'men_6acc71b57df96.png', 1, 1, 1, '2026-10-26 18:20:44'),
(3, 'IIDAI', '<p>El IIDAI es un centro de investigación y desarrollo de aplicaciones informáticas con técnicas avanzadas de la informática con la misión de promover y apoyar el buen uso de la última tecnología informática y servir como puente entre lo académico y los distintos sectores sociales de nuestra región y país.</p><p>El IIDAI tiene como principal objetivo actuar como núcleo de investigación desarrollo e innovación de la sociedad de la información actuando como germen en la introducción de estas nuevas tecnologías en todos los ámbitos y el mantenimiento de la posición competitiva de las empresas del sector informático mediante la prestación de un servicio de investigación y desarrollo tecnológico, así como de los procedimientos que lleven a la innovación.</p><p><br></p><h3><strong>UBICACION:</strong></h3><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>Oficina Central: </strong>Llallagua calle Oruro s/n edificio de Ciencia y Tecnología, primer piso Oficina Nº 1</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>Oficina Campus Universitario María Barzola:</strong>&nbsp;Edificio Administrativo&nbsp;“Bloque A” Ingeniería Informática, Oficina Nº 3</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>Email:</strong> <a href=\"mailto:iidai.informatica.unsxx@gmail.com\" rel=\"noopener noreferrer\" target=\"_blank\">iidai.informatica.unsxx@gmail.com</a>&nbsp;&nbsp;</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>Teléfono:</strong> (+591) - 75418754</li></ol><p><br></p><p><em style=\"color: rgb(13, 148, 136);\">Llallagua - Bolivia</em></p><p><br></p>', 22, 'men_6acc726a62947.png', 1, 1, 1, '2026-10-27 05:35:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `portada_inicio`
--

CREATE TABLE `portada_inicio` (
  `id_portada` int(10) UNSIGNED NOT NULL,
  `archivo_url` varchar(255) NOT NULL COMMENT 'UUID filename de la imagen',
  `titulo` varchar(255) DEFAULT NULL COMMENT 'Texto opcional para la imagen',
  `orden` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Orden de aparición en el carrusel',
  `activo` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = visible en la web, 0 = oculto',
  `subido_por` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK -> usuario.usu_id',
  `subido_el` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `portada_inicio`
--

INSERT INTO `portada_inicio` (`id_portada`, `archivo_url`, `titulo`, `orden`, `activo`, `subido_por`, `subido_el`) VALUES
(7, 'img_6adfc92aa6c75.jpg', 'Edificio Bloque A', 4, 1, 1, '2026-10-26 21:42:02'),
(8, 'img_6adfc92aa8cfe.jpg', 'Infraestructura Moderna', 6, 1, 1, '2026-10-26 21:42:02'),
(10, 'img_6adfc92aac0e4.jpg', 'Acreditada al CEUB', 2, 1, 1, '2026-10-26 21:42:02'),
(11, 'img_6adfc92aae732.jpg', 'Edificio Bloque B', 5, 1, 1, '2026-10-26 21:42:02'),
(12, 'img_6adfc92aafb42.jpg', 'Edificio Bloque B', 3, 1, 1, '2026-10-26 21:42:02'),
(13, 'img_6adfc92ab1ecd.jpg', 'Ambientes cómodos y modernos', 1, 1, 1, '2026-10-26 21:42:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `rol_id` tinyint(3) UNSIGNED NOT NULL,
  `rol_nombre` varchar(50) NOT NULL,
  `rol_descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de roles del sistema (RBAC)';

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rol_id`, `rol_nombre`, `rol_descripcion`) VALUES
(1, 'admin', 'Acceso total al sistema: gestión de usuarios, roles, y todos los módulos'),
(2, 'docente', 'Acceso a módulos académicos: perfil, laboratorios asignados, comunicados'),
(3, 'editor', 'Gestión de contenido: comunicados, extensión, admisión, inicio'),
(4, 'coordinador', 'Gestión de mención/laboratorio: responsables, horarios, plantel docente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion_enlace`
--

CREATE TABLE `ubicacion_enlace` (
  `id_enl` int(10) UNSIGNED NOT NULL,
  `ubicacion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ubicacion_enlace`
--

INSERT INTO `ubicacion_enlace` (`id_enl`, `ubicacion`) VALUES
(1, 'footer_enlaces'),
(1, 'menu_investigacion'),
(2, 'footer_redes'),
(6, 'footer_redes'),
(7, 'footer_enlaces'),
(7, 'menu_servicios'),
(8, 'footer_enlaces'),
(8, 'menu_servicios'),
(9, 'footer_enlaces');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usu_id` int(10) UNSIGNED NOT NULL,
  `usu_nom` varchar(150) NOT NULL,
  `usu_correo` varchar(150) NOT NULL,
  `usu_cel` varchar(20) DEFAULT NULL COMMENT 'Celular del usuario para envío de credenciales por WhatsApp',
  `usu_pass` varchar(255) NOT NULL COMMENT 'Hash bcrypt/argon2 (mínimo 60 chars)',
  `modulos` text DEFAULT NULL COMMENT 'JSON array de módulos permitidos, ej: ["comunicados","eventos"]',
  `estado` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=activo, 0=inactivo',
  `solicitud_reset` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=Solicitó restaurar clave desde el login',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Usuarios del sistema (auth + perfil base)';

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usu_id`, `usu_nom`, `usu_correo`, `usu_cel`, `usu_pass`, `modulos`, `estado`, `solicitud_reset`, `creado_en`, `actualizado_en`) VALUES
(1, 'OPTIMUS INFOR', 'contactos@informatica-unsxx.net', '67264027', '$2y$10$jMTU/KBHCUfItq54KTUcGuRQ98TctkyeCW/8wl/4sg8SSv9mPCMOq', '[]', 1, 0, '2026-10-04 23:18:26', '2026-10-23 03:58:43'),
(10, 'M.Sc. Ing. Jorge Villcaez Castillo', 'jorgevill2015@gmail.com', '77148375', '$2y$10$4jXuISpZbWpISl1./4.kc.RZ0nCQ.0dyV4mk7a9WmKJwKoTR8AQgO', NULL, 0, 0, '2026-10-21 04:26:08', '2026-10-27 02:09:51'),
(11, 'Ing. David Cazorla Valdivia', 'davidcv.dev@gmail.com', '74116315', '$2y$10$EbDbT4BPid7WPw7ngI.qKudrEYgwb8lFhr7CRpCK73XKPYfEov4UO', '[]', 1, 0, '2026-10-21 04:26:08', '2026-10-23 03:24:49'),
(12, 'Ing. Fernando Quinteros Coca', 'fquinteroscoca@gmail.com', '67264027', '$2y$10$PW.pfoq6MVaFyp6pFmgxzOWfvYgEypJbKUCVzK8i7pWh2FsVReuCq', '[\"laboratories\",\"communications\",\"extensions\"]', 1, 0, '2026-10-21 04:38:13', '2026-10-23 01:58:32'),
(15, 'Ing. Mary Lovera Copa', 'Mar_lc@hotmail.com', '72318145', '$2y$10$9LfKB227HcKx5f/NwJKOQeWOcmgw3V3rAkxJAmFrkFx28M2I0excG', '[]', 0, 0, '2026-10-21 05:04:14', '2026-10-23 03:25:05'),
(24, 'Ing. Lenny Mendoza Gutierrez', 'leny@gmail.com', '71234567', '$2y$10$1.upmtidTggDLCAHQZxL1u1pCE4xqpeaO6/iZb9GXpbMmaLQeoqeS', '[]', 0, 0, '2026-10-23 01:55:01', '2026-10-23 03:24:49'),
(29, 'M.Sc. Ing. Freddy Rocabado Ibañez ', 'rocosfree@gmail.com', '68294945', '$2y$10$czHZp6WARKu4hAQWBM6DievVgyc71lfJs148AJZJU.czITyKD2PAG', NULL, 0, 0, '2026-10-24 21:28:45', '2026-10-27 02:14:43'),
(30, 'M.Sc. Ing. Fernando Miranda Choque', 'fernando@gmail.com', '67237857', '$2y$10$u5YMIz4hySOEzq5f5thQDOnrSW9pD/nsq2XGrGtcJMTqRHH/pQeYy', NULL, 0, 0, '2026-10-24 21:29:24', '2026-10-27 02:10:47'),
(31, 'M.Sc. Ing. Nelson Ayaviri Padilla', 'nelsonayavirip@gmail.com', '75402249', '$2y$10$BDLSlNKHM.LNrknwUKvqneTl1NkxVFTefIgOg2xUxTqylqviyrcmG', NULL, 1, 0, '2026-10-27 02:09:51', '2026-10-27 02:09:51'),
(32, 'M.Sc. Elizabeth Mejía García', 'elymej15@gmail.com', '71180129', '$2y$10$2HOzJQOhqvA3Ce2nktkBCu4CsUf4uPyUFzVwbeueQlkNBFlR/w7Xa', NULL, 1, 0, '2026-10-27 02:10:47', '2026-10-27 02:10:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_rol`
--

CREATE TABLE `usuario_rol` (
  `usu_id` int(10) UNSIGNED NOT NULL,
  `rol_id` tinyint(3) UNSIGNED NOT NULL,
  `asignado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Asignación de roles a usuarios (RBAC)';

--
-- Volcado de datos para la tabla `usuario_rol`
--

INSERT INTO `usuario_rol` (`usu_id`, `rol_id`, `asignado_en`) VALUES
(1, 1, '2026-10-23 03:58:43'),
(10, 2, '2026-10-21 04:26:08'),
(11, 2, '2026-10-23 01:54:29'),
(12, 2, '2026-10-23 01:54:07'),
(15, 2, '2026-10-23 03:25:05'),
(24, 2, '2026-10-23 01:57:28'),
(29, 2, '2026-10-24 21:28:45'),
(30, 2, '2026-10-24 21:29:24'),
(31, 2, '2026-10-27 02:09:51'),
(32, 2, '2026-10-27 02:10:47');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `academico`
--
ALTER TABLE `academico`
  ADD PRIMARY KEY (`id_acad`),
  ADD UNIQUE KEY `uk_academico_email` (`email_acad`),
  ADD KEY `idx_academico_cat` (`cat_acad`),
  ADD KEY `idx_academico_usu` (`usu_id`),
  ADD KEY `fk_academico_creado_por` (`creado_por`),
  ADD KEY `fk_academico_actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `admision`
--
ALTER TABLE `admision`
  ADD PRIMARY KEY (`id_adm`),
  ADD KEY `idx_admision_tipo` (`tipo`),
  ADD KEY `idx_admision_actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `auditoria_eliminacion`
--
ALTER TABLE `auditoria_eliminacion`
  ADD PRIMARY KEY (`id_aud`),
  ADD KEY `idx_aud_elim_usuario` (`eliminado_por`),
  ADD KEY `idx_aud_elim_fecha` (`eliminado_en`);

--
-- Indices de la tabla `categoria_academico`
--
ALTER TABLE `categoria_academico`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `uk_cat_nombre` (`cat_nombre`);

--
-- Indices de la tabla `categoria_extension`
--
ALTER TABLE `categoria_extension`
  ADD PRIMARY KEY (`id_cat_ext`),
  ADD UNIQUE KEY `uk_nom_cat` (`nom_cat`),
  ADD UNIQUE KEY `uk_slug_cat` (`slug_cat`);

--
-- Indices de la tabla `celular`
--
ALTER TABLE `celular`
  ADD PRIMARY KEY (`id_cel`),
  ADD KEY `fk_celular_actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `comunicado`
--
ALTER TABLE `comunicado`
  ADD PRIMARY KEY (`id_com`),
  ADD KEY `idx_comunicado_fecha` (`fech_com`),
  ADD KEY `idx_comunicado_publicado_por` (`publicado_por`),
  ADD KEY `fk_comunicado_actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `documento_oficial`
--
ALTER TABLE `documento_oficial`
  ADD PRIMARY KEY (`id_doc`),
  ADD KEY `subido_por` (`subido_por`),
  ADD KEY `fk_documento_actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `enlace_servicio`
--
ALTER TABLE `enlace_servicio`
  ADD PRIMARY KEY (`id_enl`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `extension`
--
ALTER TABLE `extension`
  ADD PRIMARY KEY (`id_ext`),
  ADD KEY `idx_extension_creado_por` (`creado_por`),
  ADD KEY `fk_extension_categoria` (`id_cat_ext`),
  ADD KEY `fk_extension_actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `foto_extension`
--
ALTER TABLE `foto_extension`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `idx_foto_extension_ext` (`id_ext`),
  ADD KEY `idx_foto_extension_subido_por` (`subido_por`);

--
-- Indices de la tabla `info_institucional`
--
ALTER TABLE `info_institucional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD PRIMARY KEY (`id_lab`),
  ADD UNIQUE KEY `uk_laboratorio_nombre` (`nombre_lab`),
  ADD KEY `idx_laboratorio_resp_acad` (`resp_acad_id`),
  ADD KEY `fk_laboratorio_creado_por` (`creado_por`),
  ADD KEY `fk_laboratorio_actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `laboratorio_galeria`
--
ALTER TABLE `laboratorio_galeria`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `fk_laboratorio_galeria` (`id_lab`),
  ADD KEY `fk_lab_galeria_subido_por` (`subido_por`);

--
-- Indices de la tabla `logro`
--
ALTER TABLE `logro`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_logro_fecha` (`fech_log`),
  ADD KEY `idx_logro_registrado_por` (`registrado_por`),
  ADD KEY `fk_logro_actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `mencion_instituto`
--
ALTER TABLE `mencion_instituto`
  ADD PRIMARY KEY (`id_men`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `fk_mencion_coordinador` (`coordinador`),
  ADD KEY `fk_mencion_actualizado_por` (`actualizado_por`);

--
-- Indices de la tabla `portada_inicio`
--
ALTER TABLE `portada_inicio`
  ADD PRIMARY KEY (`id_portada`),
  ADD KEY `fk_portada_subido_por` (`subido_por`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rol_id`),
  ADD UNIQUE KEY `uk_rol_nombre` (`rol_nombre`);

--
-- Indices de la tabla `ubicacion_enlace`
--
ALTER TABLE `ubicacion_enlace`
  ADD PRIMARY KEY (`id_enl`,`ubicacion`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usu_id`),
  ADD UNIQUE KEY `uk_usuario_correo` (`usu_correo`);

--
-- Indices de la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD PRIMARY KEY (`usu_id`,`rol_id`),
  ADD KEY `idx_usuario_rol_rol` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `academico`
--
ALTER TABLE `academico`
  MODIFY `id_acad` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `admision`
--
ALTER TABLE `admision`
  MODIFY `id_adm` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `auditoria_eliminacion`
--
ALTER TABLE `auditoria_eliminacion`
  MODIFY `id_aud` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `categoria_academico`
--
ALTER TABLE `categoria_academico`
  MODIFY `cat_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `categoria_extension`
--
ALTER TABLE `categoria_extension`
  MODIFY `id_cat_ext` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `celular`
--
ALTER TABLE `celular`
  MODIFY `id_cel` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `comunicado`
--
ALTER TABLE `comunicado`
  MODIFY `id_com` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `documento_oficial`
--
ALTER TABLE `documento_oficial`
  MODIFY `id_doc` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `enlace_servicio`
--
ALTER TABLE `enlace_servicio`
  MODIFY `id_enl` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `extension`
--
ALTER TABLE `extension`
  MODIFY `id_ext` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `foto_extension`
--
ALTER TABLE `foto_extension`
  MODIFY `id_foto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de la tabla `info_institucional`
--
ALTER TABLE `info_institucional`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  MODIFY `id_lab` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `laboratorio_galeria`
--
ALTER TABLE `laboratorio_galeria`
  MODIFY `id_foto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `logro`
--
ALTER TABLE `logro`
  MODIFY `id_log` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `mencion_instituto`
--
ALTER TABLE `mencion_instituto`
  MODIFY `id_men` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `portada_inicio`
--
ALTER TABLE `portada_inicio`
  MODIFY `id_portada` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `rol_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usu_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `academico`
--
ALTER TABLE `academico`
  ADD CONSTRAINT `fk_academico_actualizado_por` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_academico_categoria` FOREIGN KEY (`cat_acad`) REFERENCES `categoria_academico` (`cat_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_academico_creado_por` FOREIGN KEY (`creado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_academico_usuario` FOREIGN KEY (`usu_id`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `admision`
--
ALTER TABLE `admision`
  ADD CONSTRAINT `fk_admision_actualizado_por` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `auditoria_eliminacion`
--
ALTER TABLE `auditoria_eliminacion`
  ADD CONSTRAINT `fk_aud_elim_usuario` FOREIGN KEY (`eliminado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `celular`
--
ALTER TABLE `celular`
  ADD CONSTRAINT `fk_celular_actualizado_por` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `comunicado`
--
ALTER TABLE `comunicado`
  ADD CONSTRAINT `fk_comunicado_actualizado_por` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comunicado_publicado_por` FOREIGN KEY (`publicado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `documento_oficial`
--
ALTER TABLE `documento_oficial`
  ADD CONSTRAINT `documento_oficial_ibfk_1` FOREIGN KEY (`subido_por`) REFERENCES `usuario` (`usu_id`),
  ADD CONSTRAINT `fk_documento_actualizado_por` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `enlace_servicio`
--
ALTER TABLE `enlace_servicio`
  ADD CONSTRAINT `enlace_servicio_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuario` (`usu_id`);

--
-- Filtros para la tabla `extension`
--
ALTER TABLE `extension`
  ADD CONSTRAINT `fk_extension_actualizado_por` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_extension_categoria` FOREIGN KEY (`id_cat_ext`) REFERENCES `categoria_extension` (`id_cat_ext`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_extension_creado_por` FOREIGN KEY (`creado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `foto_extension`
--
ALTER TABLE `foto_extension`
  ADD CONSTRAINT `fk_foto_extension_extension` FOREIGN KEY (`id_ext`) REFERENCES `extension` (`id_ext`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_foto_extension_subido_por` FOREIGN KEY (`subido_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `info_institucional`
--
ALTER TABLE `info_institucional`
  ADD CONSTRAINT `info_institucional_ibfk_1` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`);

--
-- Filtros para la tabla `laboratorio`
--
ALTER TABLE `laboratorio`
  ADD CONSTRAINT `fk_laboratorio_actualizado_por` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_laboratorio_creado_por` FOREIGN KEY (`creado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_laboratorio_resp_academico` FOREIGN KEY (`resp_acad_id`) REFERENCES `academico` (`id_acad`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `laboratorio_galeria`
--
ALTER TABLE `laboratorio_galeria`
  ADD CONSTRAINT `fk_lab_galeria_subido_por` FOREIGN KEY (`subido_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_laboratorio_galeria` FOREIGN KEY (`id_lab`) REFERENCES `laboratorio` (`id_lab`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `logro`
--
ALTER TABLE `logro`
  ADD CONSTRAINT `fk_logro_actualizado_por` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_logro_registrado_por` FOREIGN KEY (`registrado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `mencion_instituto`
--
ALTER TABLE `mencion_instituto`
  ADD CONSTRAINT `fk_mencion_actualizado_por` FOREIGN KEY (`actualizado_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mencion_coordinador` FOREIGN KEY (`coordinador`) REFERENCES `academico` (`id_acad`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `mencion_instituto_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuario` (`usu_id`);

--
-- Filtros para la tabla `portada_inicio`
--
ALTER TABLE `portada_inicio`
  ADD CONSTRAINT `fk_portada_subido_por` FOREIGN KEY (`subido_por`) REFERENCES `usuario` (`usu_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `ubicacion_enlace`
--
ALTER TABLE `ubicacion_enlace`
  ADD CONSTRAINT `fk_ubicacion_enlace` FOREIGN KEY (`id_enl`) REFERENCES `enlace_servicio` (`id_enl`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_rol`
--
ALTER TABLE `usuario_rol`
  ADD CONSTRAINT `fk_usuario_rol_rol` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`rol_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_rol_usuario` FOREIGN KEY (`usu_id`) REFERENCES `usuario` (`usu_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
