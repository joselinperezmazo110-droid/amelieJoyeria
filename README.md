# Amelie Joyería

Aplicación PHP sencilla para una joyería, preparada para ejecutarse con XAMPP.

## Puesta en marcha

1. Inicia Apache y MySQL desde el panel de XAMPP.
2. Abre phpMyAdmin en `http://localhost/phpmyadmin`.
3. Importa `database/joyerias.sql`.
4. Visita `http://localhost/trabajo/joyeria/`.

La conexión usa por defecto `127.0.0.1`, base `joyerias`, usuario `root` y contraseña vacía. Se pueden cambiar mediante `JOYERIA_DB_HOST`, `JOYERIA_DB_NAME`, `JOYERIA_DB_USER` y `JOYERIA_DB_PASSWORD`.

## Estructura

- `config/`: conexión PDO.
- `database/`: esquema y productos de ejemplo.
- `includes/`: sesión, consultas, cabecera, pie y tarjeta de producto.
- `joyeria/`: páginas públicas y flujos de usuario.
- `assets/`: estilos responsive y JavaScript.

El proyecto incluye registro, inicio de sesión, catálogo, carrito, checkout, pedidos persistentes, historial de compras y panel de administración. El checkout permite transferencia o pago contra entrega. Para cobrar con tarjeta hay que conectar una pasarela como Stripe o Mercado Pago; los datos de tarjeta no deben guardarse en esta aplicación.

Si ya habías importado una versión anterior de la base de datos, vuelve a importar `database/joyerias.sql` o aplica manualmente las nuevas columnas `usuarios.rol`, `productos.stock` y las tablas `pedidos` y `pedido_detalles`.