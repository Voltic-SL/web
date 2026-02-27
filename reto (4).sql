-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-02-2026 a las 09:41:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ausencia`
--

CREATE TABLE `ausencia` (
  `id_ausencia` int(11) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente',
  `id_h` int(11) NOT NULL,
  `dni_cubre` varchar(20) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `aula` varchar(100) NOT NULL,
  `texto` text DEFAULT NULL,
  `justificante` varchar(255) DEFAULT NULL,
  `tarea` text DEFAULT NULL,
  `semana` varchar(10) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ausencia`
--

INSERT INTO `ausencia` (`id_ausencia`, `dni`, `estado`, `id_h`, `dni_cubre`, `tipo`, `aula`, `texto`, `justificante`, `tarea`, `semana`, `fecha`) VALUES
(9, '1234578A', 'cubierta', 12, '30336116j', 'Problema familiar', 'A-203', 'Debido a un problema familiar voy a faltar en la fecha indicada', 'uploads/justificantes/1771578412_Voltic_Semana1.pdf', NULL, NULL, NULL),
(12, '1234578A', 'cubierta', 13, '1234578A', 'Enfermedad', 'SMR2 Fondo Derecha', 'jhf sjkx bkl jfh', 'uploads/justificantes/1771930229_HTTPS_David_Tena.pdf', NULL, NULL, NULL),
(13, '1234578A', 'pendiente', 13, NULL, 'Enfermedad', 'SMR2 Fondo Derecha', 'gfdfgfdf', NULL, NULL, NULL, NULL),
(14, '1234578A', 'cubierta', 13, '30336116J', 'Enfermedad', 'SMR2 Fondo Derecha', 'jdjdjd', NULL, NULL, NULL, '2026-02-26'),
(15, '1234578A', 'pendiente', 13, NULL, 'Enfermedad', 'SMR2 Fondo Derecha', 'fdgdsfg', NULL, NULL, NULL, '2026-02-25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hora`
--

CREATE TABLE `hora` (
  `id_hora` varchar(11) NOT NULL,
  `dia` varchar(15) DEFAULT NULL,
  `hora` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hora`
--

INSERT INTO `hora` (`id_hora`, `dia`, `hora`) VALUES
('J1', 'J', '1'),
('J2', 'J', '2'),
('J3', 'J', '3'),
('J4', 'J', '4'),
('J5', 'J', '5'),
('J6', 'J', '6'),
('L1', 'L', '1'),
('L2', 'L', '2'),
('L3', 'L', '3'),
('L4', 'L', '4'),
('L5', 'L', '5'),
('L6', 'L', '6'),
('M1', 'M', '1'),
('M2', 'M', '2'),
('M3', 'M', '3'),
('M4', 'M', '4'),
('M5', 'M', '5'),
('M6', 'M', '6'),
('V1', 'V', '1'),
('V2', 'V', '2'),
('V3', 'V', '3'),
('V4', 'V', '4'),
('V5', 'V', '5'),
('V6', 'V', '6'),
('X1', 'X', '1'),
('X2', 'X', '2'),
('X3', 'X', '3'),
('X4', 'X', '4'),
('X5', 'X', '5'),
('X6', 'X', '6');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `id_h` int(11) NOT NULL,
  `dni_u` varchar(20) NOT NULL,
  `id_hora` varchar(11) NOT NULL,
  `modulo` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horario`
--

INSERT INTO `horario` (`id_h`, `dni_u`, `id_hora`, `modulo`) VALUES
(10, '30336116J', 'J2', 'Guardia'),
(12, '1234578A', 'J1', 'Guardia'),
(13, '1234578A', 'J2', 'Guardia'),
(15, '30336116j', 'V3', 'Guardia'),
(16, '1234578A', 'J4', 'Guardia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `contraseña` varchar(100) DEFAULT NULL,
  `rol` varchar(30) DEFAULT NULL,
  `familia` varchar(50) DEFAULT NULL,
  `tipo_u` varchar(30) DEFAULT NULL,
  `ce` varchar(50) DEFAULT NULL,
  `faltas_cubiertas` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`dni`, `nombre`, `apellido`, `contraseña`, `rol`, `familia`, `tipo_u`, `ce`, `faltas_cubiertas`) VALUES
('12345678A', 'prueba', 'prueba', '$2y$10$gKVKEAOub1XzLfxkauSJEuA/B8Kpi.M162CW9iwrGf7oli/3AZwTa', 'admin', 'Informática', NULL, NULL, 0),
('1234578A', 'Prueba', 'Prueba', '$2y$10$DZlrQbmMK0jbWzuqjYaRgem0MycUt72NsCfNomkZwUOrBPL0Oeg8y', 'admin', 'Informática', NULL, 'correo@correo.com', 0),
('18495127E', 'Ricardo', 'Barahona Quílez', '$2y$10$DzIxOhOP0Osfty6W8YA2HeKVtM4FXPVo/ILdguCe/oTEu4W0P8zn2', 'admin', 'Informática', NULL, 'barahonaquilez@gmail.com', 0),
('30336116J', 'David', 'Tena Trullén', '$2y$10$Op2quSg814f5nngIEpCKfe1AfnGM6V58le9LHXIOyidYBisWapxKm', 'admin', 'Informática', NULL, 'dtena632@gmail.com', 0),
('69696969S', 'Andy', 'Lucas', '$2y$10$40Z.bAA0nHWoMjYoVk.IYuXGBDCTeSCeZEjoLgeO4eQB4CoSlIamC', 'profesor', 'Electricidad', NULL, 'warner@music.com', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ausencia`
--
ALTER TABLE `ausencia`
  ADD PRIMARY KEY (`id_ausencia`),
  ADD KEY `id_h` (`id_h`),
  ADD KEY `dni_cubre` (`dni_cubre`);

--
-- Indices de la tabla `hora`
--
ALTER TABLE `hora`
  ADD PRIMARY KEY (`id_hora`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`id_h`),
  ADD KEY `dni` (`dni_u`),
  ADD KEY `horario_ibfk_2` (`id_hora`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ausencia`
--
ALTER TABLE `ausencia`
  MODIFY `id_ausencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `id_h` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ausencia`
--
ALTER TABLE `ausencia`
  ADD CONSTRAINT `ausencia_ibfk_1` FOREIGN KEY (`id_h`) REFERENCES `horario` (`id_h`),
  ADD CONSTRAINT `ausencia_ibfk_2` FOREIGN KEY (`dni_cubre`) REFERENCES `usuarios` (`dni`);

--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `horario_ibfk_1` FOREIGN KEY (`dni_u`) REFERENCES `usuarios` (`dni`),
  ADD CONSTRAINT `horario_ibfk_2` FOREIGN KEY (`id_hora`) REFERENCES `hora` (`id_hora`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
