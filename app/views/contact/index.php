<?php ob_start(); ?>
<section>
    <h1>Contacto</h1>
    <p>Escríbenos para consultas sobre pedidos, diseños y entregas.</p>
    <ul>
        <li>Email: contacto@ameliejoyeria.com</li>
        <li>Teléfono: +56 9 1234 5678</li>
        <li>Dirección: Santiago, Chile</li>
    </ul>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
