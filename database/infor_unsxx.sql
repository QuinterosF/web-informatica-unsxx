-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: sdb-60.hosting.stackcp.net
-- Tiempo de generación: 13-09-2024 a las 20:48:24
-- Versión del servidor: 10.6.18-MariaDB-log
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `informaticabd-35303235cca3`
--
CREATE DATABASE IF NOT EXISTS `informaticabd-35303235cca3` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `informaticabd-35303235cca3`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `academicos`
--

CREATE TABLE `academicos` (
  `id_acad` int(11) NOT NULL,
  `nom_acad` varchar(300) NOT NULL,
  `foto_acad` varchar(300) NOT NULL,
  `cel_acad` int(15) NOT NULL,
  `email_acad` varchar(150) NOT NULL,
  `cat_acad` int(11) NOT NULL,
  `desc_acad` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `academicos`
--

INSERT INTO `academicos` (`id_acad`, `nom_acad`, `foto_acad`, `cel_acad`, `email_acad`, `cat_acad`, `desc_acad`) VALUES
(4, 'M.Sc. Ing. Santos Ireneo Juchasara Colque', 'santos - Ireneo Juchasara.jpg', 60492494, 'sijucol@gmail.com', 2, 'Ingeniero Informático, Docentes Titular (Inteligencia Artificial), M.Sc. en Ciencias de la Computación Mención Seguridad informática y Software Libre, Doctorante en Ciencias de la Computación, realizo varios diplomados.\r\n'),
(11, 'M.Sc. Ing. Richard Pascual Castro', 'img085 - Richard Pascual Castro.jpg', 68351030, 'richard@gmail.com', 2, 'Ingeniero Informático, Docente Titular, M.Sc. Gestión, Elaboración y Evaluación de Proyectos, diplomados en Seguridad informática y otros.'),
(12, 'M.Sc. Ing. Jhillma Margot Portanda Zurita', 'FOTO_JHILLMA - Jhillma Portanda.jpeg', 74259358, 'jhillmapz.010980@gmail.com', 3, 'Ingeniero en Sistemas, Docente Titular (Ingeniería de Software), Maestría en Educación Superior, realizo varios Diplomados'),
(15, 'M.Sc. Ing. Jorge Villcaez Castillo', 'Foto Jorge - Blanco - Jorge Villcáez.jpg', 77148375, 'jorgevill2015@gmail.com', 2, 'Ingeniero de Sistemas, Docente Titular, M.Sc. en Ciencias de Computación mención Computación Móvil y Telecomunicaciones, Maestrante en Rebotica, Doctorante en Ciencias de la Computación, realizo varios diplomados.'),
(16, 'M.Sc. Ing. Leyna Roxana Salinas Veyzaga', 'Leyna - Leyna Salinas Veyzaga.jpeg', 78619651, 'leyna@gmail.com', 2, 'Ingeniero Informático, Docente Titular (Estructura de Datos), M.Sc. en Ciencias de la Computación mención Seguridad Informática, Doctorante en Ciencias de la Computación, varios diplomados.'),
(17, 'M.Sc. Ing. Yolanda Soto Leyva', 'FOTOGRAFIA YOLANDA SOTO LEYVA - Yolanda Soto Leyva.jpg', 73401234, 'ysotole@gmail.com', 2, 'Ingeniero en Sistemas, Docente Titular, M.Sc. en Ciencias de la Computación mención Seguridad Informática, Doctorante en Ciencias de la Computación. '),
(18, 'Ing. David Cazorla Valdivia', '20231201_201520 - David Cazorla Valdivia.jpg', 74116315, 'davidcv.dev@gmail.com', 2, 'Ingeniero Informático, Docente Invitado, Experto en Configuración de Mikrotiks Vlans, Alineación de radio enlaces Ericsson, Huawei y ZTE., Dominio de herramientas de análisis y gestión de red, Configuración de servidores, Conocimiento en PHP, JavaScript, Dart y java.'),
(19, 'M.Sc. Ing. Freddy Rocabado Ibañez ', 'fotoRoco1 - Freddy Rocabado Ibañez.jpg', 68294945, 'rocosfree@gmail.com', 4, 'Lic. En Informática, Mención: Ingeniería de Sistemas Informáticos, Magister en Gestión, Elaboración y Evaluación de Proyectos, Varios Diplomados, Docente Titular Materia Análisis y Diseño de Sistemas, Coordinador Mención Computación Móvil.\r\n'),
(20, 'M.Sc. Ing. Fernando Miranda Choque', 'IMG-20240824-WA0006 - fernando.jpg', 67237857, 'fernando@gmail.com', 2, 'Ingeniero Informático, Docente Titular (Telecomunicaciones), M.Ss. en Evaluación de Proyectos y Ciencias de la Computación.'),
(21, 'Ing. Mary Lovera Copa', 'foto mary2 - mary.jpeg', 72318145, 'Mar_lc@hotmail.com', 5, 'Ingeniero de Sistemas, Docentes Titular (Estadística y Probabilidad), Diplomado en Educación Superior y Formación Basada en Competencias, Coordinadora Mención Seguridad Informática.'),
(22, 'Juan Pablo Luna Felipez, Ph.D.', 'jp1 - Instituto de Investigacion IIDAI UNSXX.jpg', 75418754, 'iidai.informatica.unsxx@gmail.com', 6, 'Doctor en Ciencias de la Computación, Máster en Ciencias de la Computación con mención en Seguridad Informática y Software Libre, Máster en Educación Superior, cuenta con Diplomados en Preparación Evaluación y Gestión de Proyectos, Formación Basada por Competencias y Metodología de la Investigación Científica.\r\n\r\nHa sido director de la carrera Ingeniería Informática y actualmente es Presidente y fundador de la Sociedad Científica de Docentes de la Universidad Nacional “Siglo XX”, Coordinador del Instituto de Investigación y Desarrollo de Aplicaciones IIDAI, Director de la Revista “Ciencia y Tecnología Informática” y Docente Universitario Titular en la carrera Ingeniería Informática de la Universidad Nacional “Siglo XX”, obtuvo varios premios y reconocimientos.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admision`
--

CREATE TABLE `admision` (
  `id_adm` int(11) NOT NULL,
  `proc_adm` longtext NOT NULL,
  `req_adm` longtext NOT NULL,
  `img_adm` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `celulares`
--

CREATE TABLE `celulares` (
  `id_cel` int(11) NOT NULL,
  `nom_cel` varchar(300) NOT NULL,
  `num_cel` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `celulares`
--

INSERT INTO `celulares` (`id_cel`, `nom_cel`, `num_cel`) VALUES
(1, 'Dirección de Carrera', 76543210),
(2, 'Secretaría de Carrera', 65432123);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunicados`
--

CREATE TABLE `comunicados` (
  `id_com` int(11) NOT NULL,
  `nom_com` longtext NOT NULL,
  `desc_com` longtext NOT NULL,
  `doc_com` varchar(300) NOT NULL,
  `foto_com` varchar(300) NOT NULL,
  `fech_com` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `comunicados`
--

INSERT INTO `comunicados` (`id_com`, `nom_com`, `desc_com`, `doc_com`, `foto_com`, `fech_com`) VALUES
(13, 'calendario académico 2024', '', 'HCU 064-23_CALENDARIO_2024.pdf', '', '2024-02-05'),
(14, 'REQUISITOS ESTUDIANTES NUEVOS 2024', '', 'REQUISITOS ESTUDIANTES NUEVOS 2024.pdf', '', '2024-02-05'),
(15, 'INTELIGENCIa ARTIFICIAL  aplicado a la   Educación curso ', '', 'infor4.pdf', 'infor5.png', '2024-04-10'),
(16, 'Cronogramas de actividades aniversario', '', 'universidad nacional ‘’siglo xx’’ AREA tecnología carrera ingeniería informática.pdf', 'universidad nacional ‘’siglo xx’’ AREA tecnología carrera ingeniería informática (6).png', '2024-06-29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extension`
--

CREATE TABLE `extension` (
  `id_ext` int(11) NOT NULL,
  `nom_ext` varchar(500) NOT NULL,
  `fech_ext` date NOT NULL,
  `desc_ext` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fotos`
--

CREATE TABLE `fotos` (
  `id_foto` int(11) NOT NULL,
  `nom_foto` varchar(300) NOT NULL,
  `id_ext` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inicio`
--

CREATE TABLE `inicio` (
  `id_inc` int(11) NOT NULL,
  `afiche` varchar(150) NOT NULL,
  `presentacion` longtext NOT NULL,
  `perfil` longtext NOT NULL,
  `mision` longtext NOT NULL,
  `vision` longtext NOT NULL,
  `objetivo` longtext NOT NULL,
  `historia` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `inicio`
--

INSERT INTO `inicio` (`id_inc`, `afiche`, `presentacion`, `perfil`, `mision`, `vision`, `objetivo`, `historia`) VALUES
(1, 'afiche_2024.jpg', 'En el mundo actual donde el acelerado avance de la ciencia y tecnología informática hace necesario la formación de alto nivel de profesionales Ingenieros Informáticos idóneos, comprometidos con la sociedad , el desarrollo de Bolivia y dispuestos a asumir los cambios del mundo globalizado. La Carrera Ingeniería Informática de la Universidad Nacional \"Siglo XX\" ofrece la formación de profesionales Ingenieros Informáticos de alto nivel que respondan a las demandas actuales de la sociedad.', 'Desarrollar sistemas para su aplicación en procesos industriales.\r\n\r\nTiene Formación para el manejo y mantenimiento de redes de computadoras.\r\n\r\nDesarrolla e implementa sistemas automatizados de información.\r\n\r\nRealiza y dirige estudios de asesoría en el campo de la informática.\r\n\r\nTiene la capacidad de establecer las especificaciones de los productos a desarrollar.\r\n\r\nDesarrolla programas y aplicaciones computacionales para la solución de problemas especificas, con el máximo aprovechamiento de recursos humanos y materiales.\r\n\r\nDiseñar e implementar \"Planes de desarrollo informático\", para la organización en la cual trabaja.\r\n\r\nDirige grupos de trabajo con eficiencia y eficacia, garantizando la cantidad exigida en la institución en la que desempeña.', 'Formar profesionales competitivos en el área de informática, con principios éticos, conciencia social; que lideren soluciones tecnológicas, promoviendo el desarrollo y la innovación, con capacidad de generar conocimiento científico y tecnológico para atender las demandas locales y globales.', 'Ser un referente académico de excelencia a nivel nacional e internacional en el área de informática, con espíritu innovador, liderazgo y compromiso social.', 'Formar profesionales con capacidad técnico científico, que logren implementar sistemas tecnológicos en áreas del desarrollo social, económico y político de la región y del país entero que tengan capacidades de desenvolvimiento en el mundo laboral, con reales aplicaciones científicas, para su desarrollo en el campo económico, social e ideológico del sector y coadyuvar de esta manera en el logro de soluciones que demanda nuestra región y nación.', 'El primer intento de iniciación de esta carrera, fue el año de 1996, cuando a través de una preinscripción en el área de tecnología. La gestión de 1997, se logro inscribir cantidad de gente de la región y del interior del país, que demostró su entusiasmo de proseguir el estudio en este campo tecnológico, Ante el latente entusiasmo juvenil y la proyectada gestión académica de la carrera, se tuvo que ir implementando paulatinamente los medios necesarios para que esa aspiración no quede truncada. Al respecto, debemos indicar que se logro la consecución de computadoras para que puedan implementar el laboratorio de informática y la ampliación de construcción, destinado a esta carrera de reciente creación.\r\n\r\nLa carrera se nace a la vida un 4 de julio de 1998 según resolución del H.C.U. Nº 011/98.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `laboratorios`
--

CREATE TABLE `laboratorios` (
  `id_lab` int(11) NOT NULL,
  `resp_lab` int(11) NOT NULL,
  `horario_lab` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `laboratorios`
--

INSERT INTO `laboratorios` (`id_lab`, `resp_lab`, `horario_lab`) VALUES
(1, 10, 'LAB_SEGURIDAD_INFOR.pdf'),
(2, 11, 'LAB_COMP_MOVIL.pdf'),
(3, 14, 'Planilla Abril 2022, Aux. Microcontroladores.pdf'),
(4, 14, 'LAB_SOFTWARE_1.pdf'),
(5, 12, 'LAB_SOFTWARE_II.pdf'),
(6, 12, 'Planilla Abril 2022, Aux. Investigación Operativa.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logros`
--

CREATE TABLE `logros` (
  `id_log` int(11) NOT NULL,
  `nom_log` varchar(300) NOT NULL,
  `lugar_log` varchar(150) NOT NULL,
  `fech_log` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usu_id` int(11) NOT NULL,
  `usu_nom` varchar(150) NOT NULL,
  `usu_correo` varchar(150) NOT NULL,
  `usu_pass` varchar(150) NOT NULL,
  `estado` int(11) NOT NULL,
  `acerca_de` int(11) NOT NULL,
  `carrera` int(11) NOT NULL,
  `comunicados` int(11) NOT NULL,
  `laboratorios` int(11) NOT NULL,
  `docentes` int(11) NOT NULL,
  `extension` int(11) NOT NULL,
  `usuarios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usu_id`, `usu_nom`, `usu_correo`, `usu_pass`, `estado`, `acerca_de`, `carrera`, `comunicados`, `laboratorios`, `docentes`, `extension`, `usuarios`) VALUES
(1, 'OPTIMUS INFOR', 'contactos@informatica-unsxx.net', 'S@lud4dejulio', 1, 1, 1, 1, 1, 1, 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `academicos`
--
ALTER TABLE `academicos`
  ADD PRIMARY KEY (`id_acad`);

--
-- Indices de la tabla `admision`
--
ALTER TABLE `admision`
  ADD PRIMARY KEY (`id_adm`);

--
-- Indices de la tabla `celulares`
--
ALTER TABLE `celulares`
  ADD PRIMARY KEY (`id_cel`);

--
-- Indices de la tabla `comunicados`
--
ALTER TABLE `comunicados`
  ADD PRIMARY KEY (`id_com`);

--
-- Indices de la tabla `extension`
--
ALTER TABLE `extension`
  ADD PRIMARY KEY (`id_ext`);

--
-- Indices de la tabla `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`id_foto`);

--
-- Indices de la tabla `inicio`
--
ALTER TABLE `inicio`
  ADD PRIMARY KEY (`id_inc`);

--
-- Indices de la tabla `laboratorios`
--
ALTER TABLE `laboratorios`
  ADD PRIMARY KEY (`id_lab`);

--
-- Indices de la tabla `logros`
--
ALTER TABLE `logros`
  ADD PRIMARY KEY (`id_log`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usu_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `academicos`
--
ALTER TABLE `academicos`
  MODIFY `id_acad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `celulares`
--
ALTER TABLE `celulares`
  MODIFY `id_cel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `comunicados`
--
ALTER TABLE `comunicados`
  MODIFY `id_com` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `extension`
--
ALTER TABLE `extension`
  MODIFY `id_ext` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `fotos`
--
ALTER TABLE `fotos`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `inicio`
--
ALTER TABLE `inicio`
  MODIFY `id_inc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `logros`
--
ALTER TABLE `logros`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
