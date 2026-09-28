<?php
require_once __DIR__ . '/../includes/functions.php';
if (current_user()) redirect('perfil.php');
$firstAccount = $pdo && (int) $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn() === 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
    global $pdo;
	$name = trim($_POST['nombre'] ?? ''); $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? '';
    if (!$pdo || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) { flash('error', 'Completa los datos con un correo válido y una contraseña de 6 caracteres.'); }
	else { try { $count = (int) $pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn(); $statement = $pdo->prepare('INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)'); $statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $count === 0 ? 'admin' : 'cliente']); flash('success', 'Cuenta creada. Ya puedes iniciar sesión.'); redirect('login.php'); } catch (PDOException $exception) { flash('error', 'Ese correo ya está registrado.'); } }
}
$page_title = 'Crear cuenta'; require __DIR__ . '/../includes/header.php';
?><section class="auth"><p class="eyebrow">Únete a Amelie</p><h1>Tu historia<br><em>comienza aquí.</em></h1><?php if ($firstAccount): ?><p>Esta primera cuenta tendrá acceso de administradora para configurar la tienda.</p><?php endif; ?><form method="post" class="form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Nombre<input type="text" name="nombre" required autocomplete="name"></label><label>Correo electrónico<input type="email" name="email" required autocomplete="email"></label><label>Contraseña<input type="password" name="password" minlength="6" required autocomplete="new-password"></label><button class="button" type="submit">Crear cuenta <span>↗</span></button></form></section><?php require __DIR__ . '/../includes/footer.php'; ?>
