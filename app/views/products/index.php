<?php ob_start(); ?>
<section>
    <h1>Productos</h1>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <div class="product-price">$<?= number_format($product['price'], 2) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
