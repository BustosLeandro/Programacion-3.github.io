-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2022 a las 21:09:41
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pm`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursa`
--

CREATE TABLE `cursa` (
  `CodigoEstudiante` int(11) NOT NULL,
  `CodigoCurso` int(11) NOT NULL,
  `PagoPendiente` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cursa`
--

INSERT INTO `cursa` (`CodigoEstudiante`, `CodigoCurso`, `PagoPendiente`) VALUES
(2, 4, NULL),
(3, 1, 0),
(3, 2, NULL),
(3, 3, NULL),
(4, 1, 0),
(5, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `Codigo` int(11) NOT NULL,
  `Titulo` varchar(50) NOT NULL,
  `Descripcion` varchar(200) NOT NULL,
  `Costo` float NOT NULL,
  `FechaInicio` date NOT NULL,
  `FechaFin` date NOT NULL,
  `Cupos` int(11) DEFAULT NULL,
  `Imagen` varchar(100) DEFAULT NULL,
  `CodigoProfesor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`Codigo`, `Titulo`, `Descripcion`, `Costo`, `FechaInicio`, `FechaFin`, `Cupos`, `Imagen`, `CodigoProfesor`) VALUES
(1, 'Inglés', 'Curso para principiantes', 2500, '2022-11-07', '2023-03-23', NULL, 'imagenes/cursos/1.jpg', 2),
(2, 'Portugués', 'ashgfajskdfajkshdfkjashdfk', 0, '2022-11-11', '2023-01-19', NULL, NULL, 5),
(3, 'Portugués II', 'asdfasdfasdfasdf', 0, '2022-11-11', '2022-11-25', NULL, 'imagenes/cursos/3.jpg', 5),
(4, 'portugues III', 'fasdfasdfasdfasdfasdf', 0, '2022-11-11', '2022-11-26', NULL, NULL, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `destaca`
--

CREATE TABLE `destaca` (
  `CodigoUsuario` int(11) NOT NULL,
  `CodigoRespuesta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas`
--

CREATE TABLE `etiquetas` (
  `CodigoCurso` int(11) NOT NULL,
  `Etiqueta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `etiquetas`
--

INSERT INTO `etiquetas` (`CodigoCurso`, `Etiqueta`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intereses`
--

CREATE TABLE `intereses` (
  `CodigoUsuario` int(11) NOT NULL,
  `Interes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `intereses`
--

INSERT INTO `intereses` (`CodigoUsuario`, `Interes`) VALUES
(2, 1),
(3, 1),
(5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales`
--

CREATE TABLE `materiales` (
  `Codigo` int(11) NOT NULL,
  `Titulo` varchar(20) NOT NULL,
  `Archivo` varchar(100) NOT NULL,
  `Tipo` varchar(10) NOT NULL,
  `Descripcion` varchar(200) DEFAULT NULL,
  `FechaSubido` date NOT NULL,
  `CodigoCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `Codigo` int(11) NOT NULL,
  `Adjunto` varchar(100) NOT NULL,
  `CodigoEmisor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `Codigo` int(11) NOT NULL,
  `Notificacion` varchar(100) NOT NULL,
  `Fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`Codigo`, `Notificacion`, `Fecha`) VALUES
(1, 'El usuario pablo hizo una pregunta en el curso Inglés', '2022-11-11 18:22:40'),
(2, 'El usuario pablo respondió una pregunta del curso Inglés que tú también respondiste', '2022-11-11 18:22:47'),
(3, 'El usuario pablo respondió tu pregunta del curso Inglés', '2022-11-11 18:22:47'),
(4, 'El usuario Maria respondió una pregunta del curso Inglés, que tú también respondiste', '2022-11-11 18:24:13'),
(5, 'El usuario Maria respondió tu pregunta del curso Inglés', '2022-11-11 18:24:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `Codigo` int(11) NOT NULL,
  `Pregunta` varchar(200) NOT NULL,
  `Fecha` date NOT NULL DEFAULT current_timestamp(),
  `esDestacada` tinyint(4) DEFAULT NULL,
  `estaAbierta` tinyint(4) NOT NULL DEFAULT 1,
  `CodigoUsuario` int(11) NOT NULL,
  `CodigoCurso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`Codigo`, `Pregunta`, `Fecha`, `esDestacada`, `estaAbierta`, `CodigoUsuario`, `CodigoCurso`) VALUES
(5, '1er pregunta', '2022-11-11', NULL, 1, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recibe`
--

CREATE TABLE `recibe` (
  `CodigoUsuario` int(11) NOT NULL,
  `CodigoNotificacion` int(11) NOT NULL,
  `vista` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `recibe`
--

INSERT INTO `recibe` (`CodigoUsuario`, `CodigoNotificacion`, `vista`) VALUES
(2, 1, 0),
(3, 1, 0),
(4, 1, 0),
(5, 5, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuestas`
--

CREATE TABLE `respuestas` (
  `Codigo` int(11) NOT NULL,
  `Respuesta` varchar(500) NOT NULL,
  `EsDestacada` tinyint(1) DEFAULT NULL,
  `Fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `CodigoPregunta` int(11) NOT NULL,
  `CodigoUsuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `respuestas`
--

INSERT INTO `respuestas` (`Codigo`, `Respuesta`, `EsDestacada`, `Fecha`, `CodigoPregunta`, `CodigoUsuario`) VALUES
(3, '1er respuesta', NULL, '2022-11-11 18:22:47', 5, 5),
(4, '2da respuesta', NULL, '2022-11-11 18:24:13', 5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tags`
--

CREATE TABLE `tags` (
  `Codigo` int(11) NOT NULL,
  `Tag` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tags`
--

INSERT INTO `tags` (`Codigo`, `Tag`) VALUES
(2, 'Idiomas'),
(1, 'Informática'),
(5, 'Matematica'),
(3, 'Música'),
(4, 'Salud');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuarios`
--

CREATE TABLE `tipousuarios` (
  `Codigo` int(11) NOT NULL,
  `Tipo` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tipousuarios`
--

INSERT INTO `tipousuarios` (`Codigo`, `Tipo`) VALUES
(1, 'Admin'),
(2, 'Usuario Pro'),
(3, 'Usuario gratuito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Codigo` int(11) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `Apellido` varchar(20) NOT NULL,
  `FechaRegistro` date NOT NULL DEFAULT current_timestamp(),
  `Dni` int(11) NOT NULL,
  `Sexo` tinyint(1) NOT NULL,
  `Telefono` bigint(20) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(250) NOT NULL,
  `FotoPerfil` varchar(100) DEFAULT NULL,
  `Antecedentes` varchar(200) DEFAULT NULL,
  `VencimientoPro` date DEFAULT NULL,
  `FotoIcono` varchar(100) DEFAULT NULL,
  `Es` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Codigo`, `Nombre`, `Apellido`, `FechaRegistro`, `Dni`, `Sexo`, `Telefono`, `Email`, `Password`, `FotoPerfil`, `Antecedentes`, `VencimientoPro`, `FotoIcono`, `Es`) VALUES
(1, 'Leandro', 'Bustos', '2022-10-12', 41221526, 1, 2665001740, 'bustosleandro27@gmail.com', '$2y$10$pb8sDxFXxGKiFnDGb4njkOPFSsiu1aUKt1UbQNrfnuRZe6lYz0fPm', 'imagenes/usuarios/original/1.jpg', NULL, NULL, 'imagenes/usuarios/miniatura/1.jpg', 1),
(2, 'Maria', 'Lopez', '2022-10-12', 39552989, 0, 2664991324, 'MLopez27@hotmail.com', '$2y$10$ZPHDw7j.XernNDTH1y3itOzJfyAzgtfQPNzCBgxU.cUy7wJUib5BG', 'imagenes/usuarios/original/2.jpg', NULL, '2023-01-19', 'imagenes/usuarios/miniatura/2.jpg', 2),
(3, 'Ivan', 'Lucero', '2022-10-21', 39123456, 1, 2664775321, 'ivanlucero2@hotmail.com', '$2y$10$OGT3fMEwVqhIXAn16gC5uuMBRfjm5hdCb0NMPR8ok05EDEzsg7X32', 'imagenes/usuarios/original/3.jpg', NULL, NULL, 'imagenes/usuarios/miniatura/3.jpg', 3),
(4, 'Juan', 'Perez', '2022-11-09', 27885123, 1, 2665009988, 'juanperez@hotmail.com', '$2y$10$BRwTzNq1Oqkf7y9xYI4/FeqonjrvECGSGkL8XPv5bJDXZ7vdNqwPq', NULL, NULL, NULL, NULL, 3),
(5, 'pablo', 'lopez', '2022-11-11', 41221444, 1, 2664006543, 'pablolopez@hotmail.com', '$2y$10$SQC7ev1ChNom54WHDhH1ouz47FnNcrMfjnD7N6.65CEn7nCEwL1Oi', 'imagenes/usuarios/original/5.jpg', NULL, NULL, 'imagenes/usuarios/miniatura/5.jpg', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursa`
--
ALTER TABLE `cursa`
  ADD PRIMARY KEY (`CodigoEstudiante`,`CodigoCurso`),
  ADD KEY `CodigoEstudiante` (`CodigoEstudiante`),
  ADD KEY `CodigoCurso` (`CodigoCurso`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `U-Cod-Dicta` (`CodigoProfesor`);

--
-- Indices de la tabla `destaca`
--
ALTER TABLE `destaca`
  ADD PRIMARY KEY (`CodigoUsuario`,`CodigoRespuesta`),
  ADD KEY `R-Cod-Destaca` (`CodigoRespuesta`);

--
-- Indices de la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`CodigoCurso`,`Etiqueta`),
  ADD KEY `Etiqueta` (`Etiqueta`);

--
-- Indices de la tabla `intereses`
--
ALTER TABLE `intereses`
  ADD PRIMARY KEY (`CodigoUsuario`,`Interes`),
  ADD KEY `Interes` (`Interes`);

--
-- Indices de la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `C-Cod-Tiene` (`CodigoCurso`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `U-Cod-Envia` (`CodigoEmisor`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `U-Cod-Hace` (`CodigoUsuario`),
  ADD KEY `C-Cod-Tiene` (`CodigoCurso`);

--
-- Indices de la tabla `recibe`
--
ALTER TABLE `recibe`
  ADD PRIMARY KEY (`CodigoUsuario`,`CodigoNotificacion`),
  ADD KEY `N-Cod-Recibe` (`CodigoNotificacion`);

--
-- Indices de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD PRIMARY KEY (`Codigo`),
  ADD KEY `U-Cod-Hace` (`CodigoUsuario`),
  ADD KEY `P-Cod-Tiene` (`CodigoPregunta`);

--
-- Indices de la tabla `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`Codigo`),
  ADD UNIQUE KEY `Tag` (`Tag`);

--
-- Indices de la tabla `tipousuarios`
--
ALTER TABLE `tipousuarios`
  ADD PRIMARY KEY (`Codigo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Codigo`),
  ADD UNIQUE KEY `U-Dni` (`Dni`,`Sexo`),
  ADD UNIQUE KEY `U-Email` (`Email`),
  ADD KEY `U-Es` (`Es`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `respuestas`
--
ALTER TABLE `respuestas`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tags`
--
ALTER TABLE `tags`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipousuarios`
--
ALTER TABLE `tipousuarios`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cursa`
--
ALTER TABLE `cursa`
  ADD CONSTRAINT `cursa_ibfk_1` FOREIGN KEY (`CodigoCurso`) REFERENCES `cursos` (`Codigo`),
  ADD CONSTRAINT `cursa_ibfk_2` FOREIGN KEY (`CodigoEstudiante`) REFERENCES `usuarios` (`Codigo`);

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`CodigoProfesor`) REFERENCES `usuarios` (`Codigo`);

--
-- Filtros para la tabla `destaca`
--
ALTER TABLE `destaca`
  ADD CONSTRAINT `destaca_ibfk_1` FOREIGN KEY (`CodigoUsuario`) REFERENCES `usuarios` (`Codigo`),
  ADD CONSTRAINT `destaca_ibfk_2` FOREIGN KEY (`CodigoRespuesta`) REFERENCES `respuestas` (`Codigo`);

--
-- Filtros para la tabla `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD CONSTRAINT `etiquetas_ibfk_1` FOREIGN KEY (`Etiqueta`) REFERENCES `tags` (`Codigo`),
  ADD CONSTRAINT `etiquetas_ibfk_2` FOREIGN KEY (`CodigoCurso`) REFERENCES `cursos` (`Codigo`);

--
-- Filtros para la tabla `intereses`
--
ALTER TABLE `intereses`
  ADD CONSTRAINT `intereses_ibfk_1` FOREIGN KEY (`CodigoUsuario`) REFERENCES `usuarios` (`Codigo`),
  ADD CONSTRAINT `intereses_ibfk_2` FOREIGN KEY (`Interes`) REFERENCES `tags` (`Codigo`);

--
-- Filtros para la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD CONSTRAINT `materiales_ibfk_1` FOREIGN KEY (`CodigoCurso`) REFERENCES `cursos` (`Codigo`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`CodigoEmisor`) REFERENCES `usuarios` (`Codigo`);

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`CodigoUsuario`) REFERENCES `usuarios` (`Codigo`),
  ADD CONSTRAINT `preguntas_ibfk_2` FOREIGN KEY (`CodigoCurso`) REFERENCES `cursos` (`Codigo`);

--
-- Filtros para la tabla `recibe`
--
ALTER TABLE `recibe`
  ADD CONSTRAINT `recibe_ibfk_1` FOREIGN KEY (`CodigoUsuario`) REFERENCES `usuarios` (`Codigo`),
  ADD CONSTRAINT `recibe_ibfk_2` FOREIGN KEY (`CodigoNotificacion`) REFERENCES `notificaciones` (`Codigo`);

--
-- Filtros para la tabla `respuestas`
--
ALTER TABLE `respuestas`
  ADD CONSTRAINT `respuestas_ibfk_1` FOREIGN KEY (`CodigoUsuario`) REFERENCES `usuarios` (`Codigo`),
  ADD CONSTRAINT `respuestas_ibfk_2` FOREIGN KEY (`CodigoPregunta`) REFERENCES `preguntas` (`Codigo`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`Es`) REFERENCES `tipousuarios` (`Codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
