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
	descuento_porcentaje TINYINT UNSIGNED NOT NULL DEFAULT 0,
	imagen VARCHAR(255) NULL,
	destacado TINYINT(1) DEFAULT 0,
	activo TINYINT(1) DEFAULT 1,
	creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE productos ADD COLUMN IF NOT EXISTS descuento_porcentaje TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER stock;

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

INSERT INTO productos (nombre, categoria, descripcion, precio, imagen, destacado)
SELECT seed.nombre, seed.categoria, seed.descripcion, seed.precio, seed.imagen, seed.destacado
FROM (
	SELECT 'Anillo Aura' AS nombre, 'Anillos' AS categoria, 'Plata de ley con una silueta limpia y atemporal.' AS descripcion, 89000.00 AS precio, 'anillo-aura.jpg' AS imagen, 1 AS destacado
	UNION ALL SELECT 'Collar Numa', 'Collares', 'Cadena fina con colgante dorado.', 124000.00, 'collar-numa.jpg', 1
	UNION ALL SELECT 'Pendientes Vela', 'Pendientes', 'Pendientes ligeros para iluminar cualquier ocasión.', 68000.00, 'pendientes-vela.webp', 1
	UNION ALL SELECT 'Pulsera Origen', 'Pulseras', 'Eslabones delicados y cierre seguro para todos los días.', 96000.00, 'pulsera-origen.webp', 0
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE productos.nombre = seed.nombre);

UPDATE productos SET precio = CASE nombre
	WHEN 'Anillo Aura' THEN 89000.00
	WHEN 'Collar Numa' THEN 124000.00
	WHEN 'Pendientes Vela' THEN 68000.00
	WHEN 'Pulsera Origen' THEN 96000.00
END
WHERE (nombre = 'Anillo Aura' AND precio = 89.00)
	OR (nombre = 'Collar Numa' AND precio = 124.00)
	OR (nombre = 'Pendientes Vela' AND precio = 68.00)
	OR (nombre = 'Pulsera Origen' AND precio = 96.00);
UPDATE productos SET imagen = 'anillo-aura.jpg' WHERE nombre = 'Anillo Aura';
UPDATE productos SET imagen = 'collar-numa.jpg' WHERE nombre = 'Collar Numa';
UPDATE productos SET imagen = 'pendientes-vela.webp' WHERE nombre = 'Pendientes Vela';
UPDATE productos SET imagen = 'pulsera-origen.webp' WHERE nombre = 'Pulsera Origen';
