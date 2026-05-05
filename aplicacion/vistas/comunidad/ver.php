<?php
if (!isset($publicacion) || !is_array($publicacion)) {
    flash_set('error', t('comunidad.detalle.error.cargar'));
    header('Location: ' . url('/comunidad'));
    exit;
}

$comentarios = isset($comentarios) && is_array($comentarios)
    ? $comentarios
    : [];
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
    <h1><?= htmlspecialchars(t('comunidad.detalle.titulo')) ?></h1>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <article>
        <p>
            <?= htmlspecialchars(t('comunidad.index.por')) ?>: <a href="<?= escapar(url('/perfil?usuario=' . urlencode($publicacion['autor_nombre']))) ?>">
                @<?= htmlspecialchars($publicacion['autor_nombre']) ?>
            </a>
            · <?= formatear_fecha($publicacion['fecha_creacion']) ?>
        </p>

        <?php if (!empty($publicacion['imagen'])): ?>
            <img
                src="<?= escapar(url_publica_segura($publicacion['imagen'])) ?>"
                alt="<?= htmlspecialchars(t('comunidad.index.alt_imagen_publicacion')) ?>"
                style="max-width: 600px; display:block; margin-bottom:15px; margin-left:20px; box-shadow: 0 2px 5px rgba(0,0,0,0.5);">
        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>
    </article>

    <?php
    $usuario_id = (int) $_SESSION['usuario']['id'];

    $ya_dio_like = RepositorioLikesPublicaciones::usuario_ya_dio_like(
        (int) $publicacion['id'],
        $usuario_id
    );

    $total_likes = RepositorioLikesPublicaciones::contar_likes(
        (int) $publicacion['id']
    );
    ?>

    <p id="texto-total-likes-publicacion">
        <?php if ((int) $total_likes === 1): ?>
            1 <?= htmlspecialchars(t('comunidad.index.like_singular')) ?>
        <?php else: ?>
            <?= (int) $total_likes ?> <?= htmlspecialchars(t('comunidad.index.like_plural')) ?>
        <?php endif; ?>
    </p>

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

    <p id="mensaje-like-publicacion" style="display:none;"></p>

    <?php if ((int) $publicacion['usuario_id'] === (int) $_SESSION['usuario']['id']): ?>
        <p>
            <a href="<?= escapar(url('/comunidad/editar?id=' . (int) $publicacion['id'])) ?>">
                <?= htmlspecialchars(t('comunidad.index.editar_publicacion')) ?>
            </a>
        </p>

        <form
            action="<?= url('/comunidad/eliminar') ?>"
            method="POST"
            class="form-eliminar-publicacion"
            data-confirmacion="<?= htmlspecialchars(t('comunidad.index.confirmar_eliminar_publicacion')) ?>">
            <?= csrf_campo() ?>
            <input type="hidden" name="id" value="<?= (int) $publicacion['id'] ?>">
            <button type="submit"><?= htmlspecialchars(t('comunidad.index.eliminar_publicacion')) ?></button>
        </form>
    <?php endif; ?>

    <hr>

    <section>
        <h3><?= htmlspecialchars(t('comunidad.detalle.anadir_comentario')) ?></h3>

        <form action="<?= url('/comunidad/comentar') ?>" method="POST">
            <?= csrf_campo() ?>

            <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">

            <div>
                <h3><?= htmlspecialchars(t('comunidad.detalle.anadir_comentario')) ?></h3><br>
                <textarea name="contenido" id="contenido" rows="5" required></textarea>
            </div>
            <br>
            <button type="submit"><?= htmlspecialchars(t('comunidad.detalle.publicar_comentario')) ?></button>
        </form>
    </section>

    <section>
        <h3><?= htmlspecialchars(t('comunidad.detalle.comentarios')) ?></h3>

        <?php if (count($comentarios) === 0): ?>
            <p><?= htmlspecialchars(t('comunidad.detalle.sin_comentarios')) ?>.</p>
        <?php else: ?>
            <?php foreach ($comentarios as $comentario): ?>
                <article>
                    <p>
                        <strong><a href="<?= escapar(url('/perfil?usuario=' . urlencode($comentario['autor_nombre']))) ?>">
                                @<?= htmlspecialchars($comentario['autor_nombre']) ?>
                            </a></strong>
                        · <?= formatear_fecha($comentario['fecha_creacion']) ?>
                    </p>

                    <p><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>

                    <?php if ((int) $comentario['usuario_id'] === (int) $_SESSION['usuario']['id']): ?>
                        <p>
                            <a href="<?= escapar(url('/comunidad/editar-comentario?id=' . (int) $comentario['id'])) ?>">
                                <?= htmlspecialchars(t('comunidad.detalle.editar_comentario')) ?>
                            </a>
                        </p>

                        <form
                            action="<?= url('/comunidad/eliminar-comentario') ?>"
                            method="POST"
                            class="form-eliminar-comentario"
                            data-confirmacion="<?= htmlspecialchars(t('comunidad.detalle.confirmar_eliminar_comentario')) ?>">
                            <?= csrf_campo() ?>
                            <input type="hidden" name="id" value="<?= (int) $comentario['id'] ?>">
                            <button type="submit"><?= htmlspecialchars(t('comunidad.detalle.eliminar_comentario')) ?></button>
                        </form>
                    <?php endif; ?>

                    <p>
                        <button
                            type="button"
                            class="boton-responder-comentario"
                            data-comentario-id="<?= (int) $comentario['id'] ?>"
                            data-usuario="<?= htmlspecialchars($comentario['autor_nombre']) ?>">
                            <?= htmlspecialchars(t('comunidad.detalle.responder')) ?>
                        </button>
                    </p>

                    <div
                        id="formulario-respuesta-<?= (int) $comentario['id'] ?>"
                        style="display:none; margin: 10px 0 15px 20px;">
                        <form action="<?= url('/comunidad/responder-comentario') ?>" method="POST">
                            <?= csrf_campo() ?>

                            <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">
                            <input type="hidden" name="respuesta_a_id" value="<?= (int) $comentario['id'] ?>">

                            <div>
                                <label for="respuesta-<?= (int) $comentario['id'] ?>">
                                    <?= htmlspecialchars(t('comunidad.detalle.tu_respuesta')) ?>:
                                </label> <br>
                                <textarea
                                    name="contenido"
                                    id="respuesta-<?= (int) $comentario['id'] ?>"
                                    rows="4"
                                    required></textarea>
                            </div>
                            <br>
                            <button type="submit"><?= htmlspecialchars(t('comunidad.detalle.publicar_respuesta')) ?></button>
                        </form>
                    </div>

                    <?php $total_respuestas = (int) $comentario['total_respuestas']; ?>

                    <?php if ($total_respuestas > 0): ?>
                        <p>
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
                        </p>

                        <div
                            id="respuestas-comentario-<?= (int) $comentario['id'] ?>"
                            class="contenedor-respuestas-comentario"
                            data-cargadas="0"
                            style="display:none; margin: 10px 0 15px 20px;"></div>
                    <?php endif; ?>

                    <hr>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <p>
        <a href="<?= url('/comunidad') ?>"><?= htmlspecialchars(t('comunidad.detalle.volver')) ?></a>
    </p>

    <script src="<?= url('/public/js/comunidad/ver-publicacion.js') ?>"></script>
    <script src="<?= url('/public/js/comunidad/like-publicacion.js') ?>"></script>
</body>

</html>