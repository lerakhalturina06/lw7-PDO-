-- users.sql
CREATE DATABASE IF NOT EXISTS test_database 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE test_database;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_email ON users(email);

-- Пример данных для тестирования
INSERT INTO users (name, email) VALUES
('Иван Иванов', 'ivan@example.com'),
('Петр Петров', 'petr@example.com'),
('Мария Сидорова', 'maria@example.com');


