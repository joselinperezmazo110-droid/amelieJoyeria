# Amelie Joyería

Aplicación PHP sencilla para una joyería, preparada para ejecutarse con XAMPP.

## Puesta en marcha

1. Inicia Apache y MySQL desde el panel de XAMPP.
2. Abre phpMyAdmin en `http://localhost/phpmyadmin`.
3. Importa `database/joyerias.sql`.
4. Visita `http://localhost/Newfolder/amelieJoyeria/joyeria/`.

La conexión usa por defecto `127.0.0.1`, base `joyerias`, usuario `root` y contraseña vacía. Se pueden cambiar mediante `JOYERIA_DB_HOST`, `JOYERIA_DB_NAME`, `JOYERIA_DB_USER` y `JOYERIA_DB_PASSWORD`.

## Estructura

- `config/`: conexión PDO.
- `database/`: esquema y productos de ejemplo.
- `includes/`: sesión, consultas, cabecera, pie y tarjeta de producto.
- `joyeria/`: páginas públicas y flujos de usuario.
- `assets/`: estilos responsive y JavaScript.

El proyecto incluye registro, inicio de sesión, catálogo, carrito, checkout, pedidos persistentes, historial de compras y panel de administración. El checkout permite transferencia o pago contra entrega. Para cobrar con tarjeta hay que conectar una pasarela como Stripe o Mercado Pago; los datos de tarjeta no deben guardarse en esta aplicación.

## Acceso y administración

El registro permite elegir entre cliente y administradora. El código predeterminado para crear cuentas admin es `482731`. Para cambiarlo, configura `JOYERIA_ADMIN_CODE` como variable de entorno de Apache; por ejemplo, añade `SetEnv JOYERIA_ADMIN_CODE "reemplaza-por-un-codigo-secreto-largo"` en `C:\xampp\apache\conf\httpd.conf` y reinicia Apache. Comparte el código solo con las personas autorizadas; cambia el valor predeterminado antes de publicar la tienda. Puedes crear varios administradores con el mismo código; las cuentas existentes no cambian.

Después de iniciar sesión, abre **Mi perfil** y entra en **Administración**. Desde allí puedes agregar y editar productos, ajustar existencias, publicar u ocultar artículos, cargar fotografías JPG/PNG/WebP de hasta 5 MB, aplicar descuentos de hasta el 90 %, asignar permisos y actualizar pedidos. Los descuentos se reflejan en el catálogo, el carrito y el total guardado en el pedido.

Si ya habías creado las tablas con una versión anterior, volver a importar el archivo no las actualiza porque usa `CREATE TABLE IF NOT EXISTS`. Haz una copia de seguridad y aplica las columnas y tablas que falten según sus definiciones en `database/joyerias.sql`.