<?php
$base = BASE_URL ?: '';
?><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amelie Joyería</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', serif;
            line-height: 1.6;
            color: #333;
        }
        
        header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: #ecf0f1;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #f39c12;
        }
        
        nav ul {
            list-style: none;
            display: flex;
            gap: 1.2rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        nav a {
            color: #ecf0f1;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: #f39c12;
        }
        
        main {
            min-height: 70vh;
            padding: 3rem 0;
        }
        
        footer {
            background: #2c3e50;
            color: #ecf0f1;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .auth-links {
            display: inline-flex;
            gap: 0.75rem;
            align-items: center;
        }
        .btn-link {
            color: #f39c12;
            text-decoration: none;
            font-weight: 600;
        }
        form {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            max-width: 480px;
            margin: 0 auto;
        }
        .field {
            margin-bottom: 1rem;
        }
        .field label {
            display: block;
            margin-bottom: 0.4rem;
            font-weight: bold;
        }
        .field input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            font-size: 1rem;
        }
        .submit-btn {
            width: 100%;
            background: #f39c12;
            color: white;
            border: none;
            padding: 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
        }
        .muted {
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">✨ Amelie Joyería</div>
            <ul>
                <li><a href="<?= $base ?: '' ?>/">Inicio</a></li>
                <li><a href="<?= $base ?: '' ?>/productos">Productos</a></li>
                <li><a href="<?= $base ?: '' ?>/contacto">Contacto</a></li>
                <?php if (!empty($_SESSION['user'])): ?>
                    <li><span style="color:#f39c12; font-weight:bold;">Hola, <?= htmlspecialchars($_SESSION['user']['name']) ?></span></li>
                    <li><a href="<?= $base ?: '' ?>/logout">Cerrar sesión</a></li>
                <?php else: ?>
                    <li class="auth-links">
                        <a href="<?= $base ?: '' ?>/login">Iniciar sesión</a>
                        <a href="<?= $base ?: '' ?>/register">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    
    <main class="container">
        <?php echo $content; ?>
    </main>
    
    <footer>
        <p>&copy; 2024 Amelie Joyería. Todos los derechos reservados.</p>
        <p>Elegancia y belleza en cada pieza</p>
    </footer>
</body>
</html>
