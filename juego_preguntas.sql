-- ==========================================
-- BASE DE DATOS: juego_preguntas
-- Sistema de juego de preguntas con niveles
-- ==========================================

CREATE DATABASE IF NOT EXISTS juego_preguntas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE juego_preguntas;

-- --------------------------
-- TABLA: roles
-- --------------------------
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL
);

INSERT INTO roles (nombre) VALUES ('Administrador'), ('Jugador');

-- --------------------------
-- TABLA: niveles
-- --------------------------
CREATE TABLE niveles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  puntos_minimos INT NOT NULL DEFAULT 0
);

INSERT INTO niveles (nombre, puntos_minimos) VALUES
('Básico', 0),
('Intermedio', 500),
('Avanzado', 1200);

-- --------------------------
-- TABLA: jugadores
-- --------------------------
CREATE TABLE jugadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol_id INT DEFAULT 2,
  aprobado TINYINT(1) DEFAULT 0,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- Crear usuario administrador por defecto
INSERT INTO jugadores (nombre, correo, password, rol_id, aprobado)
VALUES ('Administrador', 'admin@correo.com', '$2y$10$2dK7H7En3RkWyAbPQpVh6uFvZxk3e1k4eeGq5E6fYgO7GmjX0DJhC', 1, 1);
-- Contraseña: 1234

-- --------------------------
-- TABLA: preguntas
-- --------------------------
CREATE TABLE preguntas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  texto TEXT NOT NULL,
  nivel_id INT NOT NULL,
  opcion_a VARCHAR(255) NOT NULL,
  opcion_b VARCHAR(255) NOT NULL,
  opcion_c VARCHAR(255) NOT NULL,
  opcion_d VARCHAR(255) NOT NULL,
  opcion_correcta ENUM('a','b','c','d') NOT NULL,
  puntos INT DEFAULT 50,
  imagen VARCHAR(255) NULL,
  FOREIGN KEY (nivel_id) REFERENCES niveles(id)
);

-- --------------------------
-- TABLA: reportes
-- --------------------------
CREATE TABLE reportes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jugador_id INT NOT NULL,
  pregunta_id INT NOT NULL,
  correcta TINYINT(1) DEFAULT 0,
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (jugador_id) REFERENCES jugadores(id),
  FOREIGN KEY (pregunta_id) REFERENCES preguntas(id)
);

-- --------------------------
-- TABLA: resultados (puntajes finales)
-- --------------------------
CREATE TABLE resultados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  nivel_id INT NOT NULL,
  puntaje_total INT DEFAULT 0,
  fecha_juego DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES jugadores(id),
  FOREIGN KEY (nivel_id) REFERENCES niveles(id)
);

-- --------------------------
-- TABLA: sesiones_juego
-- --------------------------
CREATE TABLE sesiones_juego (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jugador_id INT NOT NULL,
  nivel_id INT NOT NULL,
  inicio_en DATETIME DEFAULT CURRENT_TIMESTAMP,
  finalizado_en DATETIME NULL,
  FOREIGN KEY (jugador_id) REFERENCES jugadores(id),
  FOREIGN KEY (nivel_id) REFERENCES niveles(id)
);

-- --------------------------
-- DATOS DE PRUEBA (preguntas básicas)
-- --------------------------
INSERT INTO preguntas (texto, nivel_id, opcion_a, opcion_b, opcion_c, opcion_d, opcion_correcta, puntos)
VALUES
('¿Qué es Scrum?', 1, 'Un tipo de lenguaje de programación', 'Un método ágil de desarrollo de software', 'Una herramienta de bases de datos', 'Un sistema operativo', 'b', 50),
('¿Qué representa el Product Owner en Scrum?', 1, 'El cliente o responsable del producto', 'El líder técnico', 'El diseñador', 'El probador de software', 'a', 50),
('¿Qué es un sprint en Scrum?', 1, 'Una fase de mantenimiento', 'Una reunión de equipo', 'Un periodo de trabajo con metas específicas', 'Un error de código', 'c', 50);
