<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
$order_id = (int) ($_GET['id'] ?? 0);
$orders = get_orders((int) current_user()['id']);
$order = null;
foreach ($orders as $candidate) if ((int) $candidate['id'] === $order_id) $order = $candidate;
if (!$order) { http_response_code(404); exit('Pedido no encontrado.'); }
$items = get_order_items($order_id);
$page_title = 'Pedido confirmado';
require __DIR__ . '/../includes/header.php';
?>
<section class="empty"><p class="eyebrow">Pedido #<?= (int) $order['id'] ?></p><h1>Gracias por tu compra.</h1><p>Recibimos tu pedido y lo estamos preparando. Estado: <strong><?= e(ucfirst($order['estado'])) ?></strong>.</p><p>Total: <strong><?= e(format_cop((float) $order['total'])) ?></strong></p><a class="button" href="<?= base_url('perfil.php') ?>">Ver mis pedidos <span>↗</span></a></section>
<?php require __DIR__ . '/../includes/footer.php'; ?>