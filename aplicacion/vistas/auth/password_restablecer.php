<?php
    $token = isset($token) && is_string($token) ? trim($token) : '';
    
    if ($token === '') {
        flash_set('error', t('auth.error.enlace_restablecer_no_valido'));
        header('Location: ' . url('/login'));
        exit;
    }
    
    $errores = isset($errores) && is_array($errores) ? $errores : [];
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('auth.password_restablecer.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>

    <h1><?= htmlspecialchars(t('auth.password_restablecer.titulo')) ?></h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form action="<?= url('/password/restablecer') ?>" method="POST">
        <?= csrf_campo() ?>

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div>
            <label for="password"><?= htmlspecialchars(t('auth.password_restablecer.password')) ?></label>
            <input type="password" name="password" id="password" required>
        </div>

        <div>
            <label for="password_repetida"><?= htmlspecialchars(t('auth.password_restablecer.password_repetida')) ?></label>
            <input type="password" name="password_repetida" id="password_repetida" required>
        </div>

        <br>

        <button type="submit"><?= htmlspecialchars(t('auth.password_restablecer.boton')) ?></button>
    </form>

</body>
</html>