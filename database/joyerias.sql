CREATE DATABASE IF NOT EXISTS joyerias CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE joyerias;

CREATE TABLE IF NOT EXISTS usuarios (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL,
	email VARCHAR(160) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	rol ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
	creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS productos (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(120) NOT NULL,
	categoria VARCHAR(80) NOT NULL,
	descripcion TEXT NOT NULL,
	precio DECIMAL(10,2) NOT NULL,
	stock INT UNSIGNED NOT NULL DEFAULT 10,
	imagen VARCHAR(255) NULL,
	destacado TINYINT(1) DEFAULT 0,
	activo TINYINT(1) DEFAULT 1,
	creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pedidos (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	usuario_id INT UNSIGNED NOT NULL,
	total DECIMAL(10,2) NOT NULL,
	estado ENUM('pendiente', 'pagado', 'enviado', 'cancelado') NOT NULL DEFAULT 'pendiente',
	nombre_envio VARCHAR(100) NOT NULL,
	direccion VARCHAR(255) NOT NULL,
	ciudad VARCHAR(100) NOT NULL,
	metodo_pago VARCHAR(40) NOT NULL,
	creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT fk_pedidos_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS pedido_detalles (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	pedido_id INT UNSIGNED NOT NULL,
	producto_id INT UNSIGNED NULL,
	nombre_producto VARCHAR(120) NOT NULL,
	precio DECIMAL(10,2) NOT NULL,
	cantidad INT UNSIGNED NOT NULL,
	CONSTRAINT fk_detalles_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
);

INSERT INTO productos (nombre, categoria, descripcion, precio, imagen, destacado) VALUES
('Anillo Aura', 'Anillos', 'Plata de ley con una silueta limpia y atemporal.', 89000.00, 'anillo-aura.jpg', 1),
('Collar Numa', 'Collares', 'Cadena fina con colgante dorado.', 124000.00, 'collar-numa.jpg', 1),
('Pendientes Vela', 'Pendientes', 'Pendientes ligeros para iluminar cualquier ocasión.', 68000.00, 'pendientes-vela.webp', 1),
('Pulsera Origen', 'Pulseras', 'Eslabones delicados y cierre seguro para todos los días.', 96000.00, 'pulsera-origen.webp', 0);

UPDATE productos SET precio = precio * 1000 WHERE precio > 0 AND precio < 1000;
UPDATE productos SET imagen = 'anillo-aura.jpg' WHERE nombre = 'Anillo Aura';
UPDATE productos SET imagen = 'collar-numa.jpg' WHERE nombre = 'Collar Numa';
UPDATE productos SET imagen = 'pendientes-vela.webp' WHERE nombre = 'Pendientes Vela';
UPDATE productos SET imagen = 'pulsera-origen.webp' WHERE nombre = 'Pulsera Origen';
