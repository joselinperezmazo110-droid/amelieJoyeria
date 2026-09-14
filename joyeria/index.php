<?php
require_once __DIR__ . '/../includes/functions.php';
$page_title = 'Inicio';
$featured = get_products(null, 3);
require __DIR__ . '/../includes/header.php';
?>
<section class="hero">
	<div class="hero-copy"><p class="eyebrow">Nueva colección · 2026</p><h1>Pequeños objetos.<br><em>Grandes historias.</em></h1><p>Joyería contemporánea creada para acompañarte hoy y permanecer contigo mañana.</p><a class="button" href="<?= base_url('catalogo.php') ?>">Explorar colección <span>↗</span></a></div>
	<div class="hero-art" aria-label="Composición abstracta de joyería"><div class="orb orb-one"></div><div class="orb orb-two"></div><span>FORMA<br>Y LUZ</span></div>
</section>
<section class="section-heading"><div><p class="eyebrow">Selección curada</p><h2>Piezas que hablan<br>en voz baja.</h2></div><a class="text-link" href="<?= base_url('catalogo.php') ?>">Ver toda la colección ↗</a></section>
<section class="product-grid"><?php foreach ($featured as $product): ?><?php require __DIR__ . '/../includes/tarjeta.php'; ?><?php endforeach; ?></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
