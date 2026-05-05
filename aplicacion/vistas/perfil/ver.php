<?php
    if (!isset($usuario) || !is_array($usuario)) {
        flash_set('error', t('perfil.error.cargar'));
        header('Location: ' . url('/comunidad'));
        exit;
    }
    
    $es_mi_perfil = isset($es_mi_perfil) ? (bool) $es_mi_perfil : false;
    
    $publicaciones = isset($publicaciones) && is_array($publicaciones)
        ? $publicaciones
        : [];

    $vehiculos = isset($vehiculos) && is_array($vehiculos)
        ? $vehiculos
        : [];
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('perfil.ver.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>

    <h1><?= htmlspecialchars(t('perfil.ver.titulo_de')) ?> @<?= htmlspecialchars($usuario['nombre']) ?></h1>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <section class="perfil-contenedor">
        <div class="perfil-cabecera">
            <div class="perfil-foto">
                <?php if (!empty($usuario['foto_perfil'])): ?>
                    <img
                        src="<?= escapar(url_publica_segura('uploads/perfiles/' . $usuario['foto_perfil'])) ?>"
                        alt="<?= htmlspecialchars(t('perfil.ver.alt_foto') . ' ' . $usuario['nombre']) ?>">
                <?php else: ?>
                    <div class="perfil-foto-vacia">
                        <?= htmlspecialchars(t('perfil.ver.sin_foto')) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="perfil-info">
                <h2>@<?= htmlspecialchars($usuario['nombre']) ?></h2>
            </div>
        </div>

        <?php if ($es_mi_perfil): ?>
            <div class="perfil-bloque">
                <h3><?= htmlspecialchars(t('perfil.ver.cambiar_foto')) ?></h3>

                <form action="<?= url('/perfil/foto') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_campo() ?>

                    <div>
                        <label for="foto_perfil"><?= htmlspecialchars(t('perfil.ver.nueva_foto')) ?>:</label>
                        <input
                            type="file"
                            name="foto_perfil"
                            id="foto_perfil"
                            accept="image/jpeg,image/png,image/webp"
                            required>
                    </div>

                    <button type="submit"><?= htmlspecialchars(t('perfil.ver.actualizar_foto')) ?></button>
                </form>
            </div>

            <div class="perfil-bloque">
                <h3><?= htmlspecialchars(t('perfil.ver.editar_datos')) ?></h3>

                <form action="<?= url('/perfil/actualizar') ?>" method="POST">
                    <?= csrf_campo() ?>

                    <div>
                        <label for="nombre"><?= htmlspecialchars(t('perfil.ver.nombre_usuario')) ?>:</label>
                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="<?= escapar($usuario['nombre']) ?>"
                            required>
                    </div>

                    <div>
                        <label for="email"><?= htmlspecialchars(t('perfil.ver.email')) ?>:</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="<?= escapar($usuario['email']) ?>"
                            required>
                    </div>

                    <button type="submit"><?= htmlspecialchars(t('perfil.ver.guardar_cambios')) ?></button>
                </form>
            </div>

            <div class="perfil-bloque">
                <h3><?= htmlspecialchars(t('perfil.ver.cambiar_password')) ?></h3>

                <form action="<?= url('/perfil/cambiar-password') ?>" method="POST">
                    <?= csrf_campo() ?>

                    <div>
                        <label for="password_actual"><?= htmlspecialchars(t('perfil.ver.password_actual')) ?>:</label>
                        <input
                            type="password"
                            name="password_actual"
                            id="password_actual"
                            required>
                    </div>

                    <div>
                        <label for="password_nueva"><?= htmlspecialchars(t('perfil.ver.password_nueva')) ?>:</label>
                        <input
                            type="password"
                            name="password_nueva"
                            id="password_nueva"
                            required>
                    </div>

                    <div>
                        <label for="password_nueva_repetida"><?= htmlspecialchars(t('perfil.ver.password_nueva_repetida')) ?>:</label>
                        <input
                            type="password"
                            name="password_nueva_repetida"
                            id="password_nueva_repetida"
                            required>
                    </div>

                    <button type="submit"><?= htmlspecialchars(t('perfil.ver.cambiar_password')) ?></button>
                </form>
            </div>
        <?php endif; ?>
    </section>

    <hr>

    <section class="perfil-bloque">
        <h3><?= htmlspecialchars(t('perfil.ver.garaje_publico')) ?></h3>

        <?php if (empty($vehiculos)): ?>
            <p><?= htmlspecialchars(t('perfil.ver.sin_vehiculos')) ?>.</p>
        <?php else: ?>
            <div class="perfil-garaje">
                <?php foreach ($vehiculos as $vehiculo): ?>
                    <article class="perfil-vehiculo">
                        <?php if (!empty($vehiculo['imagen'])): ?>
                            <img
                                src="<?= escapar(url_publica_segura('uploads/vehiculos/' . $vehiculo['imagen'])) ?>"
                                alt="<?= htmlspecialchars(t('perfil.ver.alt_vehiculo') . ' ' . $vehiculo['marca'] . ' ' . $vehiculo['modelo']) ?>">
                        <?php else: ?>
                            <div class="perfil-vehiculo-sin-imagen">
                                <?= htmlspecialchars(t('perfil.ver.sin_imagen')) ?>
                            </div>
                        <?php endif; ?>

                        <h4>
                            <?= htmlspecialchars($vehiculo['marca']) ?>
                            <?= htmlspecialchars($vehiculo['modelo']) ?>
                        </h4>

                        <p>
                            <?= htmlspecialchars($vehiculo['any']) ?>
                            <?php if (!empty($vehiculo['tipo_combustible'])): ?>
                                · <?= htmlspecialchars($vehiculo['tipo_combustible']) ?>
                            <?php endif; ?>
                        </p>

                        <a href="<?= url('/perfil/vehiculo?id=' . (int) $vehiculo['id']) ?>">
                            <?= htmlspecialchars(t('perfil.ver.ver_detalles')) ?>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <hr>

    <h2><?= htmlspecialchars(t('perfil.ver.publicaciones')) ?></h2>

    <?php if (count($publicaciones) === 0): ?>
        <p><?= htmlspecialchars(t('perfil.ver.sin_publicaciones')) ?>.</p>
    <?php else: ?>
        <?php foreach ($publicaciones as $publicacion): ?>
            <article>
                <p>
                    <?= formatear_fecha($publicacion['fecha_creacion']) ?>
                </p>

                <?php if (!empty($publicacion['imagen'])): ?>
                    <img
                        src="<?= escapar(url_publica_segura($publicacion['imagen'])) ?>"
                        alt="<?= htmlspecialchars(t('perfil.ver.alt_publicacion')) ?>"
                        style="max-width:400px;display:block;margin-bottom:10px;">
                <?php endif; ?>

                <p><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>

                <p>
                    <?php
                        $total_comentarios = (int) $publicacion['total_comentarios'];
                        $total_likes = (int) $publicacion['total_likes'];

                        $texto_comentarios = $total_comentarios === 1
                            ? t('perfil.ver.comentario_singular')
                            : t('perfil.ver.comentario_plural');

                        $texto_likes = $total_likes === 1
                            ? t('perfil.ver.like_singular')
                            : t('perfil.ver.like_plural');
                    ?>

                    <?= $total_comentarios ?> <?= htmlspecialchars($texto_comentarios) ?> ·
                    <?= $total_likes ?> <?= htmlspecialchars($texto_likes) ?>
                </p>

                <p>
                    <a href="<?= escapar(url('/comunidad/ver?id=' . (int) $publicacion['id'])) ?>">
                        <?= htmlspecialchars(t('perfil.ver.ver_publicacion')) ?>
                    </a>
                </p>

                <hr>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>