<?php ob_start(); ?>
<section>
    <h1>Iniciar sesión</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= (BASE_URL ?: '') ?>/login">
        <div class="field">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="field">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button class="submit-btn" type="submit">Entrar</button>

        <p class="muted">
            ¿No tienes cuenta? <a href="<?= (BASE_URL ?: '') ?>/register">Regístrate</a>
        </p>
    </form>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
