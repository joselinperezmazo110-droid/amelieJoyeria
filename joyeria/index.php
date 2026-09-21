<?php
require_once __DIR__ . '/../includes/functions.php';
$page_title = 'Inicio';
$featured = get_products(null, 3);
require __DIR__ . '/../includes/header.php';
?>
<section class="hero-showcase" aria-label="Portada principal de Amelie Joyería">
	<div class="hero-copy">
		<p class="eyebrow">Nueva colección · 2026</p>
		<h1>Pequeños objetos.<br><em>Grandes historias.</em></h1>
		<p>Joyería contemporánea creada para acompañarte hoy y permanecer contigo mañana.</p>
		<a class="button" href="<?= base_url('catalogo.php') ?>">Explorar colección <span>↗</span></a>
	</div>

	<div class="hero-visual">
		<p class="eyebrow eyebrow-visual">Piezas esenciales</p>
		<h2>Lujo discreto.<br><em>Presencia eterna.</em></h2>
		<img src="<?= base_url('../assets/images/anillo-aura.jpg') ?>" alt="Anillo Aura de Amelie Joyería" onerror="this.onerror=null;this.src='<?= base_url('../assets/images/joya-generica.webp') ?>';">
		<p>Diseños elegantes y emotivos pensados para vestir cada momento especial con delicadeza.</p>
		<a class="button" href="<?= base_url('catalogo.php') ?>">Descubrir regalos <span>↗</span></a>
	</div>
</section>
<section class="section-heading"><div><p class="eyebrow">Selección curada</p><h2>Piezas que hablan<br>en voz baja.</h2></div><a class="text-link" href="<?= base_url('catalogo.php') ?>">Ver toda la colección ↗</a></section>
<section class="product-grid"><?php foreach ($featured as $product): ?><?php require __DIR__ . '/../includes/tarjeta.php'; ?><?php endforeach; ?></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
