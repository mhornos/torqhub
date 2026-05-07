<?php
$vehiculos = isset($vehiculos) && is_array($vehiculos) ? $vehiculos : [];
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('garaje.index.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <main class="garaje-contenedor">
        <header class="garaje-cabecera">
            <div>
                <h1><?= htmlspecialchars(t('garaje.index.titulo')) ?></h1>
            </div>

            <div class="garaje-cabecera__acciones">
                <a href="<?= url('/garaje/nuevo') ?>" class="garaje-boton-enlace">
                    <?= htmlspecialchars(t('garaje.index.anadir_vehiculo')) ?>
                </a>
            </div>
        </header>

        <?php if ($m = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <?php if (count($vehiculos) === 0): ?>
            <section class="garaje-estado-vacio">
                <h2><?= htmlspecialchars(t('garaje.index.sin_vehiculos')) ?></h2>

                <p>
                    <?= htmlspecialchars(t('garaje.index.anadir_vehiculo')) ?>
                </p>

                <a href="<?= url('/garaje/nuevo') ?>" class="garaje-boton-enlace">
                    <?= htmlspecialchars(t('garaje.index.anadir_vehiculo')) ?>
                </a>
            </section>
        <?php else: ?>
            <section class="garaje-listado" aria-label="<?= htmlspecialchars(t('garaje.index.titulo')) ?>">
                <?php foreach ($vehiculos as $v): ?>
                    <article
                        class="garaje-tarjeta tarjeta-clicable"
                        data-url-tarjeta="<?= escapar(url('/garaje/ver?id=' . (int) $v['id'])) ?>"
                        tabindex="0"
                        role="link">
                        <?php
                        $imagen_vehiculo = $v['imagen'] ?? null;
                        $alt_imagen_vehiculo = t('garaje.index.alt_imagen_vehiculo') . ' ' . $v['marca'] . ' ' . $v['modelo'];
                        ?>

                        <div class="garaje-tarjeta__imagen">
                            <?php if (!empty($imagen_vehiculo)): ?>
                                <img
                                    src="<?= escapar(url_publica_segura('uploads/vehiculos/' . $imagen_vehiculo)) ?>"
                                    alt="<?= escapar($alt_imagen_vehiculo) ?>">
                            <?php else: ?>
                                <div class="garaje-tarjeta__imagen-placeholder">
                                    <span><?= htmlspecialchars(t('garaje.index.imagen_no_disponible')) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <header class="garaje-tarjeta__cabecera">
                            <h2>
                                <?= htmlspecialchars($v['marca']) ?>
                                <?= htmlspecialchars($v['modelo']) ?>
                            </h2>

                            <?php if (!empty($v['any'])): ?>
                                <p class="garaje-tarjeta__meta">
                                    <?= (int) $v['any'] ?>
                                </p>
                            <?php endif; ?>
                        </header>

                        <footer class="garaje-tarjeta__acciones">
                            <a href="<?= url('/garaje/editar?id=' . (int) $v['id']) ?>">
                                <?= htmlspecialchars(t('garaje.index.editar')) ?>
                            </a>

                            <a href="<?= url('/garaje/eliminar?id=' . (int) $v['id']) ?>">
                                <?= htmlspecialchars(t('garaje.index.eliminar')) ?>
                            </a>
                        </footer>
                    </article>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>