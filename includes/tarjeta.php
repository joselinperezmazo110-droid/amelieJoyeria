<article class="product-card">
	<a href="<?= base_url('producto.php?id=' . (int) $product['id']) ?>" class="product-image">
		<img src="<?= e(product_image($product)) ?>" alt="<?= e($product['nombre']) ?>" loading="lazy">
	</a>
	<div class="product-info"><div><p class="eyebrow"><?= e($product['categoria']) ?></p><h3><?= e($product['nombre']) ?></h3></div><strong><?= e(format_cop((float) $product['precio'])) ?></strong></div>
</article>
