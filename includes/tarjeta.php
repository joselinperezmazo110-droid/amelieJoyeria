<article class="product-card">
	<a href="<?= base_url('producto.php?id=' . (int) $product['id']) ?>" class="product-image">
		<img src="<?= e(product_image($product)) ?>" alt="<?= e($product['nombre']) ?>" loading="lazy">
		<?php if ((int) ($product['descuento_porcentaje'] ?? 0) > 0): ?><span class="sale-badge">-<?= (int) $product['descuento_porcentaje'] ?>%</span><?php endif; ?>
	</a>
	<div class="product-info"><div><p class="eyebrow"><?= e($product['categoria']) ?></p><h3><?= e($product['nombre']) ?></h3></div><div class="product-prices"><?php if ((int) ($product['descuento_porcentaje'] ?? 0) > 0): ?><del><?= e(format_cop((float) $product['precio'])) ?></del><?php endif; ?><strong><?= e(format_cop(product_price($product))) ?></strong></div></div>
</article>
