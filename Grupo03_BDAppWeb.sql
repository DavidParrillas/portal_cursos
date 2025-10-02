CREATE TABLE `usuarios` (
  `id_usuario` int PRIMARY KEY AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `correo` varchar(150) UNIQUE NOT NULL,
  `contrasena_hash` varchar(255) NOT NULL,
  `estado` ENUM ('ACTIVO', 'INACTIVO') NOT NULL DEFAULT 'ACTIVO',
  `creado_en` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `actualizado_en` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `roles` (
  `id_rol` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(30) UNIQUE NOT NULL,
  `nombre` varchar(60) NOT NULL
);

CREATE TABLE `usuarios_roles` (
  `id_usuario` int NOT NULL,
  `id_rol` int NOT NULL
);

CREATE TABLE `cursos` (
  `id_curso` int PRIMARY KEY AUTO_INCREMENT,
  `id_instructor` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `slug` varchar(170) UNIQUE NOT NULL,
  `descripcion` text NOT NULL,
  `duracion` varchar(50),
  `modalidad` ENUM ('PRESENCIAL', 'VIRTUAL', 'HIBRIDA') NOT NULL DEFAULT 'VIRTUAL',
  `precio` decimal(10,2) NOT NULL DEFAULT 0,
  `fecha_inicio` date,
  `cupos` int NOT NULL DEFAULT 0,
  `estado` ENUM ('BORRADOR', 'PENDIENTE', 'PUBLICADO', 'ARCHIVADO') NOT NULL DEFAULT 'BORRADOR',
  `creado_en` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `actualizado_en` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `categorias` (
  `id_categoria` int PRIMARY KEY AUTO_INCREMENT,
  `nombre` varchar(80) UNIQUE NOT NULL,
  `slug` varchar(100) UNIQUE NOT NULL
);

CREATE TABLE `cursos_categorias` (
  `id_curso` int NOT NULL,
  `id_categoria` int NOT NULL
);

CREATE TABLE `materiales` (
  `id_material` int PRIMARY KEY AUTO_INCREMENT,
  `id_curso` int NOT NULL,
  `id_usuario` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `creado_en` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `inscripciones` (
  `id_inscripcion` int PRIMARY KEY AUTO_INCREMENT,
  `id_curso` int NOT NULL,
  `id_estudiante` int NOT NULL,
  `fecha_inscrito` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `pagos` (
  `id_pago` int PRIMARY KEY AUTO_INCREMENT,
  `id_inscripcion` int NOT NULL,
  `proveedor` ENUM ('PAYPAL_SANDBOX') NOT NULL DEFAULT 'PAYPAL_SANDBOX',
  `estado` ENUM ('PENDIENTE', 'APROBADO', 'FALLIDO', 'REEMBOLSADO') NOT NULL DEFAULT 'PENDIENTE',
  `monto` decimal(10,2) NOT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'USD',
  `id_transaccion` varchar(120),
  `url_recibo` varchar(255),
  `creado_en` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `actualizado_en` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `resenas` (
  `id_resena` int PRIMARY KEY AUTO_INCREMENT,
  `id_curso` int NOT NULL,
  `id_estudiante` int NOT NULL,
  `calificacion` int NOT NULL,
  `comentario` text,
  `creado_en` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE UNIQUE INDEX `usuarios_roles_index_0` ON `usuarios_roles` (`id_usuario`, `id_rol`);

CREATE UNIQUE INDEX `cursos_index_1` ON `cursos` (`id_instructor`, `titulo`);

CREATE UNIQUE INDEX `cursos_categorias_index_2` ON `cursos_categorias` (`id_curso`, `id_categoria`);

CREATE UNIQUE INDEX `inscripciones_index_3` ON `inscripciones` (`id_curso`, `id_estudiante`);

CREATE UNIQUE INDEX `pagos_index_4` ON `pagos` (`id_transaccion`);

CREATE UNIQUE INDEX `resenas_index_5` ON `resenas` (`id_curso`, `id_estudiante`);

ALTER TABLE `usuarios_roles` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

ALTER TABLE `usuarios_roles` ADD FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

ALTER TABLE `cursos` ADD FOREIGN KEY (`id_instructor`) REFERENCES `usuarios` (`id_usuario`);

ALTER TABLE `cursos_categorias` ADD FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

ALTER TABLE `cursos_categorias` ADD FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

ALTER TABLE `materiales` ADD FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

ALTER TABLE `materiales` ADD FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

ALTER TABLE `inscripciones` ADD FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

ALTER TABLE `inscripciones` ADD FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id_usuario`);

ALTER TABLE `pagos` ADD FOREIGN KEY (`id_inscripcion`) REFERENCES `inscripciones` (`id_inscripcion`);

ALTER TABLE `resenas` ADD FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`);

ALTER TABLE `resenas` ADD FOREIGN KEY (`id_estudiante`) REFERENCES `usuarios` (`id_usuario`);
