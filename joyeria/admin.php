<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
global $pdo;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	$action = $_POST['action'] ?? '';
	try {
		if ($action === 'save') {
			$data = [trim($_POST['nombre'] ?? ''), trim($_POST['categoria'] ?? ''), trim($_POST['descripcion'] ?? ''), max(0, (float) ($_POST['precio'] ?? 0)), max(0, (int) ($_POST['stock'] ?? 0)), !empty($_POST['destacado']) ? 1 : 0];
			if ($data[0] === '' || $data[1] === '' || $data[2] === '' || $data[3] <= 0) throw new RuntimeException('Completa los datos del producto.');
			$statement = $pdo->prepare('INSERT INTO productos (nombre, categoria, descripcion, precio, stock, destacado) VALUES (?, ?, ?, ?, ?, ?)');
			$statement->execute($data);
			flash('success', 'Producto guardado.');
		} elseif ($action === 'toggle') {
			$statement = $pdo->prepare('UPDATE productos SET activo = 1 - activo WHERE id = ?'); $statement->execute([(int) $_POST['id']]); flash('success', 'Estado del producto actualizado.');
		} elseif ($action === 'status') {
			$statement = $pdo->prepare('UPDATE pedidos SET estado = ? WHERE id = ?'); $statement->execute([$_POST['estado'] ?? 'pendiente', (int) $_POST['id']]); flash('success', 'Estado del pedido actualizado.');
		} elseif ($action === 'role') {
			$email = trim($_POST['email'] ?? '');
			$role = $_POST['rol'] ?? 'cliente';
			if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !in_array($role, ['admin', 'cliente'], true)) throw new RuntimeException('Indica un correo y un rol válidos.');
			$statement = $pdo->prepare('UPDATE usuarios SET rol = ? WHERE email = ?');
			$statement->execute([$role, $email]);
			if ($statement->rowCount() === 0) throw new RuntimeException('No existe una cuenta con ese correo.');
			flash('success', 'Rol actualizado correctamente.');
		}
	} catch (Throwable $exception) { flash('error', $exception->getMessage()); }
	redirect('admin.php');
}
$products = $pdo->query('SELECT * FROM productos ORDER BY id DESC')->fetchAll();
$orders = $pdo->query('SELECT p.*, u.email FROM pedidos p INNER JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.creado_en DESC')->fetchAll();
$users = $pdo->query('SELECT id, nombre, email, rol, creado_en FROM usuarios ORDER BY creado_en DESC')->fetchAll();
$page_title = 'Administración'; require __DIR__ . '/../includes/header.php';
?><section class="page-intro"><p class="eyebrow">Panel interno</p><h1>Gestión de la tienda.</h1></section>
<section class="admin-section"><h2>Nuevo producto</h2><form method="post" class="form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="save"><label>Nombre<input name="nombre" required></label><label>Categoría<input name="categoria" required></label><label>Descripción<textarea name="descripcion" required></textarea></label><label>Precio en pesos colombianos<input type="number" name="precio" min="1" step="1" required></label><label>Stock<input type="number" name="stock" min="0" value="10" required></label><label><input type="checkbox" name="destacado"> Destacado</label><button class="button" type="submit">Guardar producto <span>+</span></button></form></section>
<section class="admin-section"><h2>Cuentas y permisos</h2><form method="post" class="form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="role"><label>Correo de la cuenta<input type="email" name="email" required placeholder="cliente@correo.com"></label><label>Permiso<select name="rol"><option value="cliente">Cliente</option><option value="admin">Administrador</option></select></label><button class="button" type="submit">Guardar permiso <span>↗</span></button></form><?php foreach ($users as $user): ?><article class="profile-line"><span><?= e($user['nombre']) ?><br><small><?= e($user['email']) ?></small></span><strong><?= $user['rol'] === 'admin' ? 'Administrador' : 'Cliente' ?></strong></article><?php endforeach; ?></section>
<section class="admin-section"><h2>Productos</h2><?php foreach ($products as $product): ?><article class="profile-line"><span><?= e($product['nombre']) ?><br><small><?= e($product['categoria']) ?> · <?= (int) $product['stock'] ?> disponibles</small></span><span><?= $product['activo'] ? 'Activo' : 'Oculto' ?><form method="post" style="display:inline"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int) $product['id'] ?>"><button type="submit">Cambiar</button></form></span></article><?php endforeach; ?></section>
<section class="admin-section"><h2>Pedidos</h2><?php if (!$orders): ?><p>No hay pedidos todavía.</p><?php else: ?><?php foreach ($orders as $order): ?><article class="profile-line"><span>#<?= (int) $order['id'] ?> · <?= e($order['email']) ?><br><small><?= e(format_cop((float) $order['total'])) ?></small></span><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?= (int) $order['id'] ?>"><select name="estado"><option value="pendiente" <?= $order['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option><option value="pagado" <?= $order['estado'] === 'pagado' ? 'selected' : '' ?>>Pagado</option><option value="enviado" <?= $order['estado'] === 'enviado' ? 'selected' : '' ?>>Enviado</option><option value="cancelado" <?= $order['estado'] === 'cancelado' ? 'selected' : '' ?>>Cancelado</option></select><button type="submit">Actualizar</button></form></article><?php endforeach; ?><?php endif; ?></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
