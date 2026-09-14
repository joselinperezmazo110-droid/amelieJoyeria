<?php
require_once __DIR__ . '/../includes/functions.php';
if (current_user()) redirect('perfil.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	global $pdo;
	$statement = $pdo?->prepare('SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?');
	$statement?->execute([trim($_POST['email'] ?? '')]);
	$user = $statement?->fetch();
	if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
		unset($user['password']); session_regenerate_id(true); $_SESSION['user'] = $user; flash('success', 'Bienvenido de nuevo, ' . $user['nombre'] . '.'); redirect('perfil.php');
	}
	flash('error', 'El correo o la contraseña no son correctos.');
}
$page_title = 'Entrar'; require __DIR__ . '/../includes/header.php';
?><section class="auth"><p class="eyebrow">Tu cuenta</p><h1>Qué bueno<br><em>verte.</em></h1><form method="post" class="form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Correo electrónico<input type="email" name="email" required autocomplete="email"></label><label>Contraseña<input type="password" name="password" required autocomplete="current-password"></label><button class="button" type="submit">Entrar <span>↗</span></button></form><p>¿Aún no tienes cuenta? <a class="text-link" href="<?= base_url('registro.php') ?>">Crear una aquí</a></p></section><?php require __DIR__ . '/../includes/footer.php'; ?>
