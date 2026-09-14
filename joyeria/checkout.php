<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
$items = cart_products();
if (!$items) redirect('carrito.php');
$total = cart_total($items);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verify_csrf();
	$nombre = trim($_POST['nombre'] ?? '');
	$direccion = trim($_POST['direccion'] ?? '');
	$ciudad = trim($_POST['ciudad'] ?? '');
	$metodo = $_POST['metodo_pago'] ?? '';
	if ($nombre === '' || $direccion === '' || $ciudad === '' || !in_array($metodo, ['transferencia', 'contra_entrega'], true)) {
		flash('error', 'Completa todos los datos de entrega y elige un método de pago.');
	} else {
		global $pdo;
		try {
			$pdo->beginTransaction();
			$insert = $pdo->prepare('INSERT INTO pedidos (usuario_id, total, nombre_envio, direccion, ciudad, metodo_pago) VALUES (?, ?, ?, ?, ?, ?)');
			$insert->execute([current_user()['id'], $total, $nombre, $direccion, $ciudad, $metodo]);
			$order_id = (int) $pdo->lastInsertId();
			$detail = $pdo->prepare('INSERT INTO pedido_detalles (pedido_id, producto_id, nombre_producto, precio, cantidad) VALUES (?, ?, ?, ?, ?)');
			$reduce = $pdo->prepare('UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?');
			foreach ($items as $item) {
				$reduce->execute([$item['cantidad'], $item['id'], $item['cantidad']]);
				if ($reduce->rowCount() !== 1) throw new RuntimeException('Uno de los productos ya no tiene stock suficiente.');
				$detail->execute([$order_id, $item['id'], $item['nombre'], $item['precio'], $item['cantidad']]);
			}
			$pdo->commit();
			$_SESSION['cart'] = [];
			redirect('confirmacion.php?id=' . $order_id);
		} catch (Throwable $exception) {
			if ($pdo->inTransaction()) $pdo->rollBack();
			flash('error', 'No se pudo crear el pedido. Revisa el stock e inténtalo de nuevo.');
		}
	}
}
$page_title = 'Finalizar compra';
require __DIR__ . '/../includes/header.php';
?>
<section class="page-intro"><p class="eyebrow">Último paso</p><h1>Finaliza tu compra.</h1></section>
<section class="checkout-layout"><form method="post" class="form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Nombre de quien recibe<input type="text" name="nombre" required value="<?= e($_POST['nombre'] ?? current_user()['nombre']) ?>"></label><label>Dirección<input type="text" name="direccion" required value="<?= e($_POST['direccion'] ?? '') ?>"></label><label>Ciudad<input type="text" name="ciudad" required value="<?= e($_POST['ciudad'] ?? '') ?>"></label><fieldset><legend>Método de pago</legend><label><input type="radio" name="metodo_pago" value="transferencia" required> Transferencia bancaria</label><label><input type="radio" name="metodo_pago" value="contra_entrega"> Pago contra entrega</label></fieldset><button class="button" type="submit">Crear pedido <span>↗</span></button></form><aside class="summary"><p class="eyebrow">Resumen</p><?php foreach ($items as $item): ?><div><span><?= e($item['nombre']) ?> × <?= (int) $item['cantidad'] ?></span><strong><?= e(format_cop((float) ($item['precio'] * $item['cantidad']))) ?></strong></div><?php endforeach; ?><div><span>Total</span><strong><?= e(format_cop($total)) ?></strong></div></aside></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>