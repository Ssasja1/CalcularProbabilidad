--Esta es la estructura de la base de datos que se va a utilizar en el proyecto
-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS sistema_calificaciones;
USE sistema_calificaciones;

-- Crear la tabla 'usuario'
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(10) NOT NULL AFTER id_usuario,  -- Agrega matricula después de id_usuario
    cuatrimestre INT NOT NULL AFTER matricula,        -- Agrega cuatrimestre después de matricula
    carrera VARCHAR(50) NOT NULL AFTER cuatrimestre, -- Agrega carrera después de cuatrimestre
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50) NOT NULL
);

-- Crear la tabla 'materia'
CREATE TABLE materia (
    id_materia INT AUTO_INCREMENT PRIMARY KEY,
    nombre_materia VARCHAR(100) NOT NULL,
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

-- Crear la tabla 'calificaciones' (con campos que aceptan NULL)
CREATE TABLE calificaciones (
    id_calificacion INT AUTO_INCREMENT PRIMARY KEY,
    id_materia INT,
    primer_parcial DECIMAL(5, 2) NULL,  -- Permite NULL
    segundo_parcial DECIMAL(5, 2) NULL,  -- Permite NULL
    tercer_parcial DECIMAL(5, 2) NULL,   -- Permite NULL
    FOREIGN KEY (id_materia) REFERENCES materia(id_materia) ON DELETE CASCADE
);