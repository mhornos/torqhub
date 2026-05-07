<?php
if (!isset($publicacion) || !is_array($publicacion)) {
    flash_set('error', t('comunidad.detalle.error.cargar'));
    header('Location: ' . url('/comunidad'));
    exit;
}

$comentarios = isset($comentarios) && is_array($comentarios)
    ? $comentarios
    : [];

$usuario_id = (int) $_SESSION['usuario']['id'];

$ya_dio_like = RepositorioLikesPublicaciones::usuario_ya_dio_like(
    (int) $publicacion['id'],
    $usuario_id
);

$total_likes = RepositorioLikesPublicaciones::contar_likes(
    (int) $publicacion['id']
);
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('comunidad.detalle.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <main class="comunidad-contenedor comunidad-contenedor--detalle">
        <header class="comunidad-cabecera">
            <div>
                <h1><?= htmlspecialchars(t('comunidad.detalle.titulo')) ?></h1>
            </div>

            <div class="comunidad-cabecera__acciones">
                <a href="<?= url('/comunidad') ?>" class="comunidad-boton-enlace">
                    <?= htmlspecialchars(t('comunidad.detalle.volver')) ?>
                </a>
            </div>
        </header>

        <?php if ($m = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <article class="comunidad-detalle-publicacion">
            <header class="comunidad-detalle-publicacion__cabecera">
                <p class="comunidad-publicacion__meta">
                    <?= htmlspecialchars(t('comunidad.index.por')) ?>:
                    <a href="<?= escapar(url('/perfil?usuario=' . urlencode($publicacion['autor_nombre']))) ?>">
                        @<?= htmlspecialchars($publicacion['autor_nombre']) ?>
                    </a>
                    <span>·</span>
                    <time datetime="<?= htmlspecialchars($publicacion['fecha_creacion']) ?>">
                        <?= htmlspecialchars(formatear_fecha($publicacion['fecha_creacion'])) ?>
                    </time>
                </p>
            </header>

            <?php if (!empty($publicacion['imagen'])): ?>
                <div class="comunidad-detalle-publicacion__imagen">
                    <img
                        src="<?= escapar(url_publica_segura($publicacion['imagen'])) ?>"
                        alt="<?= htmlspecialchars(t('comunidad.index.alt_imagen_publicacion')) ?>">
                </div>
            <?php endif; ?>

            <div class="comunidad-detalle-publicacion__contenido">
                <p><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>
            </div>

            <footer class="comunidad-detalle-publicacion__pie">
                <div class="comunidad-detalle-publicacion__estadisticas">
                    <p id="texto-total-likes-publicacion">
                        <?php if ((int) $total_likes === 1): ?>
                            1 <?= htmlspecialchars(t('comunidad.index.like_singular')) ?>
                        <?php else: ?>
                            <?= (int) $total_likes ?> <?= htmlspecialchars(t('comunidad.index.like_plural')) ?>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="comunidad-detalle-publicacion__acciones">
                    <form
                        action="<?= url('/comunidad/like-ajax') ?>"
                        method="POST"
                        id="formulario-like-publicacion"
                        data-url="<?= escapar(url('/comunidad/like-ajax')) ?>"
                        data-like-singular="<?= htmlspecialchars(t('comunidad.index.like_singular')) ?>"
                        data-like-plural="<?= htmlspecialchars(t('comunidad.index.like_plural')) ?>"
                        data-error-like="<?= htmlspecialchars(t('comunidad.error.like_actualizar')) ?>">
                        <?= csrf_campo() ?>

                        <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">

                        <button type="submit" id="boton-like-publicacion">
                            <?= htmlspecialchars($ya_dio_like ? t('comunidad.index.quitar_like') : t('comunidad.index.dar_like')) ?>
                        </button>
                    </form>

                    <?php if ((int) $publicacion['usuario_id'] === $usuario_id): ?>
                        <a href="<?= escapar(url('/comunidad/editar?id=' . (int) $publicacion['id'])) ?>" class="comunidad-boton-enlace">
                            <?= htmlspecialchars(t('comunidad.index.editar_publicacion')) ?>
                        </a>

                        <form
                            action="<?= url('/comunidad/eliminar') ?>"
                            method="POST"
                            class="form-eliminar-publicacion"
                            data-confirmacion="<?= htmlspecialchars(t('comunidad.index.confirmar_eliminar_publicacion')) ?>">
                            <?= csrf_campo() ?>

                            <input type="hidden" name="id" value="<?= (int) $publicacion['id'] ?>">

                            <button type="submit">
                                <?= htmlspecialchars(t('comunidad.index.eliminar_publicacion')) ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <p id="mensaje-like-publicacion" class="comunidad-mensaje-like"></p>
            </footer>
        </article>

        <section class="comunidad-comentario-formulario">
            <header class="comunidad-seccion-cabecera">
                <h2><?= htmlspecialchars(t('comunidad.detalle.anadir_comentario')) ?></h2>
            </header>

            <form action="<?= url('/comunidad/comentar') ?>" method="POST" class="comunidad-formulario-panel">
                <?= csrf_campo() ?>

                <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">

                <div class="comunidad-formulario-campo">
                    <label for="contenido"><?= htmlspecialchars(t('comunidad.detalle.anadir_comentario')) ?></label>

                    <textarea
                        name="contenido"
                        id="contenido"
                        rows="5"
                        required></textarea>
                </div>

                <div class="comunidad-formulario-acciones">
                    <button type="submit">
                        <?= htmlspecialchars(t('comunidad.detalle.publicar_comentario')) ?>
                    </button>
                </div>
            </form>
        </section>

        <section class="comunidad-comentarios">
            <header class="comunidad-seccion-cabecera">
                <h2><?= htmlspecialchars(t('comunidad.detalle.comentarios')) ?></h2>
            </header>

            <?php if (count($comentarios) === 0): ?>
                <div class="comunidad-estado-vacio">
                    <p><?= htmlspecialchars(t('comunidad.detalle.sin_comentarios')) ?>.</p>
                </div>
            <?php else: ?>
                <div class="comunidad-comentarios__listado">
                    <?php foreach ($comentarios as $comentario): ?>
                        <article class="comunidad-comentario">
                            <header class="comunidad-comentario__cabecera">
                                <p class="comunidad-publicacion__meta">
                                    <strong>
                                        <a href="<?= escapar(url('/perfil?usuario=' . urlencode($comentario['autor_nombre']))) ?>">
                                            @<?= htmlspecialchars($comentario['autor_nombre']) ?>
                                        </a>
                                    </strong>
                                    <span>·</span>
                                    <time datetime="<?= htmlspecialchars($comentario['fecha_creacion']) ?>">
                                        <?= htmlspecialchars(formatear_fecha($comentario['fecha_creacion'])) ?>
                                    </time>
                                </p>
                            </header>

                            <div class="comunidad-comentario__contenido">
                                <p><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
                            </div>

                            <footer class="comunidad-comentario__acciones">
                                <?php if ((int) $comentario['usuario_id'] === $usuario_id): ?>
                                    <a href="<?= escapar(url('/comunidad/editar-comentario?id=' . (int) $comentario['id'])) ?>" class="comunidad-boton-enlace">
                                        <?= htmlspecialchars(t('comunidad.detalle.editar_comentario')) ?>
                                    </a>

                                    <form
                                        action="<?= url('/comunidad/eliminar-comentario') ?>"
                                        method="POST"
                                        class="form-eliminar-comentario"
                                        data-confirmacion="<?= htmlspecialchars(t('comunidad.detalle.confirmar_eliminar_comentario')) ?>">
                                        <?= csrf_campo() ?>

                                        <input type="hidden" name="id" value="<?= (int) $comentario['id'] ?>">

                                        <button type="submit">
                                            <?= htmlspecialchars(t('comunidad.detalle.eliminar_comentario')) ?>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <button
                                    type="button"
                                    class="boton-responder-comentario"
                                    data-comentario-id="<?= (int) $comentario['id'] ?>"
                                    data-usuario="<?= htmlspecialchars($comentario['autor_nombre']) ?>">
                                    <?= htmlspecialchars(t('comunidad.detalle.responder')) ?>
                                </button>
                            </footer>

                            <div
                                id="formulario-respuesta-<?= (int) $comentario['id'] ?>"
                                class="comunidad-respuesta-formulario">
                                <form action="<?= url('/comunidad/responder-comentario') ?>" method="POST" class="comunidad-formulario-panel comunidad-formulario-panel--respuesta">
                                    <?= csrf_campo() ?>

                                    <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">
                                    <input type="hidden" name="respuesta_a_id" value="<?= (int) $comentario['id'] ?>">

                                    <div class="comunidad-formulario-campo">
                                        <label for="respuesta-<?= (int) $comentario['id'] ?>">
                                            <?= htmlspecialchars(t('comunidad.detalle.tu_respuesta')) ?>:
                                        </label>

                                        <textarea
                                            name="contenido"
                                            id="respuesta-<?= (int) $comentario['id'] ?>"
                                            rows="4"
                                            required></textarea>
                                    </div>

                                    <div class="comunidad-formulario-acciones">
                                        <button type="submit">
                                            <?= htmlspecialchars(t('comunidad.detalle.publicar_respuesta')) ?>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <?php $total_respuestas = (int) $comentario['total_respuestas']; ?>

                            <?php if ($total_respuestas > 0): ?>
                                <div class="comunidad-respuestas">
                                    <button
                                        type="button"
                                        class="boton-toggle-respuestas"
                                        data-publicacion-id="<?= (int) $publicacion['id'] ?>"
                                        data-comentario-id="<?= (int) $comentario['id'] ?>"
                                        data-total-respuestas="<?= $total_respuestas ?>"
                                        data-url="<?= escapar(url('/comunidad/respuestas-comentario')) ?>"
                                        data-url-perfil="<?= escapar(url('/perfil')) ?>"
                                        data-texto-ver-una="<?= htmlspecialchars(t('comunidad.detalle.ver_una_respuesta')) ?>"
                                        data-texto-ver-varias="<?= htmlspecialchars(t('comunidad.detalle.ver_respuestas')) ?>"
                                        data-texto-ocultar="<?= htmlspecialchars(t('comunidad.detalle.ocultar_respuestas')) ?>"
                                        data-texto-cargando="<?= htmlspecialchars(t('comunidad.detalle.cargando_respuestas')) ?>"
                                        data-texto-error="<?= htmlspecialchars(t('comunidad.detalle.error_cargar_respuestas')) ?>"
                                        data-texto-sin-respuestas="<?= htmlspecialchars(t('comunidad.detalle.sin_respuestas')) ?>">

                                        <?php if ($total_respuestas === 1): ?>
                                            <?= htmlspecialchars(t('comunidad.detalle.ver_una_respuesta')) ?>
                                        <?php else: ?>
                                            <?= htmlspecialchars(str_replace('{total}', (string) $total_respuestas, t('comunidad.detalle.ver_respuestas'))) ?>
                                        <?php endif; ?>
                                    </button>

                                    <div
                                        id="respuestas-comentario-<?= (int) $comentario['id'] ?>"
                                        class="contenedor-respuestas-comentario"
                                        data-cargadas="0"></div>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>