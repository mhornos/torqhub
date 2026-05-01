<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('inicio.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">

</head>
<body>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['usuario'])): ?>
        <p><?= htmlspecialchars(t('inicio.saludo')) ?>, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>!</p>
    <?php endif; ?>

    <h1><?= htmlspecialchars(t('inicio.titulo')) ?></h1>
    <p><?= htmlspecialchars(t('inicio.bienvenida')) ?>!</p>
</body>
</html>