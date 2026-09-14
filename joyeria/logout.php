<?php
require_once __DIR__ . '/../includes/functions.php';
unset($_SESSION['user']);
flash('success', 'Sesión cerrada.');
redirect('index.php');
