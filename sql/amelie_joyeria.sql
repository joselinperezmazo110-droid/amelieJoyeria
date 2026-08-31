CREATE DATABASE IF NOT EXISTS amelie_joyeria CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE amelie_joyeria;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, price, description) VALUES
('Anillo de oro', 150.00, 'Anillo elegante de oro 18k'),
('Collar plata', 80.00, 'Collar delicado en plata 925'),
('Pulsera fina', 120.50, 'Pulsera moderna para uso diario');
