<?php
require_once __DIR__ . '/functions.php';
$page_title = $page_title ?? 'Amelie Joyería';
?><!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= e($page_title) ?> | Amelie Joyería</title>
	<link rel="stylesheet" href="<?= base_url('../assets/css/estilos.css') ?>">
	<link rel="stylesheet" href="<?= base_url('../assets/css/tienda.css?v=4') ?>">
	<style>
		.brand-logo { display: block; width: 218px; height: auto; }
		.brand:focus-visible { outline: 2px solid var(--gold); outline-offset: 5px; }
		@media (max-width: 700px) { .brand-logo { width: 168px; } .site-header { height: auto; min-height: 78px; gap: 18px; } .nav { gap: 12px; flex-wrap: wrap; justify-content: flex-end; } }
	</style>
</head>
<body>
<header class="site-header">
	<a class="brand" href="<?= base_url('index.php') ?>" aria-label="Amelie Joyería, ir al inicio"><img class="brand-logo" src="<?= base_url('../assets/images/logo-amelie.svg') ?>" alt="Amelie Joyería"></a>
	<nav class="nav" aria-label="Navegación principal">
		<a href="<?= base_url('catalogo.php') ?>">Colección</a>
		<?php if (current_user()): ?><a href="<?= base_url('perfil.php') ?>">Mi perfil</a><?php endif; ?>
		<?php if (is_admin()): ?><a href="<?= base_url('admin.php') ?>">Admin</a><?php endif; ?>
		<a class="cart-link" href="<?= base_url('carrito.php') ?>">Bolsa <b><?= cart_count() ?></b></a>
		<?php if (current_user()): ?><a href="<?= base_url('logout.php') ?>">Salir</a><?php else: ?><a href="<?= base_url('login.php') ?>">Entrar</a><?php endif; ?>
	</nav>
</header>
<main class="container">
<?php foreach (take_flashes() as $flash): ?><div class="notice <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endforeach; ?>
