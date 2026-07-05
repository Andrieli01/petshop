CREATE DATABASE IF NOT EXISTS petshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE petshop;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS especies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    especie VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nascimento DATE NOT NULL,
    especie_id INT NOT NULL,
    prontuario TEXT,
    genero VARCHAR(10),
    FOREIGN KEY (especie_id) REFERENCES especies(id)
);

INSERT INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@petshop.com', '$2y$10$tANdzZrIrwplHTvr7GaNg.3Fc8ivAcYy/4apKJyo.6nQ8VBKCRiBO');
