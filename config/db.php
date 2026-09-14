<?php
declare(strict_types=1);

$host = getenv('JOYERIA_DB_HOST') ?: '127.0.0.1';
$database = getenv('JOYERIA_DB_NAME') ?: 'joyerias';
$username = getenv('JOYERIA_DB_USER') ?: 'root';
$password = getenv('JOYERIA_DB_PASSWORD') ?: '';

try {
	$pdo = new PDO(
		"mysql:host={$host};dbname={$database};charset=utf8mb4",
		$username,
		$password,
		[
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		]
	);
} catch (PDOException $exception) {
	$pdo = null;
}
