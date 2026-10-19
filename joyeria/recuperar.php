<?php
require_once __DIR__ . '/../includes/functions.php';
if (current_user()) redirect('perfil.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    global $pdo;

    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Ingresa un correo electrónico válido.');
        redirect('recuperar.php');
    }

    $statement = $pdo?->prepare('SELECT id FROM usuarios WHERE email = ?');
    $statement?->execute([$email]);
    $user = $statement?->fetch();

    if ($user) {
        // Aquí puedes integrar un servicio de correo más adelante.
        // Por ahora, no revelamos si el correo existe para mantener la seguridad.
    }

    flash('success', 'Si ese correo está registrado, recibirás instrucciones para restablecer tu contraseña.');
    redirect('login.php');
}

$page_title = 'Recuperar contraseña';
require __DIR__ . '/../includes/header.php';
?>
<section class="auth">
    <p class="eyebrow">Seguridad</p>
    <h1>Recupera<br><em>tu contraseña</em></h1>
    <form method="post" class="form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label>Correo electrónico
            <input type="email" name="email" required autocomplete="email" placeholder="tucorreo@ejemplo.com">
        </label>
        <button class="button" type="submit">Enviar instrucciones <span>↗</span></button>
    </form>
    <p><a class="text-link" href="<?= base_url('login.php') ?>">Volver al inicio de sesión</a></p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
