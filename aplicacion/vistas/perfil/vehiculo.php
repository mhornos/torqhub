<?php
if (!isset($vehiculo) || !is_array($vehiculo)) {
    flash_set('error', t('perfil.vehiculo.error.cargar'));
    header('Location: ' . url('/comunidad'));
    exit;
}

$imagenes_vehiculo = isset($imagenes_vehiculo) && is_array($imagenes_vehiculo)
    ? $imagenes_vehiculo
    : [];

if (empty($imagenes_vehiculo) && !empty($vehiculo['imagen'])) {
    $imagenes_vehiculo[] = [
        'nombre_archivo' => $vehiculo['imagen'],
        'texto_alt' => null,
    ];
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
        <section class="perfil-vehiculo-detalle-cabecera">
            <div>
                <p class="perfil-subtitulo"><?= htmlspecialchars(t('perfil.vehiculo.titulo_pagina')) ?></p>

                <h1>
                    <?= htmlspecialchars($vehiculo['marca']) ?>
                    <?= htmlspecialchars($vehiculo['modelo']) ?>
                </h1>

                <p>
                    <?= htmlspecialchars(t('perfil.vehiculo.propietario')) ?>:
                    <a href="<?= escapar(url('/perfil?usuario=' . urlencode($vehiculo['autor_nombre']))) ?>">
                        @<?= htmlspecialchars($vehiculo['autor_nombre']) ?>
                    </a>
                </p>
            </div>

            <a href="<?= escapar(url('/perfil?usuario=' . urlencode($vehiculo['autor_nombre']))) ?>">
                <?= htmlspecialchars(t('perfil.vehiculo.volver_perfil')) ?>
            </a>
        </section>

        <section class="perfil-vehiculo-detalle">
            <div class="perfil-vehiculo-detalle__galeria">
                <?php if (!empty($imagenes_vehiculo)): ?>
                    <?php
                    $primera_imagen = $imagenes_vehiculo[0];
                    $src_primera_imagen = url_publica_segura('uploads/vehiculos/' . $primera_imagen['nombre_archivo']);
                    $alt_primera_imagen = $primera_imagen['texto_alt']
                        ?: t('perfil.vehiculo.alt_imagen');
                    ?>

                    <section class="carrusel-vehiculo" aria-label="<?= escapar(t('garaje.detalle.galeria')) ?>">
                        <div class="carrusel-vehiculo__visor">
                            <button
                                type="button"
                                class="carrusel-vehiculo__control carrusel-vehiculo__control--anterior"
                                aria-label="<?= escapar(t('garaje.detalle.foto_anterior')) ?>">
                                ‹
                            </button>

                            <img
                                class="carrusel-vehiculo__imagen"
                                src="<?= escapar($src_primera_imagen) ?>"
                                alt="<?= escapar($alt_primera_imagen) ?>">

                            <button
                                type="button"
                                class="carrusel-vehiculo__control carrusel-vehiculo__control--siguiente"
                                aria-label="<?= escapar(t('garaje.detalle.foto_siguiente')) ?>">
                                ›
                            </button>

                            <span class="carrusel-vehiculo__contador">
                                1 / <?= count($imagenes_vehiculo) ?>
                            </span>
                        </div>

                        <?php if (count($imagenes_vehiculo) > 1): ?>
                            <div class="carrusel-vehiculo__miniaturas">
                                <?php foreach ($imagenes_vehiculo as $indice => $imagen_vehiculo): ?>
                                    <?php
                                    $src_miniatura = url_publica_segura('uploads/vehiculos/' . $imagen_vehiculo['nombre_archivo']);
                                    $alt_miniatura = $imagen_vehiculo['texto_alt'] ?: t('perfil.vehiculo.alt_imagen');
                                    ?>

                                    <button
                                        type="button"
                                        class="carrusel-vehiculo__miniatura <?= $indice === 0 ? 'carrusel-vehiculo__miniatura--activa' : '' ?>"
                                        data-indice="<?= (int) $indice ?>"
                                        data-src="<?= escapar($src_miniatura) ?>"
                                        data-alt="<?= escapar($alt_miniatura) ?>"
                                        aria-label="<?= escapar(t('garaje.detalle.foto_contador') . ' ' . ($indice + 1)) ?>">
                                        <img
                                            src="<?= escapar($src_miniatura) ?>"
                                            alt="<?= escapar($alt_miniatura) ?>">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php else: ?>
                    <div class="detalle-vehiculo-placeholder-imagen">
                        <span class="detalle-vehiculo-placeholder-texto">
                            <?= htmlspecialchars(t('garaje.detalle.imagen_no_disponible')) ?>
                        </span>

                        <small class="detalle-vehiculo-placeholder-ayuda">
                            <?= htmlspecialchars(t('garaje.detalle.imagen_ayuda')) ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>

            <section class="perfil-vehiculo-detalle__datos perfil-bloque">
                <h2><?= htmlspecialchars(t('perfil.vehiculo.detalles_publicos')) ?></h2>

                <dl class="perfil-datos-vehiculo">
                    <div class="perfil-dato">
                        <dt><?= htmlspecialchars(t('perfil.vehiculo.any')) ?></dt>
                        <dd><?= htmlspecialchars($vehiculo['any']) ?></dd>
                    </div>

                    <?php if (!empty($vehiculo['carroceria'])): ?>
                        <div class="perfil-dato">
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.carroceria')) ?></dt>
                            <dd><?= htmlspecialchars($vehiculo['carroceria']) ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($vehiculo['tipo_combustible'])): ?>
                        <div class="perfil-dato">
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.combustible')) ?></dt>
                            <dd><?= htmlspecialchars($vehiculo['tipo_combustible']) ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($vehiculo['tipo_cambio'])): ?>
                        <div class="perfil-dato">
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.cambio')) ?></dt>
                            <dd><?= htmlspecialchars($vehiculo['tipo_cambio']) ?></dd>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($vehiculo['potencia_cv'])): ?>
                        <div class="perfil-dato">
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.potencia')) ?></dt>
                            <dd><?= (int) $vehiculo['potencia_cv'] ?> cv</dd>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($vehiculo['cilindrada_cm3'])): ?>
                        <div class="perfil-dato">
                            <dt><?= htmlspecialchars(t('perfil.vehiculo.cilindrada')) ?></dt>
                            <dd><?= (int) $vehiculo['cilindrada_cm3'] ?> cm³</dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </section>
        </section>
    </main>

    <script src="<?= url('/public/js/garaje/carrusel-vehiculo.js') ?>"></script>
</body>

</html>