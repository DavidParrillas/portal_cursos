-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-10-2025 a las 20:38:48
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
-- Base de datos: `portal_cursos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `slug` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `slug`) VALUES
(1, 'Tecnología', 'tecnologia'),
(2, 'Negocios', 'negocios'),
(3, 'Diseño', 'diseno'),
(4, 'Marketing', 'marketing'),
(5, 'Desarrollo Personal', 'desarrollo-personal'),
(6, 'Idiomas', 'idiomas'),
(7, 'Salud y Bienestar', 'salud-bienestar'),
(8, 'Artes', 'artes'),
(9, 'Ciencias', 'ciencias'),
(10, 'Educación', 'educacion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `id_instructor` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `slug` varchar(170) NOT NULL,
  `portada` varchar(255) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `duracion` varchar(50) DEFAULT NULL,
  `modalidad` enum('PRESENCIAL','VIRTUAL','HIBRIDA') NOT NULL DEFAULT 'VIRTUAL',
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_inicio` date DEFAULT NULL,
  `cupos` int(11) NOT NULL DEFAULT 0,
  `estado` enum('BORRADOR','PENDIENTE','PUBLICADO','ARCHIVADO') NOT NULL DEFAULT 'BORRADOR',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `id_categoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `id_instructor`, `titulo`, `slug`, `portada`, `descripcion`, `duracion`, `modalidad`, `precio`, `fecha_inicio`, `cupos`, `estado`, `creado_en`, `actualizado_en`, `id_categoria`) VALUES
(11, 12, 'React', 'react', '/portal_cursos/uploads/portadas/portada_11_1760125658.png', 'React es una biblioteca de Javascript para crear interfaces web de usuario, tipicamente se usa para crear aplicaciones web frontend.\r\nEn este curso de React aprenderás las bases necesarias de React como componentes (Components), props, estado (useState), hooks, estilos, useContext, useEffect, ademas de usar otras herramientas como create-react-app, vitejs, tailwindcss, react-icons y otras mas bibliotecas de npm (Nodejs).\r\n\r\nEste un curso enfocado en principiantes asi que si no sabes nada de React, este es tu punto de partida.', '1 sección, 1 clase, 4 horas', 'VIRTUAL', 23.00, '2025-09-30', 5, 'PUBLICADO', '2025-10-10 13:47:38', '2025-10-10 15:02:24', 1),
(12, 12, 'Node JS', 'node-js', '/portal_cursos/uploads/portadas/portada_12_1760125773.png', 'Aprende todo lo que necesitas saber sobre Node.js. En esta Capítulo 1, conocerás realmente lo que es, cómo funciona, haremos ejercicios interactivos, veremos su sistema de módulos desde cero y muchas cosas más.', '1 sección, 1 clase, 4 horas', 'VIRTUAL', 23.00, '2025-10-17', 5, 'PUBLICADO', '2025-10-10 13:49:33', '2025-10-10 15:02:43', 1),
(13, 12, 'Curo Docker', 'curo-docker', '/portal_cursos/uploads/portadas/portada_13_1760205248.png', 'Primer episodio del curso de #docker,  se introduce docker, sus ventajas, diferencias frente a las máquinas virtuales y el estándar OCI.', '3 secciónes, 4 clases, 4 horas', 'VIRTUAL', 34.00, '2025-10-15', 45, 'PUBLICADO', '2025-10-11 11:54:08', '2025-10-11 12:25:39', 1),
(15, 13, 'Curso de guitarra intermedio', 'curso-de-guitarra-intermedio', '/portal_cursos/uploads/portadas/portada_15_1760206952.jpg', 'Este Curso de guitarra intermedio también está disponible en nuestra plataforma, donde aparte de la lección se encuentran los pdf de descargar, los ejercicios de cada lección y las rutinas de práctica', '2 secciónes, 3 clases, 5 horas', 'VIRTUAL', 34.00, '2025-10-07', 0, 'PUBLICADO', '2025-10-11 12:22:32', '2025-10-11 12:26:30', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id_inscripcion` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `fecha_inscrito` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales`
--

CREATE TABLE `materiales` (
  `id_material` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materiales`
--

INSERT INTO `materiales` (`id_material`, `id_curso`, `id_usuario`, `titulo`, `ruta_archivo`, `creado_en`) VALUES
(12, 11, 12, 'Curso de Reactjs desde Cero para principiantes 2022', 'https://youtu.be/GoGGSc-LuZE?si=vMV-tRitcfdT_owr', '2025-10-10 13:47:38'),
(13, 11, 12, 'react', '/portal_cursos/uploads/materiales/material_11_1760125658_0.png', '2025-10-10 13:47:38'),
(14, 12, 12, 'Introducción y primeros pasos', 'https://youtu.be/yB4n_K7dZV8?si=GwCdLF5JO3cfa64K', '2025-10-10 13:49:33'),
(15, 12, 12, 'Desarrollando una API con Express desde cero', 'https://youtu.be/YmZE1HXjpd4?si=5g9E8ychwH10T1xD', '2025-10-10 13:49:33'),
(16, 12, 12, '1_uMw2nMkXV3DuTmIUEKO7Xg', '/portal_cursos/uploads/materiales/material_12_1760125773_0.png', '2025-10-10 13:49:33'),
(17, 13, 12, '1. Introducción, docker vs VMs, OCI, casos de uso... - Curso Docker gratuito y en español', 'https://youtu.be/AquOM-ISsnA?si=7zMshsWvWzLEDxW5', '2025-10-11 11:54:08'),
(18, 13, 12, '2. Instalación Docker Desktop y Engine en Windows, Mac y Linux - Curso docker gratuito y en español', 'https://youtu.be/obALwLV-49U?si=PL5V43rUearrI5Q8', '2025-10-11 11:54:08'),
(19, 13, 12, '3. Conceptos básicos, contenedor, imagen, dockerfile, dockerhub - Curso docker gratuito en español', 'https://youtu.be/cWm3_PZR7Os?si=AEMSdvDIH6yqOL9F', '2025-10-11 11:54:08'),
(20, 13, 12, 'docker', '/portal_cursos/uploads/materiales/material_13_1760205248_0.png', '2025-10-11 11:54:08'),
(24, 15, 13, 'Curso de guitarra intermedio - Lección 1 - ACORDES BÁSICOS', 'https://youtu.be/mNu5qRpRsqU?si=UZa51MLaQ0EevSrv', '2025-10-11 12:22:32'),
(25, 15, 13, 'Curso de guitarra intermedio - Lección 2 - ACORDES CON CEJILLA', 'https://youtu.be/HtXbU5lVcFQ?si=Yb5aUVMLfFhra1IS', '2025-10-11 12:22:32'),
(26, 15, 13, 'tipos-de-guitarra-electrica', '/portal_cursos/uploads/materiales/material_15_1760206952_0.jpg', '2025-10-11 12:22:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_inscripcion` int(11) NOT NULL,
  `proveedor` enum('PAYPAL_SANDBOX') NOT NULL DEFAULT 'PAYPAL_SANDBOX',
  `estado` enum('PENDIENTE','APROBADO','FALLIDO','REEMBOLSADO') NOT NULL DEFAULT 'PENDIENTE',
  `monto` decimal(10,2) NOT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'USD',
  `id_transaccion` varchar(120) DEFAULT NULL,
  `url_recibo` varchar(255) DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id_resena` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `calificacion` int(11) NOT NULL,
  `comentario` text DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `nombre` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `codigo`, `nombre`) VALUES
(1, 'estudiante', 'Estudiante'),
(2, 'instructor', 'Instructor'),
(3, 'administrador', 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `estado` enum('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `actualizado_en` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_completo`, `correo`, `contrasena_hash`, `estado`, `creado_en`, `actualizado_en`) VALUES
(7, 'David', 'david@gmail.com', '$2y$10$i3i1X456INgw8ksMXI9V1.67kTO7NsaY6ZW8oUbs4SIY0fhRl4e8u', 'ACTIVO', '2025-10-02 14:12:07', '2025-10-02 14:12:07'),
(9, 'Administrador', 'admin@gmail.com', '$2y$10$svHdSxfc4ZXBjTNKA4./XehKIy8r25yoYA841Q7bofTgcIpjQ9DOe', 'ACTIVO', '2025-10-02 14:48:31', '2025-10-02 14:48:31'),
(11, 'alex1', 'alex@gmial.com', '$2y$10$eYCHnl675CfExhZgOxtkoeYF.Fb8OUxaC.5SGORMlb1zvPwHIA5zC', 'ACTIVO', '2025-10-02 17:21:10', '2025-10-02 17:21:10'),
(12, 'tutor', 'tuto@gmail.com', '$2y$10$AJGl8q3OrHfdwBrRF8J2v.WlQQqGzWnqPn2ThE1LuW1njs6j0yLRK', 'ACTIVO', '2025-10-03 18:57:26', '2025-10-03 18:57:26'),
(13, 'Mario', 'mario@gmail.com', '$2y$10$PJZZRx31gUpr91n.JSDZluDEPy2ist39ktJUMQApmfvOIU1zOW39G', 'ACTIVO', '2025-10-11 12:13:34', '2025-10-11 12:13:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_roles`
--

CREATE TABLE `usuarios_roles` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_roles`
--

INSERT INTO `usuarios_roles` (`id_usuario`, `id_rol`) VALUES
(7, 1),
(9, 3),
(11, 1),
(12, 2),
(13, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `cursos_index_1` (`id_instructor`,`titulo`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD UNIQUE KEY `inscripciones_index_3` (`id_curso`,`id_estudiante`),
  ADD KEY `id_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`id_material`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD UNIQUE KEY `pagos_index_4` (`id_transaccion`),
  ADD KEY `id_inscripcion` (`id_inscripcion`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id_resena`),
  ADD UNIQUE KEY `resenas_index_5` (`id_curso`,`id_estudiante`),
  ADD KEY `id_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD UNIQUE KEY `usuarios_roles_index_0` (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id_resena` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_instructor`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `cursos_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL;

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`),
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD CONSTRAINT `materiales_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`),
  ADD CONSTRAINT `materiales_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_inscripcion`) REFERENCES `inscripciones` (`id_inscripcion`);

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`),
  ADD CONSTRAINT `resenas_ibfk_2` FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `usuarios_roles`
--
ALTER TABLE `usuarios_roles`
  ADD CONSTRAINT `usuarios_roles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `usuarios_roles_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
