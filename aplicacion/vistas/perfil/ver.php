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
    <main class="perfil-contenedor">
        <header class="perfil-cabecera">
            <div class="perfil-cabecera__foto">
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

            <div class="perfil-cabecera__info">
                <p class="perfil-cabecera__etiqueta">
                    <?= htmlspecialchars(t('perfil.ver.titulo_pagina')) ?>
                </p>

                <h1>@<?= htmlspecialchars($usuario['nombre']) ?></h1>

                <?php if ($es_mi_perfil): ?>
                    <p class="perfil-cabecera__texto">
                        <?= htmlspecialchars(t('perfil.ver.email')) ?>:
                        <?= htmlspecialchars($usuario['email']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="perfil-cabecera__acciones" aria-label="<?= htmlspecialchars(t('perfil.ver.opciones_perfil')) ?>">
                <button
                    type="button"
                    class="perfil-boton-control"
                    data-perfil-boton="publicaciones">
                    <?= htmlspecialchars(t('perfil.ver.boton_publicaciones')) ?>
                </button>

                <?php if ($es_mi_perfil): ?>
                    <button
                        type="button"
                        class="perfil-boton-control"
                        data-perfil-boton="configuracion">
                        <?= htmlspecialchars(t('perfil.ver.boton_configuracion')) ?>
                    </button>
                <?php endif; ?>

                <button
                    type="button"
                    class="perfil-boton-control"
                    data-perfil-boton="atras"
                    hidden>
                    <?= htmlspecialchars(t('perfil.ver.boton_atras')) ?>
                </button>
            </div>
        </header>

        <?php if ($m = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <?php if ($es_mi_perfil): ?>
            <section class="perfil-seccion perfil-seccion--configuracion" data-perfil-panel="configuracion" hidden>
                <header class="perfil-seccion__cabecera">
                    <h2><?= htmlspecialchars(t('perfil.ver.editar_datos')) ?></h2>
                </header>

                <div class="perfil-ajustes">
                    <article class="perfil-panel">
                        <h3><?= htmlspecialchars(t('perfil.ver.cambiar_foto')) ?></h3>

                        <form action="<?= url('/perfil/foto') ?>" method="POST" enctype="multipart/form-data" class="perfil-formulario">
                            <?= csrf_campo() ?>

                            <div class="perfil-formulario__campo">
                                <label for="foto_perfil"><?= htmlspecialchars(t('perfil.ver.nueva_foto')) ?>:</label>

                                <input
                                    type="file"
                                    name="foto_perfil"
                                    id="foto_perfil"
                                    accept="image/jpeg,image/png,image/webp"
                                    required>
                            </div>

                            <div class="perfil-formulario__acciones">
                                <button type="submit">
                                    <?= htmlspecialchars(t('perfil.ver.actualizar_foto')) ?>
                                </button>
                            </div>
                        </form>
                    </article>

                    <article class="perfil-panel">
                        <h3><?= htmlspecialchars(t('perfil.ver.editar_datos')) ?></h3>

                        <form action="<?= url('/perfil/actualizar') ?>" method="POST" class="perfil-formulario">
                            <?= csrf_campo() ?>

                            <div class="perfil-formulario__campo">
                                <label for="nombre"><?= htmlspecialchars(t('perfil.ver.nombre_usuario')) ?>:</label>

                                <input
                                    type="text"
                                    name="nombre"
                                    id="nombre"
                                    value="<?= escapar($usuario['nombre']) ?>"
                                    required>
                            </div>

                            <div class="perfil-formulario__campo">
                                <label for="email"><?= htmlspecialchars(t('perfil.ver.email')) ?>:</label>

                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="<?= escapar($usuario['email']) ?>"
                                    required>
                            </div>

                            <div class="perfil-formulario__acciones">
                                <button type="submit">
                                    <?= htmlspecialchars(t('perfil.ver.guardar_cambios')) ?>
                                </button>
                            </div>
                        </form>
                    </article>

                    <article class="perfil-panel perfil-panel--ancho-completo">
                        <h3><?= htmlspecialchars(t('perfil.ver.cambiar_password')) ?></h3>

                        <form action="<?= url('/perfil/cambiar-password') ?>" method="POST" class="perfil-formulario perfil-formulario--grid">
                            <?= csrf_campo() ?>

                            <div class="perfil-formulario__campo">
                                <label for="password_actual"><?= htmlspecialchars(t('perfil.ver.password_actual')) ?>:</label>

                                <input
                                    type="password"
                                    name="password_actual"
                                    id="password_actual"
                                    autocomplete="current-password"
                                    required>
                            </div>

                            <div class="perfil-formulario__campo">
                                <label for="password_nueva"><?= htmlspecialchars(t('perfil.ver.password_nueva')) ?>:</label>

                                <input
                                    type="password"
                                    name="password_nueva"
                                    id="password_nueva"
                                    autocomplete="new-password"
                                    required>
                            </div>

                            <div class="perfil-formulario__campo">
                                <label for="password_nueva_repetida"><?= htmlspecialchars(t('perfil.ver.password_nueva_repetida')) ?>:</label>

                                <input
                                    type="password"
                                    name="password_nueva_repetida"
                                    id="password_nueva_repetida"
                                    autocomplete="new-password"
                                    required>
                            </div>

                            <div class="perfil-formulario__acciones perfil-formulario__acciones--ancho-completo">
                                <button type="submit">
                                    <?= htmlspecialchars(t('perfil.ver.cambiar_password')) ?>
                                </button>
                            </div>
                        </form>
                    </article>
                </div>
            </section>
        <?php endif; ?>

        <section class="perfil-seccion perfil-seccion--garaje" data-perfil-panel="garaje">
            <header class="perfil-seccion__cabecera">
                <h2><?= htmlspecialchars(t('perfil.ver.garaje_publico')) ?></h2>
            </header>

            <?php if (empty($vehiculos)): ?>
                <div class="perfil-estado-vacio">
                    <p><?= htmlspecialchars(t('perfil.ver.sin_vehiculos')) ?>.</p>
                </div>
            <?php else: ?>
                <div class="perfil-garaje">
                    <?php foreach ($vehiculos as $vehiculo): ?>
                        <article class="perfil-vehiculo">
                            <div class="perfil-vehiculo__imagen">
                                <?php if (!empty($vehiculo['imagen'])): ?>
                                    <img
                                        src="<?= escapar(url_publica_segura('uploads/vehiculos/' . $vehiculo['imagen'])) ?>"
                                        alt="<?= htmlspecialchars(t('perfil.ver.alt_vehiculo') . ' ' . $vehiculo['marca'] . ' ' . $vehiculo['modelo']) ?>">
                                <?php else: ?>
                                    <div class="perfil-vehiculo__imagen-vacia">
                                        <?= htmlspecialchars(t('perfil.ver.sin_imagen')) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="perfil-vehiculo__contenido">
                                <h3>
                                    <?= htmlspecialchars($vehiculo['marca']) ?>
                                    <?= htmlspecialchars($vehiculo['modelo']) ?>
                                </h3>

                                <p>
                                    <?= htmlspecialchars($vehiculo['any']) ?>

                                    <?php if (!empty($vehiculo['tipo_combustible'])): ?>
                                        · <?= htmlspecialchars($vehiculo['tipo_combustible']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <footer class="perfil-vehiculo__acciones">
                                <a href="<?= escapar(url('/perfil/vehiculo?id=' . (int) $vehiculo['id'])) ?>" class="perfil-boton-enlace">
                                    <?= htmlspecialchars(t('perfil.ver.ver_detalles')) ?>
                                </a>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="perfil-seccion perfil-seccion--publicaciones" data-perfil-panel="publicaciones" hidden>
            <header class="perfil-seccion__cabecera">
                <h2><?= htmlspecialchars(t('perfil.ver.publicaciones')) ?></h2>
            </header>

            <?php if (count($publicaciones) === 0): ?>
                <div class="perfil-estado-vacio">
                    <p><?= htmlspecialchars(t('perfil.ver.sin_publicaciones')) ?>.</p>
                </div>
            <?php else: ?>
                <div class="perfil-publicaciones">
                    <?php foreach ($publicaciones as $publicacion): ?>
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

                        <article class="perfil-publicacion">
                            <header class="perfil-publicacion__cabecera">
                                <time datetime="<?= htmlspecialchars($publicacion['fecha_creacion']) ?>">
                                    <?= htmlspecialchars(formatear_fecha($publicacion['fecha_creacion'])) ?>
                                </time>
                            </header>

                            <?php if (!empty($publicacion['imagen'])): ?>
                                <div class="perfil-publicacion__imagen">
                                    <img
                                        src="<?= escapar(url_publica_segura($publicacion['imagen'])) ?>"
                                        alt="<?= htmlspecialchars(t('perfil.ver.alt_publicacion')) ?>">
                                </div>
                            <?php endif; ?>

                            <div class="perfil-publicacion__contenido">
                                <p><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>
                            </div>

                            <footer class="perfil-publicacion__pie">
                                <p>
                                    <?= $total_comentarios ?> <?= htmlspecialchars($texto_comentarios) ?> ·
                                    <?= $total_likes ?> <?= htmlspecialchars($texto_likes) ?>
                                </p>

                                <a href="<?= escapar(url('/comunidad/ver?id=' . (int) $publicacion['id'])) ?>" class="perfil-boton-enlace">
                                    <?= htmlspecialchars(t('perfil.ver.ver_publicacion')) ?>
                                </a>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>