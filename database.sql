CREATE DATABASE IF NOT EXISTS paginaweb;
USE paginaweb;

drop table if exists messages;
drop table if exists users;

create table users (id_user BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(64) NOT NULL, email VARCHAR(255) NOT NULL UNIQUE, hash_password VARCHAR(255) NOT NULL);
CREATE TABLE messages (
    id_message INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    messages VARCHAR(192) NOT NULL,  -- Mensaje (encriptado o no)
    is_encrypted BOOLEAN NOT NULL DEFAULT 0,  -- 0 = mensaje no encriptado, 1 = mensaje encriptado
    id_user BIGINT UNSIGNED NOT NULL,  -- ID del usuario
    FOREIGN KEY(id_user) REFERENCES users(id_user)  -- Relación con el usuario
);

CREATE USER 'enti'@'localhost' IDENTIFIED BY 'enti';

GRANT ALL PRIVILEGES ON paginaweb.* TO 'enti'@'localhost';

FLUSH PRIVILEGES;
