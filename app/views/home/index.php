<?php
ob_start();
?>
<style>
    .hero {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        color: white;
        padding: 4rem 2rem;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 3rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .hero p {
        font-size: 1.3rem;
        margin-bottom: 2rem;
        opacity: 0.95;
    }
    
    .cta-button {
        display: inline-block;
        background: white;
        color: #e67e22;
        padding: 1rem 2rem;
        text-decoration: none;
        border-radius: 50px;
        font-weight: bold;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }
    
    .products-section {
        margin: 3rem 0;
    }
    
    .products-section h2 {
        font-size: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        color: #2c3e50;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .product-card {
        background: white;
        border: 1px solid #ecf0f1;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    
    .product-card h3 {
        color: #f39c12;
        margin-bottom: 0.5rem;
    }
    
    .product-price {
        font-size: 1.5rem;
        color: #e67e22;
        font-weight: bold;
        margin: 1rem 0;
    }
    
    .features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        margin: 3rem 0;
    }
    
    .feature {
        text-align: center;
        padding: 2rem;
    }
    
    .feature-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .feature h3 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
</style>

<section class="hero">
    <h1>✨ Bienvenido a Amelie Joyería</h1>
    <p>Descubre nuestras joyas exquisitas, cada pieza es una obra de arte hecha con amor y elegancia</p>
    <a href="#productos" class="cta-button">Ver Catálogo</a>
</section>

<section class="features">
    <div class="feature">
        <div class="feature-icon">💎</div>
        <h3>Diseño Exclusivo</h3>
        <p>Piezas únicas diseñadas con atención al detalle</p>
    </div>
    <div class="feature">
        <div class="feature-icon">🔒</div>
        <h3>Calidad Premium</h3>
        <p>Materiales de alta calidad garantizados</p>
    </div>
    <div class="feature">
        <div class="feature-icon">🚚</div>
        <h3>Envío Seguro</h3>
        <p>Entrega rápida y segura a domicilio</p>
    </div>
</section>

<section class="products-section" id="productos">
    <h2>Productos Destacados</h2>
    <div class="products-grid">
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <div class="product-price">$<?= number_format($p['price'], 2) ?></div>
                <button style="background: #f39c12; color: white; border: none; padding: 0.5rem 1rem; border-radius: 5px; cursor: pointer;">
                    Añadir al carrito
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
