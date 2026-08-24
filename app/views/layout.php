<!DOCTYPE html>
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
            gap: 2rem;
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
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">✨ Amelie Joyería</div>
            <ul>
                <li><a href="/">Inicio</a></li>
                <li><a href="/productos">Productos</a></li>
                <li><a href="/contacto">Contacto</a></li>
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
