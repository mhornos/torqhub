<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('garaje.mantenimiento.editar.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <h1><?= htmlspecialchars(t('garaje.mantenimiento.editar.titulo')) ?></h1>

    <?php
    $vehiculo = $vehiculo ?? [];
    $mantenimiento = $mantenimiento ?? [];
    $datos_formulario = $datos_formulario ?? [];

    $fecha_valor = $datos_formulario['fecha'] ?? $mantenimiento['fecha'] ?? '';
    $tipo_valor = $datos_formulario['tipo'] ?? $mantenimiento['tipo'] ?? '';
    $descripcion_valor = $datos_formulario['descripcion'] ?? $mantenimiento['descripcion'] ?? '';
    $kilometros_valor = $datos_formulario['kilometros'] ?? $mantenimiento['kilometros'] ?? '';
    $coste_valor = $datos_formulario['coste'] ?? $mantenimiento['coste'] ?? '';
    ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <p>
    <ul>
        <li>
            <strong><?= htmlspecialchars(t('garaje.mantenimiento.vehiculo')) ?>:</strong>
            <?= htmlspecialchars($vehiculo['marca'] ?? '') ?>
            <?= htmlspecialchars($vehiculo['modelo'] ?? '') ?>

            <?php if (!empty($vehiculo['any'])): ?>
                (<?= (int) $vehiculo['any'] ?>)
            <?php endif; ?>
        </li>
    </ul>
    </p>

    <form action="<?= url('/garaje/mantenimientos/editar') ?>" method="POST">
        <?= csrf_campo() ?>

        <input type="hidden" name="mantenimiento_id" value="<?= (int) ($mantenimiento['id'] ?? 0) ?>">

        <div>
            <label for="fecha"><?= htmlspecialchars(t('garaje.mantenimiento.fecha')) ?>: *</label>
            <input
                type="date"
                id="fecha"
                name="fecha"
                value="<?= htmlspecialchars((string) $fecha_valor) ?>"
                required>
        </div>

        <div>
            <label for="tipo"><?= htmlspecialchars(t('garaje.mantenimiento.tipo')) ?>: *</label>
            <input
                type="text"
                id="tipo"
                name="tipo"
                maxlength="100"
                value="<?= htmlspecialchars((string) $tipo_valor) ?>"
                required>
        </div>

        <div>
            <label for="descripcion"><?= htmlspecialchars(t('garaje.mantenimiento.descripcion')) ?>:</label><br>
            <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars((string) $descripcion_valor) ?></textarea>
        </div>

        <div>
            <label for="kilometros"><?= htmlspecialchars(t('garaje.mantenimiento.kilometros')) ?>:</label>
            <input
                type="number"
                id="kilometros"
                name="kilometros"
                min="0"
                step="1"
                value="<?= htmlspecialchars((string) $kilometros_valor) ?>">
        </div>

        <div>
            <label for="coste"><?= htmlspecialchars(t('garaje.mantenimiento.coste')) ?> (€):</label>
            <input
                type="number"
                id="coste"
                name="coste"
                min="0"
                step="0.01"
                value="<?= htmlspecialchars((string) $coste_valor) ?>">
        </div>

        <br>

        <a href="<?= url('/garaje/ver?id=' . (int) ($vehiculo['id'] ?? 0)) ?>">
            <?= htmlspecialchars(t('garaje.mantenimiento.volver')) ?>
        </a>

        <button type="submit">
            <?= htmlspecialchars(t('garaje.mantenimiento.guardar_cambios')) ?>
        </button>
    </form>
</body>

</html>