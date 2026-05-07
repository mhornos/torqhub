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
    <main class="perfil-contenedor perfil-contenedor--vehiculo">
        <header class="perfil-vehiculo-detalle-cabecera">
            <div>
                <p class="perfil-cabecera__etiqueta">
                    <?= htmlspecialchars(t('perfil.vehiculo.titulo_pagina')) ?>
                </p>

                <h1>
                    <?= htmlspecialchars($vehiculo['marca']) ?>
                    <?= htmlspecialchars($vehiculo['modelo']) ?>
                </h1>

                <p class="perfil-cabecera__texto">
                    <?= htmlspecialchars(t('perfil.vehiculo.propietario')) ?>:
                    <a href="<?= escapar(url('/perfil?usuario=' . urlencode($vehiculo['autor_nombre']))) ?>">
                        @<?= htmlspecialchars($vehiculo['autor_nombre']) ?>
                    </a>
                </p>
            </div>

            <div class="perfil-vehiculo-detalle-cabecera__acciones">
                <a href="<?= escapar(url('/perfil?usuario=' . urlencode($vehiculo['autor_nombre']))) ?>" class="perfil-boton-enlace">
                    <?= htmlspecialchars(t('perfil.vehiculo.volver_perfil')) ?>
                </a>
            </div>
        </header>

        <section class="perfil-vehiculo-detalle">
            <div class="perfil-vehiculo-detalle__imagen">
                <?php if (!empty($vehiculo['imagen'])): ?>
                    <img
                        src="<?= escapar(url_publica_segura('uploads/vehiculos/' . $vehiculo['imagen'])) ?>"
                        alt="<?= htmlspecialchars(t('perfil.vehiculo.alt_imagen')) ?>">
                <?php else: ?>
                    <div class="perfil-vehiculo__imagen-vacia">
                        <?= htmlspecialchars(t('perfil.ver.sin_imagen')) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="perfil-vehiculo-detalle__datos">
                <h2><?= htmlspecialchars(t('perfil.vehiculo.detalles_publicos')) ?></h2>

                <dl class="perfil-datos-vehiculo">
                    <div>
                        <dt><?= htmlspecialchars(t('perfil.vehiculo.any')) ?></dt>
                        <dd><?= htmlspecialchars($vehiculo['any']) ?></dd>
                    </div>

                    <?php if (!empty($vehiculo['carroceria'])): ?>
                        <div>
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.carroceria')) ?></dt>
                            <dd><?= htmlspecialchars($vehiculo['carroceria']) ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($vehiculo['tipo_combustible'])): ?>
                        <div>
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.combustible')) ?></dt>
                            <dd><?= htmlspecialchars($vehiculo['tipo_combustible']) ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($vehiculo['tipo_cambio'])): ?>
                        <div>
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.cambio')) ?></dt>
                            <dd><?= htmlspecialchars($vehiculo['tipo_cambio']) ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($vehiculo['potencia_cv'])): ?>
                        <div>
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.potencia')) ?></dt>
                            <dd><?= (int) $vehiculo['potencia_cv'] ?> cv</dd>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($vehiculo['cilindrada_cm3'])): ?>
                        <div>
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.cilindrada')) ?></dt>
                            <dd><?= (int) $vehiculo['cilindrada_cm3'] ?> cm³</dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>
        </section>
    </main>
</body>

</html>