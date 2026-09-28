<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
global $pdo;

function save_uploaded_product_image(): ?string
{
	$file = $_FILES['imagen'] ?? null;
	if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) return null;
	if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024) {
		throw new RuntimeException('La imagen debe pesar menos de 5 MB.');
	}
	$image = @getimagesize($file['tmp_name']);
	$extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
	if (!$image || !isset($extensions[$image['mime']])) {
		throw new RuntimeException('Sube una imagen JPG, PNG o WebP válida.');
	}
	$directory = __DIR__ . '/../assets/images/uploads';
	if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
		throw new RuntimeException('No se pudo preparar la carpeta de imágenes.');
	}
	$filename = bin2hex(random_bytes(12)) . '.' . $extensions[$image['mime']];
	if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $filename)) {
		throw new RuntimeException('No se pudo guardar la imagen.');
	}
	return 'uploads/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	$action = $_POST['action'] ?? '';
	try {
		if (in_array($action, ['save', 'update'], true)) {
			$name = trim($_POST['nombre'] ?? '');
			$category = trim($_POST['categoria'] ?? '');
			$description = trim($_POST['descripcion'] ?? '');
			$price = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);
			$stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
			$discount = filter_var($_POST['descuento_porcentaje'] ?? 0, FILTER_VALIDATE_INT);
			if ($name === '' || $category === '' || $description === '' || $price === false || $price <= 0 || $stock === false || $stock < 0 || $discount === false || $discount < 0 || $discount > 90) {
				throw new RuntimeException('Revisa los datos: precio mayor a cero, stock válido y descuento entre 0 y 90 %.');
			}
			$image = save_uploaded_product_image();
			$values = [$name, $category, $description, $price, $stock, $discount];
			$featured = !empty($_POST['destacado']) ? 1 : 0;
			if ($action === 'save') {
				$statement = $pdo->prepare('INSERT INTO productos (nombre, categoria, descripcion, precio, stock, descuento_porcentaje, imagen, destacado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
				$statement->execute([...$values, $image ?? 'joya-generica.webp', $featured]);
				flash('success', 'Producto agregado al catálogo.');
			} else {
				$statement = $pdo->prepare('UPDATE productos SET nombre = ?, categoria = ?, descripcion = ?, precio = ?, stock = ?, descuento_porcentaje = ?, destacado = ?, imagen = COALESCE(?, imagen) WHERE id = ?');
				$statement->execute([...$values, $featured, $image, (int) ($_POST['id'] ?? 0)]);
				flash('success', 'Producto actualizado.');
			}
		} elseif ($action === 'toggle') {
			$statement = $pdo->prepare('UPDATE productos SET activo = 1 - activo WHERE id = ?');
			$statement->execute([(int) ($_POST['id'] ?? 0)]);
			flash('success', 'Visibilidad del producto actualizada.');
		} elseif ($action === 'status') {
			$status = $_POST['estado'] ?? '';
			if (!in_array($status, ['pendiente', 'pagado', 'enviado', 'cancelado'], true)) throw new RuntimeException('El estado de pedido no es válido.');
			$statement = $pdo->prepare('UPDATE pedidos SET estado = ? WHERE id = ?');
			$statement->execute([$status, (int) ($_POST['id'] ?? 0)]);
			flash('success', 'Estado del pedido actualizado.');
		} elseif ($action === 'role') {
			$email = trim($_POST['email'] ?? '');
			$role = $_POST['rol'] ?? 'cliente';
			if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, ['admin', 'cliente'], true)) {
				throw new RuntimeException('Indica un correo y un rol válidos.');
			}
			$statement = $pdo->prepare('UPDATE usuarios SET rol = ? WHERE email = ?');
			$statement->execute([$role, $email]);
			if ($statement->rowCount() === 0) throw new RuntimeException('No existe esa cuenta o ya tiene ese rol.');
			flash('success', 'Rol actualizado correctamente.');
		} else {
			throw new RuntimeException('La acción solicitada no es válida.');
		}
	} catch (RuntimeException $exception) {
		flash('error', $exception->getMessage());
	} catch (Throwable $exception) {
		error_log('Admin action failed: ' . $exception->getMessage());
		flash('error', 'No se pudo guardar el cambio. Revisa los datos e inténtalo de nuevo.');
	}
	redirect('admin.php');
}
$products = $pdo->query('SELECT * FROM productos ORDER BY id DESC')->fetchAll();
$orders = $pdo->query('SELECT p.*, u.email FROM pedidos p INNER JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.creado_en DESC')->fetchAll();
$users = $pdo->query('SELECT id, nombre, email, rol, creado_en FROM usuarios ORDER BY creado_en DESC')->fetchAll();
$activeProducts = count(array_filter($products, fn($product) => (int) $product['activo'] === 1));
$inventory = array_sum(array_column($products, 'stock'));
$lowStock = count(array_filter($products, fn($product) => (int) $product['activo'] === 1 && (int) $product['stock'] <= 5));
$page_title = 'Administración';
require __DIR__ . '/../includes/header.php';
?>
<section class="admin-heading page-intro"><p class="eyebrow">Espacio privado</p><h1>Administración.</h1><p>Gestiona el catálogo, las existencias, las promociones y los pedidos.</p></section>
<nav class="admin-tabs" aria-label="Secciones de administración"><a href="#inventario">Inventario</a><a href="#productos">Productos</a><a href="#cuentas">Cuentas</a><a href="#pedidos">Pedidos</a></nav>
<section class="admin-metrics" aria-label="Resumen de la tienda"><div><span>Productos activos</span><strong><?= $activeProducts ?></strong></div><div><span>Unidades en inventario</span><strong><?= $inventory ?></strong></div><div class="<?= $lowStock ? 'metric-warning' : '' ?>"><span>Por reponer (5 o menos)</span><strong><?= $lowStock ?></strong></div><div><span>Pedidos recibidos</span><strong><?= count($orders) ?></strong></div></section>

<section class="admin-section" id="inventario"><div class="admin-section-heading"><div><p class="eyebrow">Catálogo</p><h2>Agregar producto</h2></div></div>
<form method="post" enctype="multipart/form-data" class="admin-form product-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="save">
	<label>Nombre<input name="nombre" maxlength="120" required></label><label>Categoría<input name="categoria" maxlength="80" required></label>
	<label class="field-wide">Descripción<textarea name="descripcion" required rows="3"></textarea></label>
	<label>Precio (COP)<input type="number" name="precio" min="1" step="1" required></label><label>Unidades disponibles<input type="number" name="stock" min="0" value="10" required></label>
	<label>Descuento promocional (%)<input type="number" name="descuento_porcentaje" min="0" max="90" value="0" required></label><label class="image-field">Fotografía<input type="file" name="imagen" accept="image/jpeg,image/png,image/webp"><small>JPG, PNG o WebP, hasta 5 MB.</small></label>
	<label class="check-field"><input type="checkbox" name="destacado" value="1"> Destacar en la selección</label><button class="button" type="submit">Agregar producto <span>+</span></button>
</form></section>

<section class="admin-section" id="productos"><div class="admin-section-heading"><div><p class="eyebrow">Control de existencias</p><h2>Inventario y promociones</h2></div><span class="admin-count"><?= count($products) ?> referencias</span></div>
<?php if (!$products): ?><p>Aún no hay productos registrados.</p><?php else: ?><div class="inventory-list">
<?php foreach ($products as $product): ?><article class="inventory-entry"><img class="inventory-photo" src="<?= e(product_image($product)) ?>" alt="<?= e($product['nombre']) ?>"><form method="post" enctype="multipart/form-data" class="admin-form inventory-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="update"><input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
	<label>Nombre<input name="nombre" value="<?= e($product['nombre']) ?>" maxlength="120" required></label><label>Categoría<input name="categoria" value="<?= e($product['categoria']) ?>" maxlength="80" required></label>
	<label class="field-wide">Descripción<textarea name="descripcion" rows="2" required><?= e($product['descripcion']) ?></textarea></label>
	<label>Precio (COP)<input type="number" name="precio" min="1" step="1" value="<?= e((string) $product['precio']) ?>" required></label><label>Stock<input type="number" name="stock" min="0" value="<?= (int) $product['stock'] ?>" required></label>
	<label>Descuento (%)<input type="number" name="descuento_porcentaje" min="0" max="90" value="<?= (int) ($product['descuento_porcentaje'] ?? 0) ?>" required></label><label class="image-field">Cambiar fotografía<input type="file" name="imagen" accept="image/jpeg,image/png,image/webp"></label>
	<label class="check-field"><input type="checkbox" name="destacado" value="1" <?= $product['destacado'] ? 'checked' : '' ?>> Destacado</label><div class="inventory-actions"><button class="button button-small" type="submit">Guardar cambios</button></div>
</form><div class="inventory-status"><span class="status-pill <?= !$product['activo'] ? 'status-hidden' : ((int) $product['stock'] <= 5 ? 'status-low' : '') ?>"><?= !$product['activo'] ? 'Oculto' : ((int) $product['stock'] <= 5 ? 'Stock bajo' : 'Activo') ?></span><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int) $product['id'] ?>"><button class="text-button" type="submit"><?= $product['activo'] ? 'Ocultar' : 'Publicar' ?></button></form></div></article><?php endforeach; ?></div><?php endif; ?></section>

<section class="admin-section" id="cuentas"><div class="admin-section-heading"><div><p class="eyebrow">Acceso a la tienda</p><h2>Cuentas y permisos</h2></div></div><form method="post" class="admin-form role-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="role"><label>Correo de la cuenta<input type="email" name="email" required placeholder="cliente@correo.com"></label><label>Rol<select name="rol"><option value="cliente">Cliente</option><option value="admin">Administradora</option></select></label><button class="button button-small" type="submit">Actualizar rol</button></form>
<div class="user-list"><?php foreach ($users as $user): ?><article class="user-row"><span><strong><?= e($user['nombre']) ?></strong><small><?= e($user['email']) ?></small></span><span class="status-pill"><?= $user['rol'] === 'admin' ? 'Administradora' : 'Cliente' ?></span></article><?php endforeach; ?></div></section>

<section class="admin-section" id="pedidos"><div class="admin-section-heading"><div><p class="eyebrow">Ventas</p><h2>Pedidos</h2></div><span class="admin-count"><?= count($orders) ?> pedidos</span></div>
<?php if (!$orders): ?><p>Aún no hay pedidos.</p><?php else: ?><div class="order-list admin-orders"><?php foreach ($orders as $order): ?><article class="admin-order"><div><strong>Pedido #<?= (int) $order['id'] ?></strong><span><?= e($order['email']) ?> · <?= e(format_cop((float) $order['total'])) ?></span><small><?= e(date('d/m/Y', strtotime($order['creado_en']))) ?> · <?= e(str_replace('_', ' ', $order['metodo_pago'])) ?></small></div><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?= (int) $order['id'] ?>"><label class="visually-hidden" for="status-<?= (int) $order['id'] ?>">Estado del pedido #<?= (int) $order['id'] ?></label><select id="status-<?= (int) $order['id'] ?>" name="estado"><option value="pendiente" <?= $order['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option><option value="pagado" <?= $order['estado'] === 'pagado' ? 'selected' : '' ?>>Pagado</option><option value="enviado" <?= $order['estado'] === 'enviado' ? 'selected' : '' ?>>Enviado</option><option value="cancelado" <?= $order['estado'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option></select><button class="button button-small" type="submit">Actualizar</button></form></article><?php endforeach; ?></div><?php endif; ?></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
