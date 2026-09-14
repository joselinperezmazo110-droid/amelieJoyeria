<?php
require_once __DIR__ . '/../includes/functions.php';
$page_title = 'Colección';
$search = trim($_GET['q'] ?? '');
$products = get_products($search);
require __DIR__ . '/../includes/header.php';
?>
<section class="page-intro"><p class="eyebrow">Amelie / colección</p><h1>Encuentra tu pieza.</h1><form class="search" method="get"><input name="q" value="<?= e($search) ?>" placeholder="Buscar por nombre o categoría"><button aria-label="Buscar">⌕</button></form></section>
<section class="catalog-toolbar"><span><?= count($products) ?> piezas</span><span>Hecho para llevarlo todo</span></section>
<section class="product-grid"><?php if (!$products): ?><p class="empty">No encontramos piezas con esa búsqueda.</p><?php else: ?><?php foreach ($products as $product): ?><?php require __DIR__ . '/../includes/tarjeta.php'; ?><?php endforeach; ?><?php endif; ?></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
