-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-11-2026 a las 03:47:55
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
(2, 'Certificados', 'Ejemplos de certificados'),
(3, 'Enfermedades cronicas', 'Documentos con temas de enfermedades cronicas'),
(4, 'Doctores', 'Doctores que estan trabajando en la actualidad');

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
(10, 'monopolio2', 'documentos/doc_6aef55ce9fba7.pdf', '2026-11-07 13:46:06.000000', 1, 1);

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
(10, 1),
(10, 2),
(10, 3),
(10, 4);

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
(8, '¿Que tan satisfecho estas en general con tu experencia en el clinicas?', 2);

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
(7, '2026-08-30 14:50:50.000000', 3, 5),
(8, '2026-08-30 14:50:50.000000', 1, 6),
(9, '2026-08-30 14:50:50.000000', 1, 7),
(10, '2026-08-30 14:50:50.000000', 5, 8),
(12, '2026-08-30 15:07:49.000000', 1, 5),
(13, '2026-08-30 15:07:49.000000', 2, 6),
(14, '2026-08-30 15:07:49.000000', 3, 7),
(15, '2026-08-30 15:07:49.000000', 3, 8),
(17, '2026-08-30 15:08:09.000000', 2, 5),
(18, '2026-08-30 15:08:09.000000', 2, 6),
(19, '2026-08-30 15:08:09.000000', 1, 7),
(20, '2026-08-30 15:08:09.000000', 3, 8),
(22, '2026-08-30 15:11:19.000000', 1, 5),
(23, '2026-08-30 15:11:19.000000', 1, 6),
(24, '2026-08-30 15:11:19.000000', 1, 7),
(25, '2026-08-30 15:11:19.000000', 3, 8),
(27, '2026-08-30 17:24:56.000000', 5, 5),
(28, '2026-08-30 17:24:56.000000', 2, 6),
(29, '2026-08-30 17:24:56.000000', 4, 7),
(30, '2026-08-30 17:24:56.000000', 1, 8),
(32, '2026-08-30 19:23:15.000000', 1, 5),
(33, '2026-08-30 19:23:15.000000', 3, 6),
(34, '2026-08-30 19:23:15.000000', 3, 7),
(35, '2026-08-30 19:23:15.000000', 3, 8),
(37, '2026-10-27 21:20:26.000000', 3, 5),
(38, '2026-10-27 21:20:26.000000', 5, 6),
(39, '2026-10-27 21:20:26.000000', 5, 7),
(40, '2026-10-27 21:20:26.000000', 5, 8),
(41, '2026-11-07 13:17:55.000000', 4, 5),
(42, '2026-11-07 13:17:55.000000', 3, 6),
(43, '2026-11-07 13:17:55.000000', 4, 7),
(44, '2026-11-07 13:17:55.000000', 2, 8),
(45, '2026-11-07 14:22:55.000000', 1, 5),
(46, '2026-11-07 14:22:55.000000', 4, 6),
(47, '2026-11-07 14:22:55.000000', 3, 7),
(48, '2026-11-07 14:22:55.000000', 1, 8),
(49, '2026-11-07 14:25:55.000000', 2, 5),
(50, '2026-11-07 14:25:55.000000', 5, 6),
(51, '2026-11-07 14:25:55.000000', 1, 7),
(52, '2026-11-07 14:25:55.000000', 5, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Id_usuario` int(100) NOT NULL,
  `Nombre` varchar(25) NOT NULL,
  `Apellido` varchar(25) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Contraseña` varchar(60) NOT NULL,
  `Tipo_usuario` enum('Administrador','Funcionario','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Id_usuario`, `Nombre`, `Apellido`, `Email`, `Contraseña`, `Tipo_usuario`) VALUES
(1, 'Hydro', 'RusX', 'tiagoalmeidate@gmail.com', '$2y$10$v8JfdwaICjAogCbwfRiOSuFoK5vPBDk3CXKB6cygO69wgppLFCX5u', 'Administrador'),
(102, 'Rafael', 'Acosta', 'Rafita@gmail.com', '$2y$10$1/jLfoiVA9Q.sneMFP9mpeftKKZeZ5dpa5NoFp5UFNIWerdTEcBey', 'Funcionario'),
(103, 'Leonardo', 'Carambula', 'Carambulanet@gmail.com', '$2y$10$9uyqEwAa/ZwA8gFieQiaPOvHo2RqlwBUJVrz6OCZboePRS2hvnwZ.', 'Administrador'),
(105, 'baijavier', 'sosa', 'baijavier@gmail.com', 'hola67', 'Funcionario');

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
  MODIFY `Id_categoria` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `Id_documento` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `Id_encuesta` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  MODIFY `Id_pregunta` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `Id_respuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `Id_usuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

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
  ADD CONSTRAINT `Id_pregunta` FOREIGN KEY (`Id_pregunta`) REFERENCES `pregunta` (`Id_pregunta`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
