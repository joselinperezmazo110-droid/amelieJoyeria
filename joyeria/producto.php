<?php
require_once __DIR__ . '/../includes/functions.php';
$product = get_product((int) ($_GET['id'] ?? 0));
if (!$product) { http_response_code(404); $page_title = 'Producto no encontrado'; require __DIR__ . '/../includes/header.php'; echo '<section class="empty"><h1>Producto no encontrado.</h1><a class="text-link" href="' . base_url('catalogo.php') . '">Volver a la colección ↗</a></section>'; require __DIR__ . '/../includes/footer.php'; exit; }
$page_title = $product['nombre'];
require __DIR__ . '/../includes/header.php';
?>
<section class="detail"><div class="detail-image"><img src="<?= e(product_image($product)) ?>" alt="<?= e($product['nombre']) ?>"></div><div class="detail-copy"><p class="eyebrow"><?= e($product['categoria']) ?></p><h1><?= e($product['nombre']) ?></h1><p class="price"><?= e(format_cop((float) $product['precio'])) ?></p><p><?= e($product['descripcion']) ?></p><p><?= (int) $product['stock'] ?> disponibles</p><form method="post" action="carrito.php" class="add-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $product['id'] ?>"><label>Cantidad <input type="number" name="cantidad" value="1" min="1" max="<?= max(1, (int) $product['stock']) ?>"></label><button class="button" type="submit" <?= $product['stock'] < 1 ? 'disabled' : '' ?>>Añadir a la bolsa <span>+</span></button></form></div></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
