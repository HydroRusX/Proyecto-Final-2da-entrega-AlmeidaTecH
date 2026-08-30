-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-08-2026 a las 22:01:31
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
-- Base de datos: `projecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `Id_categoria` int(15) NOT NULL,
  `Nombre_categoria` varchar(25) NOT NULL,
  `Descripcion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`Id_categoria`, `Nombre_categoria`, `Descripcion`) VALUES
(1, 'Bendecido', 'Dios bendice esta categoria'),
(2, 'Certificados', 'Ejemplos de certificados');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `Id_documento` int(15) NOT NULL,
  `Titulo` varchar(45) NOT NULL,
  `Archivo` varchar(100) NOT NULL,
  `Fecha_carga` datetime(6) NOT NULL,
  `Id_usuario` int(100) NOT NULL,
  `Activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documento`
--

INSERT INTO `documento` (`Id_documento`, `Titulo`, `Archivo`, `Fecha_carga`, `Id_usuario`, `Activo`) VALUES
(1, 'Mi gran arte', 'documentos/doc_6a92ff7853a82.pdf', '2026-08-29 12:49:12.000000', 1, 1),
(2, 'Mi gran arte', 'documentos/doc_6a92ff7c7e458.pdf', '2026-08-29 12:49:16.000000', 1, 1),
(4, 'Mi gran fallo', 'documentos/doc_6a9301f85417b.pdf', '2026-08-29 12:59:52.000000', 1, 1),
(5, 'Mi gran acierto', 'documentos/doc_6a930543bdced.pdf', '2026-08-29 13:13:55.000000', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_categoria`
--

CREATE TABLE `documento_categoria` (
  `Id_documento` int(15) NOT NULL,
  `Id_categoria` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documento_categoria`
--

INSERT INTO `documento_categoria` (`Id_documento`, `Id_categoria`) VALUES
(1, 1),
(2, 1),
(4, 2),
(5, 1),
(5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `Id_encuesta` int(15) NOT NULL,
  `Titulo` varchar(25) NOT NULL,
  `Descripcion` varchar(100) NOT NULL,
  `Segmentada` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuesta`
--

INSERT INTO `encuesta` (`Id_encuesta`, `Titulo`, `Descripcion`, `Segmentada`) VALUES
(2, 'Encuesta de satisfaccion', 'Encuesta acerca de tu experencia', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE `pregunta` (
  `Id_pregunta` int(15) NOT NULL,
  `Texto` varchar(100) NOT NULL,
  `Id_encuesta` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pregunta`
--

INSERT INTO `pregunta` (`Id_pregunta`, `Texto`, `Id_encuesta`) VALUES
(5, '¿Que tan satisfecho estas con la atencion del personal?', 2),
(6, '¿Que tan satisfecho estas con el personal?', 2),
(7, '¿Que tan satisfecho estas con la limpieza de las instalaciones?', 2),
(8, '¿Que tan satisfecho estas en general con tu experencia en el clinicas?', 2),
(9, 'Prueba', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `Id_respuesta` int(11) NOT NULL,
  `Fecha_respuesta` datetime(6) NOT NULL,
  `Valor_satisfaccion` int(11) NOT NULL,
  `Id_pregunta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuesta`
--

INSERT INTO `respuesta` (`Id_respuesta`, `Fecha_respuesta`, `Valor_satisfaccion`, `Id_pregunta`) VALUES
(2, '2026-08-30 14:50:20.000000', 4, 5),
(3, '2026-08-30 14:50:20.000000', 3, 6),
(4, '2026-08-30 14:50:20.000000', 4, 7),
(5, '2026-08-30 14:50:20.000000', 5, 8),
(6, '2026-08-30 14:50:20.000000', 1, 9),
(7, '2026-08-30 14:50:50.000000', 3, 5),
(8, '2026-08-30 14:50:50.000000', 1, 6),
(9, '2026-08-30 14:50:50.000000', 1, 7),
(10, '2026-08-30 14:50:50.000000', 5, 8),
(11, '2026-08-30 14:50:50.000000', 1, 9),
(12, '2026-08-30 15:07:49.000000', 1, 5),
(13, '2026-08-30 15:07:49.000000', 2, 6),
(14, '2026-08-30 15:07:49.000000', 3, 7),
(15, '2026-08-30 15:07:49.000000', 3, 8),
(16, '2026-08-30 15:07:49.000000', 3, 9),
(17, '2026-08-30 15:08:09.000000', 2, 5),
(18, '2026-08-30 15:08:09.000000', 2, 6),
(19, '2026-08-30 15:08:09.000000', 1, 7),
(20, '2026-08-30 15:08:09.000000', 3, 8),
(21, '2026-08-30 15:08:09.000000', 3, 9),
(22, '2026-08-30 15:11:19.000000', 1, 5),
(23, '2026-08-30 15:11:19.000000', 1, 6),
(24, '2026-08-30 15:11:19.000000', 1, 7),
(25, '2026-08-30 15:11:19.000000', 3, 8),
(26, '2026-08-30 15:11:19.000000', 5, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Id_usuario` int(100) NOT NULL,
  `Nombre` varchar(25) NOT NULL,
  `Apellido` varchar(25) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Contraseña` varchar(16) NOT NULL,
  `Tipo_usuario` enum('Administrador','Funcionario','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Id_usuario`, `Nombre`, `Apellido`, `Email`, `Contraseña`, `Tipo_usuario`) VALUES
(1, 'Hydro', 'RusX', 'tiagoalmeidate@gmail.com', 'gokuninja20200', 'Administrador'),
(102, 'Rafael', 'Acosta', 'Rafita@gmail.com', 'Todomybrother', 'Funcionario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`Id_categoria`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`Id_documento`),
  ADD KEY `Id_usuario` (`Id_usuario`);

--
-- Indices de la tabla `documento_categoria`
--
ALTER TABLE `documento_categoria`
  ADD PRIMARY KEY (`Id_documento`,`Id_categoria`),
  ADD KEY `Id_categoria` (`Id_categoria`);

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`Id_encuesta`);

--
-- Indices de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD PRIMARY KEY (`Id_pregunta`),
  ADD KEY `Id_encuesta` (`Id_encuesta`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`Id_respuesta`),
  ADD KEY `Id_pregunta` (`Id_pregunta`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `Id_categoria` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `Id_documento` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `Id_encuesta` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  MODIFY `Id_pregunta` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `Id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `Id_usuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `Id_usuario` FOREIGN KEY (`Id_usuario`) REFERENCES `usuario` (`Id_usuario`);

--
-- Filtros para la tabla `documento_categoria`
--
ALTER TABLE `documento_categoria`
  ADD CONSTRAINT `Id_categoria` FOREIGN KEY (`Id_categoria`) REFERENCES `categoria` (`Id_categoria`),
  ADD CONSTRAINT `Id_documento` FOREIGN KEY (`Id_documento`) REFERENCES `documento` (`Id_documento`);

--
-- Filtros para la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD CONSTRAINT `Id_encuesta` FOREIGN KEY (`Id_encuesta`) REFERENCES `encuesta` (`Id_encuesta`);

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `Id_pregunta` FOREIGN KEY (`Id_pregunta`) REFERENCES `pregunta` (`Id_pregunta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
