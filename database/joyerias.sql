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

UPDATE productos SET nombre = 'Anillo Corazón Rosa', categoria = 'Anillos' WHERE nombre = 'Pulsera Corazón Rosa';
UPDATE productos SET nombre = 'Collar Corazón Rosa' WHERE nombre = 'Collar Trenza Dorada';
UPDATE productos SET nombre = 'Pulsera Charms Dorados' WHERE nombre = 'Pulsera Flor de Luz';

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
		SELECT 'Anillo Aura' AS nombre, 'Anillos' AS categoria, 'Anillo dorado con banda de zirconias y una piedra central en forma de corazón.' AS descripcion, 89000.00 AS precio, '598BDD34-AF32-4E98-A763-91D30EE6508A.jpg' AS imagen, 1 AS destacado
		UNION ALL SELECT 'Collar Numa', 'Collares', 'Cadena dorada fina con dije de mariposa brillante, ligera y delicada para llevar todos los días.', 124000.00, '190D45D6-BE91-4866-B085-3833FE6ECF1C.PNG', 1
		UNION ALL SELECT 'Pendientes Vela', 'Pendientes', 'Aros dorados colgantes con dijes de corazón rosa facetado, ideales para dar un toque luminoso.', 68000.00, '21A6D9AD-AC7D-4E29-92BB-FED325EE24BF.PNG', 1
		UNION ALL SELECT 'Pulsera Origen', 'Pulseras', 'Pulsera dorada de eslabones finos con pequeños corazones y zirconias brillantes.', 96000.00, '46627DD2-7AE3-44FE-A697-3C5AD2AC1B31.PNG', 0
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM productos WHERE productos.nombre = seed.nombre);

INSERT INTO productos (nombre, categoria, descripcion, precio, imagen, destacado)
SELECT seed.nombre, seed.categoria, seed.descripcion, seed.precio, seed.imagen, 0
FROM (
	SELECT 'Anillo Brillo Eterno' AS nombre, 'Anillos' AS categoria, 'Anillo dorado con motivo de mariposa y zirconias que recorren la banda. Una pieza luminosa para ocasiones especiales.' AS descripcion, 159000.00 AS precio, '11BF4C95-3684-4E1A-9ECE-4211DF1B7AFB.PNG' AS imagen
	UNION ALL SELECT 'Anillo Tres Luces', 'Anillos', 'Anillo dorado con diseño de mariposa y piedras brillantes engastadas en la banda.', 149000.00, 'FCDF8116-D45F-4BC8-83B7-3265993FD5D8.PNG'
	UNION ALL SELECT 'Collar Corazón Rosa', 'Collares', 'Cadena dorada fina con un dije de corazón rosa facetado, delicado y fácil de combinar.', 135000.00, 'D832C7AE-9E88-4438-B7D1-67C179A111EE.PNG'
	UNION ALL SELECT 'Pulsera Charms Dorados', 'Pulseras', 'Pulsera dorada con charms de corazones y destellos, un diseño alegre para combinar en capas.', 118000.00, '4B99930D-4FA5-4FEA-8FC8-4C38F5E7237C.PNG'
	UNION ALL SELECT 'Anillo Corazón Rosa', 'Anillos', 'Anillo dorado con una piedra central en forma de corazón y zirconias alrededor de la banda.', 112000.00, 'FF523F97-EEFA-4E87-8AD0-2208E626CE2A.PNG'
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
UPDATE productos SET descripcion = 'Anillo dorado con banda de zirconias y una piedra central en forma de corazón.', imagen = '598BDD34-AF32-4E98-A763-91D30EE6508A.jpg' WHERE nombre = 'Anillo Aura';
UPDATE productos SET descripcion = 'Cadena dorada fina con dije de mariposa brillante, ligera y delicada para llevar todos los días.', imagen = '190D45D6-BE91-4866-B085-3833FE6ECF1C.PNG' WHERE nombre = 'Collar Numa';
UPDATE productos SET descripcion = 'Aros dorados colgantes con dijes de corazón rosa facetado, ideales para dar un toque luminoso.', imagen = '21A6D9AD-AC7D-4E29-92BB-FED325EE24BF.PNG' WHERE nombre = 'Pendientes Vela';
UPDATE productos SET descripcion = 'Pulsera dorada de eslabones finos con pequeños corazones y zirconias brillantes.', imagen = '46627DD2-7AE3-44FE-A697-3C5AD2AC1B31.PNG' WHERE nombre = 'Pulsera Origen';
UPDATE productos SET descripcion = 'Anillo dorado con motivo de mariposa y zirconias que recorren la banda. Una pieza luminosa para ocasiones especiales.', imagen = '11BF4C95-3684-4E1A-9ECE-4211DF1B7AFB.PNG' WHERE nombre = 'Anillo Brillo Eterno';
UPDATE productos SET descripcion = 'Anillo dorado con diseño de mariposa y piedras brillantes engastadas en la banda.', imagen = 'FCDF8116-D45F-4BC8-83B7-3265993FD5D8.PNG' WHERE nombre = 'Anillo Tres Luces';
UPDATE productos SET descripcion = 'Cadena dorada fina con un dije de corazón rosa facetado, delicado y fácil de combinar.', imagen = 'D832C7AE-9E88-4438-B7D1-67C179A111EE.PNG' WHERE nombre = 'Collar Corazón Rosa';
UPDATE productos SET descripcion = 'Pulsera dorada con charms de corazones y destellos, un diseño alegre para combinar en capas.', imagen = '4B99930D-4FA5-4FEA-8FC8-4C38F5E7237C.PNG' WHERE nombre = 'Pulsera Charms Dorados';
UPDATE productos SET descripcion = 'Anillo dorado con una piedra central en forma de corazón y zirconias alrededor de la banda.', imagen = 'FF523F97-EEFA-4E87-8AD0-2208E626CE2A.PNG' WHERE nombre = 'Anillo Corazón Rosa';
