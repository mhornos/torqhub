DROP DATABASE IF EXISTS torqhub;

CREATE DATABASE torqhub
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE torqhub;

CREATE TABLE usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(80) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('usuario','admin') NOT NULL DEFAULT 'usuario',
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE publicaciones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    contenido TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_publicaciones_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE comentarios_publicaciones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    publicacion_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    contenido TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comentarios_publicacion
        FOREIGN KEY (publicacion_id) REFERENCES publicaciones(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_comentarios_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE publicaciones_likes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    publicacion_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_like_publicacion
        FOREIGN KEY (publicacion_id) REFERENCES publicaciones(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_like_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE,

    UNIQUE KEY unique_like (publicacion_id, usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE vehiculos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  marca VARCHAR(60) NOT NULL,
  modelo VARCHAR(60) NOT NULL,
  any SMALLINT UNSIGNED NULL,
  vin VARCHAR(25) NULL,

  carroceria ENUM(
    'coche pequeño',
    'sedán',
    'familiar',
    'cabrio',
    'coupé',
    'suv/4x4',
    'monovolumen',
    'furgoneta',
    'otros'
  ) NULL,

  tipo_combustible ENUM(
    'gasolina',
    'diesel',
    'electrico',
    'electro/gasolina',
    'electro/diesel',
    'gas natural (CNG)',
    'etanol',
    'hidrogeno',
    'gas licuado (GLP)',
    'otros'
  ) NULL,

  tipo_cambio ENUM('automatico', 'manual') NULL,

  potencia_cv SMALLINT UNSIGNED NULL,
  cilindrada_cm3 SMALLINT UNSIGNED NULL,

  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_vehiculos_usuario
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE mantenimientos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  vehiculo_id INT UNSIGNED NOT NULL,
  fecha DATE NOT NULL,
  tipo VARCHAR(100) NOT NULL,
  descripcion TEXT NULL,
  kilometros INT UNSIGNED NULL,
  coste DECIMAL(10,2) NULL,
  fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_mantenimientos_vehiculo
    FOREIGN KEY (vehiculo_id) REFERENCES vehiculos(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE vehiculos
ADD COLUMN imagen VARCHAR(255) NULL AFTER cilindrada_cm3;

ALTER TABLE publicaciones
DROP COLUMN titulo;

ALTER TABLE publicaciones
ADD imagen VARCHAR(255) NULL AFTER contenido;

