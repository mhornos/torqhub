<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('auth.registro.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">

</head>

<body>
    <h1><?= htmlspecialchars(t('auth.registro.titulo')) ?></h1>

    <!-- mensajes flash -->
    <?php if ($mensaje = flash_get('error')): ?>
        <p class="mensaje-error"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <?php if ($mensaje = flash_get('ok')): ?>
        <p class="mensaje-ok"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <!-- // formulario de registro -->
    <form action="<?= url('/registro') ?>" method="POST" id="formulario-registro">
        <?= csrf_campo() ?>

        <div>
            <label for="nombre"><?= htmlspecialchars(t('auth.registro.nombre')) ?></label>
            <input
                type="text"
                name="nombre"
                id="nombre"
                required
                maxlength="80"
                pattern="^(?!.*\.\.)(?!.*\.$)[a-z0-9._]+$"
                title="<?= htmlspecialchars(t('auth.registro.nombre_ayuda')) ?>"
                autocomplete="username">
            <small><?= htmlspecialchars(t('auth.registro.nombre_ayuda')) ?></small>
        </div>

        <div>
            <label for="email"><?= htmlspecialchars(t('auth.registro.email')) ?></label>
            <input
                type="email"
                name="email"
                id="email"
                required
                maxlength="120"
                autocomplete="email">
        </div>

        <div>
            <label for="password"><?= htmlspecialchars(t('auth.registro.password')) ?></label>
            <input
                type="password"
                name="password"
                id="password"
                required
                minlength="8"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                title="<?= htmlspecialchars(t('auth.registro.password_ayuda')) ?>"
                autocomplete="new-password">
        </div>

        <div>
            <label for="password_repetida"><?= htmlspecialchars(t('auth.registro.password_repetida')) ?>:</label>
            <input
                type="password"
                name="password_repetida"
                id="password_repetida"
                required
                minlength="8"
                maxlength="255"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                title="<?= htmlspecialchars(t('auth.registro.password_ayuda')) ?>"
                autocomplete="new-password"> <br>
            <div id="error-password-repetida" class="mensaje-error-campo" style="display:none;">
                <?= htmlspecialchars(t('auth.registro.error_password_repetida')) ?>
            </div>
            <small><?= htmlspecialchars(t('auth.registro.password_ayuda')) ?></small>
        </div>

        <br>
        <button type="submit"><?= htmlspecialchars(t('auth.registro.boton')) ?></button>
    </form>

    <script src="<?= url('/public/js/auth/registro.js') ?>"></script>
</body>

</html>