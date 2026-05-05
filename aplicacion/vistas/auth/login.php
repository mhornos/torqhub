<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('auth.login.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">

</head>

<body>
    <h1><?= htmlspecialchars(t('auth.login.titulo')) ?></h1>

    <!-- // mensajes flash -->
    <?php if ($mensaje = flash_get('error')): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <?php if ($mensaje = flash_get('ok')): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <!-- // formulario de login -->
    <form method="post" action="<?= url('/login') ?>">
        <?= csrf_campo() ?>

        <div>
            <label for="email"><?= htmlspecialchars(t('auth.login.email')) ?></label>
            <input
                type="email"
                name="email"
                id="email"
                required
                maxlength="120"
                autocomplete="email">
        </div>

        <div>
            <label for="password"><?= htmlspecialchars(t('auth.login.password')) ?></label>
            <input
                type="password"
                name="password"
                id="password"
                required
                maxlength="255"
                autocomplete="current-password">
        </div>
        <br>
        <button type="submit"><?= htmlspecialchars(t('auth.login.boton')) ?></button>
    </form>

    <p>
        <a href="<?= url('/password/olvidada') ?>"><?= htmlspecialchars(t('auth.login.password_olvidada')) ?></a>
    </p>

</body>

</html>