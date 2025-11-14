-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-11-2025 a las 22:41:01
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
-- Base de datos: `juego_preguntas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `puntos_minimos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id`, `nombre`, `puntos_minimos`) VALUES
(1, 'Básico', 0),
(2, 'Intermedio', 500),
(3, 'Avanzado', 1200);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas`
--

CREATE TABLE `preguntas` (
  `id` int(11) NOT NULL,
  `nivel_id` int(11) NOT NULL,
  `tema` varchar(150) NOT NULL,
  `texto` text NOT NULL,
  `opcion_a` text NOT NULL,
  `opcion_b` text NOT NULL,
  `opcion_c` text NOT NULL,
  `opcion_d` text NOT NULL,
  `opcion_correcta` char(1) NOT NULL,
  `puntos` int(11) NOT NULL,
  `imagenes` varchar(225) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas`
--

INSERT INTO `preguntas` (`id`, `nivel_id`, `tema`, `texto`, `opcion_a`, `opcion_b`, `opcion_c`, `opcion_d`, `opcion_correcta`, `puntos`, `imagenes`, `creado_por`, `creado_en`) VALUES
(1, 1, 'Scrum', '¿Qué rol en Scrum es responsable de maximizar el valor del producto?', 'Scrum Master', 'Product Owner', 'Dev Team', 'Stakeholder', 'b', 40, 'img/img_p1.png', NULL, '2025-11-14 08:08:47'),
(2, 1, 'Scrum', '¿Qué artefacto contiene la lista priorizada de requerimientos?', 'Sprint Backlog', 'Product Backlog', 'Incremento', 'Roadmap', 'b', 40, 'img_p2.png', NULL, '2025-11-14 08:08:47'),
(3, 1, 'SCRUM', '¿Cuánto dura típicamente un Sprint?', '1 a 4 semanas', '1 año', '6 meses', 'Un día', 'a', 40, 'img_p3.png', NULL, '2025-11-14 08:08:47'),
(4, 1, 'Análisis de Datos', '¿Qué es un diagrama de flujo?', 'Un código', 'Un conjunto de pasos representados gráficamente', 'Un archivo SQL', 'Un manual', 'b', 40, 'img_p4.png', NULL, '2025-11-14 08:08:47'),
(5, 1, 'Análisis', '¿Qué técnica recoge necesidades del cliente?', 'Reuniones', 'Entrevistas', 'Encuestas', 'Todas las anteriores', 'd', 40, 'img_p5.png', NULL, '2025-11-14 08:08:47'),
(6, 1, 'SCRUM', '¿Quién facilita las ceremonias de Scrum?', 'PO', 'SM', 'Dev Team', 'Cliente', 'b', 40, 'img_p6.png', NULL, '2025-11-14 08:08:47'),
(7, 1, 'Análisis', '¿Qué es un requisito funcional?', 'Describe cómo debe comportarse el sistema', 'Describe colores', 'Describe hardware', 'Describe costos', 'a', 40, 'img_p7.png', NULL, '2025-11-14 08:08:47'),
(8, 1, 'Análisis', '¿Qué herramienta se usa para modelar procesos?', 'Figma', 'Draw.io', 'Photoshop', 'Premiere', 'b', 40, 'img_p8.png', NULL, '2025-11-14 08:08:47'),
(9, 1, 'SCRUM', '¿Cuál es una ceremonia de Scrum?', 'Daily', 'Análisis', 'Deploy', 'Testing', 'a', 40, 'img_p9.png', NULL, '2025-11-14 08:08:47'),
(10, 1, 'SCRUM', '¿Qué evento inspecciona el incremento?', 'Sprint Review', 'Daily', 'Retrospectiva', 'Meeting estratégica', 'a', 40, 'img_p10.png', NULL, '2025-11-14 08:08:47'),
(11, 2, 'Diseño', '¿Qué es un diagrama UML?', 'Un dibujo', 'Un modelo del sistema', 'Un archivo SQL', 'Un programa', 'b', 60, 'img_p11.png', NULL, '2025-11-14 08:09:03'),
(12, 2, 'Diseño', '¿Qué representa un caso de uso?', 'Base de datos', 'Interacciones entre usuario y sistema', 'Pruebas', 'Errores', 'b', 60, 'img_p12.png', NULL, '2025-11-14 08:09:03'),
(13, 2, 'Pruebas', '¿Qué es una prueba unitaria?', 'Prueba del sistema completo', 'Prueba de un módulo pequeño', 'Prueba de usuario', 'Prueba manual', 'b', 60, 'img_p13.png', NULL, '2025-11-14 08:09:03'),
(14, 2, 'Pruebas', '¿Qué herramienta sirve para automatizar pruebas?', 'Selenium', 'Photoshop', 'Excel', 'Unity', 'a', 60, 'img_p14.png', NULL, '2025-11-14 08:09:03'),
(15, 2, 'Diseño', '¿Qué es un Mockup?', 'Modelo visual de pantalla', 'Código', 'Caso de uso', 'Documento legal', 'a', 60, 'img_p15.png', NULL, '2025-11-14 08:09:03'),
(16, 2, 'Pruebas', '¿Qué mide una prueba de carga?', 'Rendimiento', 'Colores', 'Usuarios felices', 'RAM', 'a', 60, 'img_p16.png', NULL, '2025-11-14 08:09:03'),
(17, 2, 'Pruebas', '¿Qué prueba busca errores inesperados?', 'Caja negra', 'Caja blanca', 'Monkey testing', 'Prueba social', 'c', 60, 'img_p17.png', NULL, '2025-11-14 08:09:03'),
(18, 2, 'Diseño', '¿Qué figura aparece en un diagrama de clases?', 'Atributos', 'Usuarios', 'Sprints', 'Historias', 'a', 60, 'img_p18.png', NULL, '2025-11-14 08:09:03'),
(19, 2, 'Pruebas', '¿Qué es un bug?', 'Un éxito', 'Un error del sistema', 'Un requisito', 'Un botón', 'b', 60, 'img_p19.png', NULL, '2025-11-14 08:09:03'),
(20, 2, 'Diseño', '¿Qué es un wireframe?', 'Boceto funcional', 'Video', 'Prueba', 'Código SQL', 'a', 60, 'img_p20.png', NULL, '2025-11-14 08:09:03'),
(21, 2, 'Pruebas', '¿Qué prueba valida requisitos?', 'Aceptación', 'Carga', 'Funcional', 'Estres', 'a', 60, 'img_p21.png', NULL, '2025-11-14 08:09:03'),
(22, 2, 'Diseño', '¿Qué patrón organiza capas?', 'MVC', 'CRUD', 'DNS', 'SQL', 'a', 60, 'img_p22.png', NULL, '2025-11-14 08:09:03'),
(23, 2, 'Pruebas', '¿Qué prueba requiere usuario real?', 'Beta Testing', 'Unit Testing', 'Integración', 'Mock', 'a', 60, 'img_p23.png', NULL, '2025-11-14 08:09:03'),
(24, 3, 'Python', '¿Qué imprime print(2**3)?', '5', '6', '8', '9', 'c', 90, 'img_p24.png', NULL, '2025-11-14 08:09:32'),
(25, 3, 'Python', '¿Qué tipo es \"True\"?', 'String', 'Bool', 'List', 'Float', 'b', 90, 'img_p25.png', NULL, '2025-11-14 08:09:32'),
(26, 3, 'Python', '¿Qué estructura repite código?', 'if', 'for', 'return', 'print', 'b', 90, 'img_p26.png', NULL, '2025-11-14 08:09:32'),
(27, 3, 'Python', '¿Qué es una lista?', 'Colección mutable', 'Variable', 'Condición', 'Texto', 'a', 90, 'img_p27.png', NULL, '2025-11-14 08:09:32'),
(28, 3, 'BD', '¿Qué hace SELECT?', 'Inserta', 'Actualiza', 'Consulta datos', 'Elimina', 'c', 90, 'img_p28.png', NULL, '2025-11-14 08:09:32'),
(29, 3, 'BD', '¿Qué es una Primary Key?', 'Llave única', 'Llave duplicada', 'Llave falsa', 'Nada', 'a', 90, 'img_p29.png', NULL, '2025-11-14 08:09:32'),
(30, 3, 'Python', '¿Cuál crea una función?', 'func name', 'def name():', 'function name()', 'make name', 'b', 90, 'img_p30.png', NULL, '2025-11-14 08:09:32'),
(31, 3, 'BD', '¿Qué comando borra registros?', 'SELECT', 'DROP', 'DELETE', 'UPDATE', 'c', 90, 'img_p31.png', NULL, '2025-11-14 08:09:32'),
(32, 3, 'Python', '¿Qué es un diccionario?', 'Colección clave-valor', 'Lista', 'Texto', 'Condición', 'a', 90, 'img_p32.png', NULL, '2025-11-14 08:09:32'),
(33, 3, 'Python', '¿Qué imprime len(\"Hola\")?', '2', '3', '4', '5', 'c', 90, 'img_p33.png', NULL, '2025-11-14 08:09:32'),
(34, 3, 'BD', '¿Qué hace JOIN?', 'Une tablas', 'Elimina', 'Busca errores', 'Crea índices', 'a', 90, 'img_p34.png', NULL, '2025-11-14 08:09:32'),
(35, 3, 'Python', '¿Qué es una tupla?', 'Mutable', 'Inmutable', 'Doble', 'Condición', 'b', 90, 'img_p35.png', NULL, '2025-11-14 08:09:32'),
(36, 3, 'BD', '¿Qué hace INSERT?', 'Edita', 'Elimina', 'Agrega datos', 'Divide', 'c', 90, 'img_p36.png', NULL, '2025-11-14 08:09:32'),
(37, 3, 'Python', '¿Cuál es un comentario?', '# texto', '@texto', '//texto', '%%texto', 'a', 90, 'img_p37.png', NULL, '2025-11-14 08:09:32'),
(38, 3, 'BD', '¿Qué es SQL?', 'Un lenguaje de consultas', 'Un sistema operativo', 'Un tipo de archivo', 'Un navegador', 'a', 90, 'img_p38.png', NULL, '2025-11-14 08:09:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_sesion`
--

CREATE TABLE `preguntas_sesion` (
  `id` int(11) NOT NULL,
  `sesion_id` int(11) NOT NULL,
  `pregunta_id` int(11) NOT NULL,
  `respuesta_usuario` char(1) NOT NULL,
  `puntos_otorgados` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` int(11) NOT NULL,
  `jugador_id` int(11) NOT NULL,
  `puntaje_total` int(11) DEFAULT 0,
  `nivel_alcanzado` varchar(50) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `jugador_id`, `puntaje_total`, `nivel_alcanzado`, `fecha`) VALUES
(25, 2, 160, 'Básico', '2025-11-14 09:44:49'),
(26, 3, 240, 'Básico', '2025-11-14 09:58:21'),
(27, 3, 160, 'Básico', '2025-11-14 09:59:02'),
(28, 3, 360, 'Básico', '2025-11-14 10:00:57'),
(29, 3, 300, 'Intermedio', '2025-11-14 10:19:13'),
(30, 3, 720, 'Intermedio', '2025-11-14 10:21:48'),
(31, 3, 450, 'Alto', '2025-11-14 10:24:23'),
(32, 3, 720, 'Alto', '2025-11-14 10:32:08'),
(33, 2, 320, 'Básico', '2025-11-14 14:06:21'),
(34, 2, 240, 'Básico', '2025-11-14 14:07:50'),
(35, 2, 600, 'Intermedio', '2025-11-14 14:51:13'),
(36, 2, 0, 'Alto', '2025-11-14 15:05:20'),
(37, 2, 720, 'Alto', '2025-11-14 15:54:37'),
(38, 2, 450, 'Alto', '2025-11-14 15:59:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Jugador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones_juego`
--

CREATE TABLE `sesiones_juego` (
  `id` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  `nivel_id` int(11) NOT NULL,
  `iniciando_en` datetime NOT NULL DEFAULT current_timestamp(),
  `finalizado_en` datetime DEFAULT NULL,
  `puntos_ganados` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `rol_id` int(11) NOT NULL,
  `aprobado` tinyint(1) NOT NULL DEFAULT 0,
  `puntos` int(11) NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `email`, `password_hash`, `rol_id`, `aprobado`, `puntos`, `creado_en`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$cccIFvJKukRj52zSUy6ebOQGPpvkObEIkA1ri932TS4ahZfseOL3e', 1, 1, 0, '2025-11-14 08:50:49'),
(2, 'luiss', 'luis123@gmail.com', '$2y$10$Dvz1ak3iWLIq61NamEDBpOv3/gqKT4Cunx89fvtYHCZGTQAWnGEgm', 2, 1, 2490, '2025-11-14 08:58:35'),
(3, 'derly31', 'derlymedina@gmail.com', '$2y$10$4CaRRjYxAflr4meLYB93vugypJ7nxr2cTlW1y5xrKZTL3YbHmJx16', 2, 1, 2950, '2025-11-14 09:55:53');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `idx_preguntas_nivel` (`nivel_id`);

--
-- Indices de la tabla `preguntas_sesion`
--
ALTER TABLE `preguntas_sesion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sesion_id` (`sesion_id`),
  ADD KEY `pregunta_id` (`pregunta_id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jugador_id` (`jugador_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sesiones_juego`
--
ALTER TABLE `sesiones_juego`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario` (`usuario`),
  ADD KEY `nivel_id` (`nivel_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `preguntas`
--
ALTER TABLE `preguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `preguntas_sesion`
--
ALTER TABLE `preguntas_sesion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sesiones_juego`
--
ALTER TABLE `sesiones_juego`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `preguntas`
--
ALTER TABLE `preguntas`
  ADD CONSTRAINT `preguntas_ibfk_1` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`),
  ADD CONSTRAINT `preguntas_ibfk_2` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `preguntas_sesion`
--
ALTER TABLE `preguntas_sesion`
  ADD CONSTRAINT `preguntas_sesion_ibfk_1` FOREIGN KEY (`sesion_id`) REFERENCES `sesiones_juego` (`id`),
  ADD CONSTRAINT `preguntas_sesion_ibfk_2` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas` (`id`);

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`jugador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sesiones_juego`
--
ALTER TABLE `sesiones_juego`
  ADD CONSTRAINT `sesiones_juego_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `sesiones_juego_ibfk_2` FOREIGN KEY (`nivel_id`) REFERENCES `niveles` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
