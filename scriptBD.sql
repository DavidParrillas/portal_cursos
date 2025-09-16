-- =========================================================
-- Portal Web de Cursos Cortos y Talleres - Script MySQL/MariaDB (Etapa 2)
-- Probado en MySQL 8.0+ y MariaDB 10.4+ (XAMPP)
-- =========================================================

-- 1) Crear BD (borra si existe)
DROP DATABASE IF EXISTS portal_cursos;
CREATE DATABASE portal_cursos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portal_cursos;

-- 2) Tablas de usuarios
CREATE TABLE estudiante (
  id_estudiante INT AUTO_INCREMENT PRIMARY KEY,
  nombre        VARCHAR(100) NOT NULL,
  correo        VARCHAR(100) NOT NULL UNIQUE,
  contrasena    VARCHAR(255) NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE instructor (
  id_instructor INT AUTO_INCREMENT PRIMARY KEY,
  nombre        VARCHAR(100) NOT NULL,
  correo        VARCHAR(100) NOT NULL UNIQUE,
  contrasena    VARCHAR(255) NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE administrador (
  id_admin   INT AUTO_INCREMENT PRIMARY KEY,
  nombre     VARCHAR(100) NOT NULL,
  correo     VARCHAR(100) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3) Catálogo de categorías
CREATE TABLE categoria (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre       VARCHAR(100) NOT NULL UNIQUE,
  descripcion  VARCHAR(255)
) ENGINE=InnoDB;

-- 4) Cursos (con aprobación previa y relación a instructor/categoría)
CREATE TABLE curso (
  id_curso      INT AUTO_INCREMENT PRIMARY KEY,
  id_instructor INT NOT NULL,
  id_categoria  INT NOT NULL,
  titulo        VARCHAR(150) NOT NULL,
  descripcion   TEXT,
  duracion      VARCHAR(50),
  modalidad     ENUM('presencial','virtual') DEFAULT 'virtual',
  precio        DECIMAL(10,2) DEFAULT 0.00,
  fecha_inicio  DATE,
  cupos         INT DEFAULT 0,
  aprobado      TINYINT(1) NOT NULL DEFAULT 0, -- 0=pending, 1=aprobado
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_curso_instructor
    FOREIGN KEY (id_instructor) REFERENCES instructor(id_instructor)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_curso_categoria
    FOREIGN KEY (id_categoria)  REFERENCES categoria(id_categoria)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE INDEX idx_curso_titulo ON curso(titulo);
CREATE INDEX idx_curso_modalidad ON curso(modalidad);
CREATE INDEX idx_curso_fecha ON curso(fecha_inicio);

-- 5) Inscripciones
CREATE TABLE inscripcion (
  id_inscripcion INT AUTO_INCREMENT PRIMARY KEY,
  id_curso       INT NOT NULL,
  id_estudiante  INT NOT NULL,
  fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  estado ENUM('activa','cancelada','finalizada') DEFAULT 'activa',
  CONSTRAINT fk_insc_curso
    FOREIGN KEY (id_curso) REFERENCES curso(id_curso)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_insc_estudiante
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  UNIQUE KEY uq_inscripcion_unica (id_curso, id_estudiante)
) ENGINE=InnoDB;

-- 6) Pagos (soporte PayPal Sandbox / comprobante)
CREATE TABLE pago (
  id_pago        INT AUTO_INCREMENT PRIMARY KEY,
  id_inscripcion INT NOT NULL,
  proveedor      ENUM('paypal_sandbox') NOT NULL DEFAULT 'paypal_sandbox',
  transaction_id VARCHAR(100) NOT NULL UNIQUE,
  monto          DECIMAL(10,2) NOT NULL,
  moneda         VARCHAR(10) NOT NULL DEFAULT 'USD',
  estado         ENUM('PENDIENTE','COMPLETADO','FALLIDO') NOT NULL DEFAULT 'PENDIENTE',
  fecha_pago     TIMESTAMP NULL,
  comprobante_url VARCHAR(255) NULL,
  CONSTRAINT fk_pago_inscripcion
    FOREIGN KEY (id_inscripcion) REFERENCES inscripcion(id_inscripcion)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7) Materiales
CREATE TABLE material (
  id_material   INT AUTO_INCREMENT PRIMARY KEY,
  id_curso      INT NOT NULL,
  id_instructor INT NOT NULL,
  titulo        VARCHAR(150) NOT NULL,
  archivo       VARCHAR(255) NOT NULL,  -- ruta/nombre del archivo
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_material_curso
    FOREIGN KEY (id_curso) REFERENCES curso(id_curso)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_material_instructor
    FOREIGN KEY (id_instructor) REFERENCES instructor(id_instructor)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 8) Reseñas (1..n por estudiante y curso)
-- NOTA: evitamos CHECK para compatibilidad amplia; se valida en app o con trigger opcional
CREATE TABLE resena (
  id_resena     INT AUTO_INCREMENT PRIMARY KEY,
  id_estudiante INT NOT NULL,
  id_curso      INT NOT NULL,
  comentario    TEXT,
  calificacion  TINYINT NOT NULL,  -- 1..5
  fecha         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_resena_estudiante
    FOREIGN KEY (id_estudiante) REFERENCES estudiante(id_estudiante)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_resena_curso
    FOREIGN KEY (id_curso) REFERENCES curso(id_curso)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_resena_calificacion ON resena(calificacion);

-- (Opcional) Trigger para validar calificación 1..5 en MariaDB/MySQL antiguos
DELIMITER $$
CREATE TRIGGER trg_resena_check_ins
BEFORE INSERT ON resena
FOR EACH ROW
BEGIN
  IF NEW.calificacion < 1 OR NEW.calificacion > 5 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'calificacion debe estar entre 1 y 5';
  END IF;
END$$
CREATE TRIGGER trg_resena_check_upd
BEFORE UPDATE ON resena
FOR EACH ROW
BEGIN
  IF NEW.calificacion < 1 OR NEW.calificacion > 5 THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'calificacion debe estar entre 1 y 5';
  END IF;
END$$
DELIMITER ;

-- 9) Vista simple de reportería
CREATE OR REPLACE VIEW vw_inscripciones_por_curso AS
SELECT
  c.id_curso,
  c.titulo,
  c.cupos,
  COUNT(i.id_inscripcion) AS inscritos
FROM curso c
LEFT JOIN inscripcion i 
  ON i.id_curso = c.id_curso AND i.estado <> 'cancelada'
GROUP BY c.id_curso, c.titulo, c.cupos;

-- 10) Datos semilla mínimos
INSERT INTO categoria (nombre, descripcion) VALUES
  ('Programación', 'Cursos de desarrollo de software'),
  ('Diseño', 'UI/UX y diseño gráfico'),
  ('Negocios', 'Marketing y emprendimiento');

INSERT INTO instructor (nombre, correo, contrasena) VALUES
  ('Ana Instructora', 'ana@demo.com', '123'), -- usa hash real en producción
  ('Luis Mentor', 'luis@demo.com', '123');

INSERT INTO administrador (nombre, correo, contrasena) VALUES
  ('Admin General', 'admin@demo.com', '123');

INSERT INTO estudiante (nombre, correo, contrasena) VALUES
  ('María Alumna', 'maria@demo.com', '123'),
  ('Carlos Estudiante', 'carlos@demo.com', '123');

INSERT INTO curso (id_instructor, id_categoria, titulo, descripcion, duracion, modalidad, precio, fecha_inicio, cupos, aprobado)
VALUES
  (1, 1, 'Introducción a PHP', 'Fundamentos de PHP y MySQL', '20h', 'virtual', 49.99, '2025-10-01', 30, 1),
  (2, 2, 'Figma a HTML/CSS', 'Maquetación web moderna', '12h', 'virtual', 39.99, '2025-10-10', 25, 1);

INSERT INTO inscripcion (id_curso, id_estudiante, estado) VALUES
  (1, 1, 'activa'),
  (1, 2, 'activa'),
  (2, 1, 'activa');

INSERT INTO pago (id_inscripcion, transaction_id, monto, moneda, estado, fecha_pago, comprobante_url) VALUES
  (1, 'PAYPALTEST-0001', 49.99, 'USD', 'COMPLETADO', NOW(), 'https://sandbox.paypal.com/tx/PAYPALTEST-0001');

INSERT INTO material (id_curso, id_instructor, titulo, archivo) VALUES
  (1, 1, 'Diapositivas Clase 1', 'uploads/curso1/clase1.pdf');

INSERT INTO resena (id_estudiante, id_curso, comentario, calificacion) VALUES
  (1, 1, 'Muy buen curso', 5),
  (2, 1, 'Claro y práctico', 4);