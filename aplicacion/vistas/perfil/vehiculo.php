<?php
    if (!isset($vehiculo) || !is_array($vehiculo)) {
        flash_set('error', t('perfil.vehiculo.error.cargar'));
        header('Location: ' . url('/comunidad'));
        exit;
    }
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('perfil.vehiculo.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>

    <h1>
        <?= htmlspecialchars($vehiculo['marca']) ?>
        <?= htmlspecialchars($vehiculo['modelo']) ?>
    </h1>

    <p>
        <?= htmlspecialchars(t('perfil.vehiculo.propietario')) ?>:
        <a href="<?= url('/perfil?usuario=' . urlencode($vehiculo['autor_nombre'])) ?>">
            @<?= htmlspecialchars($vehiculo['autor_nombre']) ?>
        </a>
    </p>

    <?php if (!empty($vehiculo['imagen'])): ?>
        <img 
            src="<?= url('/public/uploads/vehiculos/' . rawurlencode($vehiculo['imagen'])) ?>" 
            alt="<?= htmlspecialchars(t('perfil.vehiculo.alt_imagen')) ?>"
            style="max-width: 700px; width: 100%; display:block; margin-bottom:20px;"
        >
    <?php endif; ?>

    <section class="perfil-bloque">
        <h2><?= htmlspecialchars(t('perfil.vehiculo.detalles_publicos')) ?></h2>

        <p><strong><?= htmlspecialchars(t('perfil.vehiculo.any')) ?>:</strong> <?= htmlspecialchars($vehiculo['any']) ?></p>

        <?php if (!empty($vehiculo['carroceria'])): ?>
            <p><strong><?= htmlspecialchars(t('perfil.vehiculo.carroceria')) ?>:</strong> <?= htmlspecialchars($vehiculo['carroceria']) ?></p>
        <?php endif; ?>

        <?php if (!empty($vehiculo['tipo_combustible'])): ?>
            <p><strong><?= htmlspecialchars(t('perfil.vehiculo.combustible')) ?>:</strong> <?= htmlspecialchars($vehiculo['tipo_combustible']) ?></p>
        <?php endif; ?>

        <?php if (!empty($vehiculo['tipo_cambio'])): ?>
            <p><strong><?= htmlspecialchars(t('perfil.vehiculo.cambio')) ?>:</strong> <?= htmlspecialchars($vehiculo['tipo_cambio']) ?></p>
        <?php endif; ?>

        <?php if (!empty($vehiculo['potencia_cv'])): ?>
            <p><strong><?= htmlspecialchars(t('perfil.vehiculo.potencia')) ?>:</strong> <?= (int) $vehiculo['potencia_cv'] ?> cv</p>
        <?php endif; ?>

        <?php if (!empty($vehiculo['cilindrada_cm3'])): ?>
            <p><strong><?= htmlspecialchars(t('perfil.vehiculo.cilindrada')) ?>:</strong> <?= (int) $vehiculo['cilindrada_cm3'] ?> cm³</p>
        <?php endif; ?>
    </section>

    <p>
        <a href="<?= url('/perfil?usuario=' . urlencode($vehiculo['autor_nombre'])) ?>">
            <?= htmlspecialchars(t('perfil.vehiculo.volver_perfil')) ?>
        </a>
    </p>

</body>
</html>