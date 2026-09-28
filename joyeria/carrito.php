<?php
require_once __DIR__ . '/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	if (isset($_POST['remove'])) {
		unset($_SESSION['cart'][(int) $_POST['remove']]);
		redirect('carrito.php');
	}
	$id = (int) ($_POST['id'] ?? 0);
	$cantidad = max(1, min(10, (int) ($_POST['cantidad'] ?? 1)));
	$product = get_product($id);
	if ($product) {
		$available = max(0, (int) $product['stock']);
		$_SESSION['cart'][$id] = min($available, ($_SESSION['cart'][$id] ?? 0) + $cantidad);
		flash('success', 'La pieza se añadió a tu bolsa.');
	}
	redirect('carrito.php');
}
$page_title = 'Tu bolsa';
$items = cart_products();
$total = cart_total($items);
require __DIR__ . '/../includes/header.php';
?>
<section class="page-intro"><p class="eyebrow">Tu selección</p><h1>La bolsa.</h1></section>
<?php if (!$items): ?><section class="empty"><h2>Tu bolsa está esperando una pieza especial.</h2><a class="button" href="<?= base_url('catalogo.php') ?>">Ver colección <span>↗</span></a></section><?php else: ?><section class="cart-layout"><div><?php foreach ($items as $item): ?><article class="cart-item"><div class="cart-thumb"><?= e(strtoupper(substr($item['nombre'], 0, 1))) ?></div><div><p class="eyebrow"><?= e($item['categoria']) ?></p><h3><?= e($item['nombre']) ?></h3><p><?= $item['cantidad'] ?> × <?= e(format_cop(product_price($item))) ?></p></div><strong><?= e(format_cop(product_price($item) * (int) $item['cantidad'])) ?></strong><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="remove" value="<?= (int) $item['id'] ?>"><button class="remove" type="submit" aria-label="Quitar <?= e($item['nombre']) ?>">×</button></form></article><?php endforeach; ?></div><aside class="summary"><p class="eyebrow">Resumen</p><div><span>Total</span><strong><?= e(format_cop($total)) ?></strong></div><a class="button" href="<?= base_url('checkout.php') ?>">Finalizar compra <span>↗</span></a></aside></section><?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
