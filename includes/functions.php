<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

require_once __DIR__ . '/../config/db.php';

function e(?string $value): string
{
	return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function format_cop(float $amount): string
{
	if ($amount > 0 && $amount < 1000) $amount *= 1000;
	return '$ ' . number_format($amount, 0, ',', '.') . ' COP';
}

function product_image(array $product): string
{
	$images = [
		'Anillo Aura' => '../assets/images/anillo-aura.jpg',
		'Collar Numa' => '../assets/images/collar-numa.jpg',
		'Pendientes Vela' => '../assets/images/pendientes-vela.webp',
		'Pulsera Origen' => '../assets/images/pulsera-origen.webp',
	];
	return $images[$product['nombre']] ?? (!empty($product['imagen']) ? (string) $product['imagen'] : '../assets/images/joya-generica.webp');
}

function base_url(string $path = ''): string
{
	return '/trabajo/joyeria/' . ltrim($path, '/');
}

function redirect(string $path): never
{
	header('Location: ' . base_url($path));
	exit;
}

function flash(string $type, string $message): void
{
	$_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function take_flashes(): array
{
	$flashes = $_SESSION['flash'] ?? [];
	unset($_SESSION['flash']);
	return $flashes;
}

function current_user(): ?array
{
	return $_SESSION['user'] ?? null;
}

function csrf_token(): string
{
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
	$token = $_POST['csrf_token'] ?? '';
	if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
		http_response_code(419);
		exit('Solicitud no válida. Vuelve a intentarlo.');
	}
}

function is_admin(): bool
{
	return (current_user()['rol'] ?? '') === 'admin';
}

function admin_count(): int
{
	global $pdo;
	if (!$pdo) return 0;
	return (int) $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'admin'")->fetchColumn();
}

function require_admin(): void
{
	if (!current_user()) {
		require_login();
	}
	if (!is_admin()) {
		http_response_code(403);
		exit('No tienes permisos para acceder a esta sección.');
	}
}

function require_login(): void
{
	if (!current_user()) {
		flash('error', 'Inicia sesión para continuar.');
		redirect('login.php');
	}
}

function cart_count(): int
{
	return array_sum($_SESSION['cart'] ?? []);
}

function get_products(?string $search = null, int $limit = 0): array
{
	global $pdo;
	if (!$pdo) return [];
	$sql = 'SELECT * FROM productos WHERE activo = 1';
	$params = [];
	if ($search) {
		$sql .= ' AND (nombre LIKE :search OR categoria LIKE :search)';
		$params['search'] = '%' . $search . '%';
	}
	$sql .= ' ORDER BY destacado DESC, id DESC';
	if ($limit > 0) $sql .= ' LIMIT ' . $limit;
	$statement = $pdo->prepare($sql);
	$statement->execute($params);
	return $statement->fetchAll();
}

function get_product(int $id): ?array
{
	global $pdo;
	if (!$pdo) return null;
	$statement = $pdo->prepare('SELECT * FROM productos WHERE id = ? AND activo = 1');
	$statement->execute([$id]);
	return $statement->fetch() ?: null;
}

function cart_products(): array
{
	global $pdo;
	if (!$pdo || empty($_SESSION['cart'])) return [];
	$ids = array_keys($_SESSION['cart']);
	$placeholders = implode(',', array_fill(0, count($ids), '?'));
	$statement = $pdo->prepare("SELECT * FROM productos WHERE id IN ($placeholders) AND activo = 1");
	$statement->execute($ids);
	$products = [];
	foreach ($statement->fetchAll() as $product) {
		$product['cantidad'] = (int) $_SESSION['cart'][$product['id']];
		$products[] = $product;
	}
	return $products;
}

function cart_total(array $items): float
{
	return array_sum(array_map(fn($item) => (float) $item['precio'] * (int) $item['cantidad'], $items));
}

function get_orders(int $user_id): array
{
	global $pdo;
	if (!$pdo) return [];
	$statement = $pdo->prepare('SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY creado_en DESC');
	$statement->execute([$user_id]);
	return $statement->fetchAll();
}

function get_order_items(int $order_id): array
{
	global $pdo;
	$statement = $pdo->prepare('SELECT * FROM pedido_detalles WHERE pedido_id = ? ORDER BY id');
	$statement->execute([$order_id]);
	return $statement->fetchAll();
}
