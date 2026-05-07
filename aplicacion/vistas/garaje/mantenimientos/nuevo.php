<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('garaje.mantenimiento.nuevo.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <main class="garaje-formulario-contenedor">
        <header class="garaje-formulario-cabecera">
            <h1><?= htmlspecialchars(t('garaje.mantenimiento.nuevo.titulo')) ?></h1>
        </header>

        <?php
        $datos_formulario = $datos_formulario ?? [];
        $vehiculo = $vehiculo ?? [];
        ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <section class="garaje-formulario-contexto">
            <span><?= htmlspecialchars(t('garaje.mantenimiento.vehiculo')) ?></span>

            <strong>
                <?= htmlspecialchars($vehiculo['marca'] ?? '') ?>
                <?= htmlspecialchars($vehiculo['modelo'] ?? '') ?>

                <?php if (!empty($vehiculo['any'])): ?>
                    (<?= (int) $vehiculo['any'] ?>)
                <?php endif; ?>
            </strong>
        </section>

        <form action="<?= url('/garaje/mantenimientos/nuevo') ?>" method="POST" class="garaje-formulario-panel formulario-mantenimiento"> <?= csrf_campo() ?>

            <input type="hidden" name="vehiculo_id" value="<?= (int) ($vehiculo['id'] ?? 0) ?>">

            <div>
                <label for="fecha"><?= htmlspecialchars(t('garaje.mantenimiento.fecha')) ?>: *</label>
                <input
                    type="date"
                    id="fecha"
                    name="fecha"
                    value="<?= htmlspecialchars($datos_formulario['fecha'] ?? '') ?>"
                    required>
            </div>

            <div>
                <label for="tipo"><?= htmlspecialchars(t('garaje.mantenimiento.tipo')) ?>: *</label>
                <input
                    type="text"
                    id="tipo"
                    name="tipo"
                    maxlength="100"
                    value="<?= htmlspecialchars($datos_formulario['tipo'] ?? '') ?>"
                    required>
            </div>

            <div class="garaje-formulario-campo--ancho-completo">
                <label for="descripcion"><?= htmlspecialchars(t('garaje.mantenimiento.descripcion')) ?>:</label>
                <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($datos_formulario['descripcion'] ?? '') ?></textarea>
            </div>

            <div>
                <label for="kilometros"><?= htmlspecialchars(t('garaje.mantenimiento.kilometros')) ?>:</label>
                <input
                    type="number"
                    id="kilometros"
                    name="kilometros"
                    min="0"
                    step="1"
                    value="<?= htmlspecialchars($datos_formulario['kilometros'] ?? '') ?>">
            </div>

            <div>
                <label for="coste"><?= htmlspecialchars(t('garaje.mantenimiento.coste')) ?> (€):</label>
                <input
                    type="number"
                    id="coste"
                    name="coste"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($datos_formulario['coste'] ?? '') ?>">
            </div>

            <div class="garaje-formulario-acciones">
                <a href="<?= url('/garaje/ver?id=' . (int) ($vehiculo['id'] ?? 0)) ?>" class="garaje-boton-enlace">
                    <?= htmlspecialchars(t('garaje.mantenimiento.volver')) ?>
                </a>

                <button type="submit">
                    <?= htmlspecialchars(t('garaje.mantenimiento.guardar')) ?>
                </button>
            </div>
        </form>
    </main>
</body>

</html>