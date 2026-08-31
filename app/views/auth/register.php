<?php ob_start(); ?>
<section>
    <h1>Crear cuenta</h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= (BASE_URL ?: '') ?>/register">
        <div class="field">
            <label for="name">Nombre</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="field">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="field">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="field">
            <label for="confirm_password">Confirmar contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button class="submit-btn" type="submit">Registrarme</button>

        <p class="muted">
            ¿Ya tienes cuenta? <a href="<?= (BASE_URL ?: '') ?>/login">Inicia sesión</a>
        </p>
    </form>
</section>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
