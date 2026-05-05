<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('auth.password_olvidada.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>

    <h1><?= htmlspecialchars(t('auth.password_olvidada.titulo')) ?></h1>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form action="<?= url('/password/olvidada') ?>" method="POST">
        <?= csrf_campo() ?>

        <div>
            <label for="email"><?= htmlspecialchars(t('auth.password_olvidada.email')) ?></label>
            <input
                type="email"
                name="email"
                id="email"
                required
                maxlength="120"
                autocomplete="email">
        </div>

        <br>

        <button type="submit"><?= htmlspecialchars(t('auth.password_olvidada.boton')) ?></button>
    </form>

    <p>
        <a href="<?= url('/login') ?>"><?= htmlspecialchars(t('auth.password_olvidada.volver_login')) ?></a>
    </p>

</body>

</html>