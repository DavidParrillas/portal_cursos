CREATE TABLE IF NOT EXISTS usuarios (id INT AUTO_INCREMENT PRIMARY KEY,nombre VARCHAR(120) NOT NULL,email VARCHAR(120) UNIQUE NOT NULL,pass_hash VARCHAR(255) NOT NULL,rol ENUM('admin','docente','estudiante') DEFAULT 'estudiante');
CREATE TABLE IF NOT EXISTS cursos (id INT AUTO_INCREMENT PRIMARY KEY,titulo VARCHAR(200) NOT NULL,descripcion TEXT);
CREATE TABLE IF NOT EXISTS secciones (id INT AUTO_INCREMENT PRIMARY KEY,curso_id INT NOT NULL,titulo VARCHAR(200) NOT NULL,orden INT DEFAULT 0,FOREIGN KEY (curso_id) REFERENCES cursos(id));
CREATE TABLE IF NOT EXISTS clases (id INT AUTO_INCREMENT PRIMARY KEY,seccion_id INT NOT NULL,titulo VARCHAR(200) NOT NULL,descripcion TEXT,youtube_url VARCHAR(300),archivo_url VARCHAR(300),orden INT DEFAULT 0,FOREIGN KEY (seccion_id) REFERENCES secciones(id));
CREATE TABLE IF NOT EXISTS inscripciones (id INT AUTO_INCREMENT PRIMARY KEY,usuario_id INT NOT NULL,curso_id INT NOT NULL,UNIQUE(usuario_id, curso_id),FOREIGN KEY (usuario_id) REFERENCES usuarios(id),FOREIGN KEY (curso_id) REFERENCES cursos(id));
CREATE TABLE IF NOT EXISTS progreso_clase (id INT AUTO_INCREMENT PRIMARY KEY,usuario_id INT NOT NULL,clase_id INT NOT NULL,completada TINYINT(1) DEFAULT 0,UNIQUE(usuario_id, clase_id),FOREIGN KEY (usuario_id) REFERENCES usuarios(id),FOREIGN KEY (clase_id) REFERENCES clases(id));
INSERT INTO cursos (titulo, descripcion) VALUES ('Programación Web I','Curso de ejemplo') ON DUPLICATE KEY UPDATE descripcion=VALUES(descripcion);
SET @curso_id = (SELECT id FROM cursos WHERE titulo='Programación Web I' LIMIT 1);
INSERT INTO secciones (curso_id,titulo,orden) VALUES (@curso_id,'Unidad 1: Intro',1),(@curso_id,'Unidad 2: PHP',2);
SET @s1 = (SELECT id FROM secciones WHERE curso_id=@curso_id AND titulo='Unidad 1: Intro' LIMIT 1);
SET @s2 = (SELECT id FROM secciones WHERE curso_id=@curso_id AND titulo='Unidad 2: PHP' LIMIT 1);
INSERT INTO clases (seccion_id,titulo,descripcion,youtube_url,orden) VALUES
(@s1,'Bienvenida','Descripción de la clase','https://www.youtube.com/watch?v=dQw4w9WgXcQ',1),
(@s2,'PHP Básico','Sintaxis y ejemplos','https://www.youtube.com/watch?v=oHg5SJYRHA0',1);