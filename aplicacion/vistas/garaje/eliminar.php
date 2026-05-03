<?php
if (!isset($vehiculo) || !is_array($vehiculo)) {
    flash_set('error', t('garaje.eliminar.error.cargar'));
    header('Location: ' . url('/garaje'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('garaje.eliminar.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <h1><?= htmlspecialchars(t('garaje.eliminar.titulo')) ?></h1>

    <p><?= htmlspecialchars(t('garaje.eliminar.confirmacion')) ?></p>

    <p>
        <strong><?= htmlspecialchars($vehiculo['marca']) ?> <?= htmlspecialchars($vehiculo['modelo']) ?></strong>
        <?php if (!empty($vehiculo['any'])): ?>
            (<?= (int) $vehiculo['any'] ?>)
        <?php endif; ?>
    </p>

    <form method="post" action="<?= url('/garaje/eliminar') ?>">
        <?= csrf_campo() ?>

        <input type="hidden" name="id" value="<?= (int) $vehiculo['id'] ?>">

        <a href="<?= url('/garaje/ver?id=' . (int) $vehiculo['id']) ?>">
            <?= htmlspecialchars(t('garaje.eliminar.cancelar')) ?>
        </a>

        <button type="submit">
            <?= htmlspecialchars(t('garaje.eliminar.eliminar')) ?>
        </button>
    </form>
</body>

</html>