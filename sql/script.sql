--establecer la codificación 
SET NAMES utf8mb4;
SET character_set_client = 'utf8mb4';
SET character_set_connection = 'utf8mb4';
SET character_set_results = 'utf8mb4';
SET collation_connection = 'utf8mb4_bin';

--crear la base de datos 
CREATE DATABASE IF NOT EXISTS StarDust
CHARACTER SET utf8mb4
COLLATE utf8mb4_bin;

--seleccionar la base de datos creada
USE StarDust;

--crear la tabla 'users'
CREATE TABLE IF NOT EXISTS users (
    iduser INT AUTO_INCREMENT PRIMARY KEY,                          -- Clave primaria autoincrementable
    mail VARCHAR(40) NOT NULL UNIQUE,                               -- Correo único
    username VARCHAR(16) NOT NULL UNIQUE,                           -- Nombre de usuario único
    passHash VARCHAR(60) NOT NULL,                                  -- Hash de la contraseña
    userFirstName VARCHAR(60) NOT NULL,                             -- Nombre del usuario
    userLastName VARCHAR(120) NOT NULL,                             -- Apellido del usuario
    creationDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,       -- Fecha de creación
    removeDate DATETIME DEFAULT NULL,                               -- Fecha de eliminación (puede ser NULL)
    lastSignIn DATETIME DEFAULT NULL,                               -- Último inicio de sesión
    active TINYINT(1) NOT NULL DEFAULT 1,                           -- Indica si el usuario está activo

    --nuevos campos_1

    activationDate DATETIME,                                        -- Fecha de activacion de la cuenta
    activationCode VARCHAR(64),                                     -- Codigo de activacion
    resetPassExpiry DATETIME,                                       -- Fecha de pass expiry
    resetPassCode VARCHAR(64),                                      -- Codigo de reset pass

    --nuevos campos_2

    imatgeDePerfil BLOB,
    biografia VARCHAR(150),
    ubicacio VARCHAR(20),
    dataNaix DATE,
    -- publicaciones

) ENGINE=InnoDB


CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    contenido TEXT NOT NULL,
    imagen VARCHAR(255) DEFAULT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    qttLikes INT, -- 0 de base
    archivo VARCHAR(255) NULL,
    FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE post_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    username VARCHAR(50) NOT NULL,
    reaction ENUM('like', 'meh', 'dislike') NOT NULL DEFAULT 'like',
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE,
    UNIQUE (post_id, username) -- Evita duplicados
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CHARACTER SET utf8mb4
COLLATE utf8mb4_bin;