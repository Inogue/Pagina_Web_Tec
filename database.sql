CREATE DATABASE IF NOT EXISTS paginaweb;

drop table if exists passwords;
drop table if exists messages;
drop table if exists 'users';

create table `users`(id_user BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, username VARCHAR(64) NOT NULL, hash_password VARCHAR(255) NOT NULL);
create table passwords (id_password INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, password VARCHAR(32) NOT NULL, id_user BIGINT UNSIGNED NOT NULL, FOREIGN KEY(id_user) REFERENCES users (id_user));
create table messages (id_message INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, messages VARCHAR(192) NOT NULL, id_user BIGINT UNSIGNED NOT NULL, FOREIGN KEY(id_user) REFERENCES users (id_user));
