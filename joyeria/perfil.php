<?php
require_once __DIR__ . '/../includes/functions.php'; require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'make_admin') {
	verify_csrf();
	if (admin_count() === 0) {
		global $pdo;
		$statement = $pdo->prepare("UPDATE usuarios SET rol = 'admin' WHERE id = ?");
		$statement->execute([(int) current_user()['id']]);
		$_SESSION['user']['rol'] = 'admin';
		flash('success', 'Tu cuenta ya es administradora.');
	}
	redirect('perfil.php');
}
$orders = get_orders((int) current_user()['id']);
$page_title = 'Mi perfil'; require __DIR__ . '/../includes/header.php';
?><section class="profile"><p class="eyebrow">Cuenta personal</p><h1>Hola, <?= e(current_user()['nombre']) ?>.</h1><div class="profile-line"><span>Correo</span><strong><?= e(current_user()['email']) ?></strong></div><?php if (!is_admin() && admin_count() === 0): ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="make_admin"><button class="button" type="submit">Activar cuenta administradora <span>↗</span></button></form><?php endif; ?><h2>Mis pedidos</h2><?php if (!$orders): ?><p>Aún no tienes pedidos.</p><?php else: ?><div class="order-list"><?php foreach ($orders as $order): ?><article class="profile-line"><span>Pedido #<?= (int) $order['id'] ?><br><small><?= e(date('d/m/Y', strtotime($order['creado_en']))) ?></small></span><strong><?= e(ucfirst($order['estado'])) ?><br><?= e(format_cop((float) $order['total'])) ?></strong></article><?php endforeach; ?></div><?php endif; ?><p><a class="text-link" href="<?= base_url('logout.php') ?>">Cerrar sesión ↗</a><?php if (is_admin()): ?> · <a class="text-link" href="<?= base_url('admin.php') ?>">Administración ↗</a><?php endif; ?></p></section><?php require __DIR__ . '/../includes/footer.php'; ?>
