<?php
$publicaciones = $publicaciones ?? [];
?>

<div id="contenedor-publicaciones-comunidad">
    <?php if (count($publicaciones) === 0): ?>
        <p><?= htmlspecialchars(t('comunidad.index.sin_publicaciones')) ?>.</p>
    <?php else: ?>
        <?php foreach ($publicaciones as $publicacion): ?>
            <article>
                <p>
                    <?= htmlspecialchars(t('comunidad.index.por')) ?>: <a href="<?= url('/perfil?usuario=' . urlencode($publicacion['autor_nombre'])) ?>">
                        @<?= htmlspecialchars($publicacion['autor_nombre']) ?>
                    </a>
                    · <?= formatear_fecha($publicacion['fecha_creacion']) ?>
                </p>

                <?php if (!empty($publicacion['imagen'])): ?>
                    <img
                        src="<?= url('/public/' . $publicacion['imagen']) ?>"
                        alt="<?= htmlspecialchars(t('comunidad.index.alt_imagen_publicacion')) ?>"
                        style="max-width: 400px; display:block; margin-bottom:10px; margin-left:20px; box-shadow: 0 2px 5px rgba(0,0,0,0.5);">
                <?php endif; ?>

                <p><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>

                <p>
                    <?php if ((int) $publicacion['total_comentarios'] === 1): ?>
                        1 <?= htmlspecialchars(t('comunidad.index.comentario_singular')) ?>
                    <?php else: ?>
                        <?= (int) $publicacion['total_comentarios'] ?> <?= htmlspecialchars(t('comunidad.index.comentario_plural')) ?>
                    <?php endif; ?>
                </p>

                <?php
                $usuario_id_actual = (int) $_SESSION['usuario']['id'];

                $ya_dio_like = RepositorioLikesPublicaciones::usuario_ya_dio_like(
                    (int) $publicacion['id'],
                    $usuario_id_actual
                );
                ?>

                <p
                    class="texto-total-likes-publicacion-listado"
                    data-publicacion-id="<?= (int) $publicacion['id'] ?>">
                    <?php if ((int) $publicacion['total_likes'] === 1): ?>
                        1 <?= htmlspecialchars(t('comunidad.index.like_singular')) ?>
                    <?php else: ?>
                        <?= (int) $publicacion['total_likes'] ?> <?= htmlspecialchars(t('comunidad.index.like_plural')) ?>
                    <?php endif; ?>
                </p>

                <form
                    action="<?= url('/comunidad/like-ajax') ?>"
                    method="POST"
                    class="formulario-like-publicacion-listado"
                    data-url="<?= url('/comunidad/like-ajax') ?>"
                    data-like-singular="<?= htmlspecialchars(t('comunidad.index.like_singular')) ?>"
                    data-like-plural="<?= htmlspecialchars(t('comunidad.index.like_plural')) ?>"
                    data-error-like="<?= htmlspecialchars(t('comunidad.error.like_actualizar')) ?>"
                    <?= csrf_campo() ?>

                    <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">

                    <button
                        type="submit"
                        class="boton-like-publicacion-listado">
                        <?= htmlspecialchars($ya_dio_like ? t('comunidad.index.quitar_like') : t('comunidad.index.dar_like')) ?>
                    </button>
                </form>

                <p
                    class="mensaje-like-publicacion-listado"
                    data-publicacion-id="<?= (int) $publicacion['id'] ?>"
                    style="display:none;"></p>

                <p>
                    <a href="<?= url('/comunidad/ver?id=' . $publicacion['id']) ?>">
                        <?= htmlspecialchars(t('comunidad.index.ver_publicacion')) ?>
                    </a>
                </p>

                <?php if ((int) $publicacion['usuario_id'] === (int) $_SESSION['usuario']['id']): ?>
                    <p>
                        <a href="<?= url('/comunidad/editar?id=' . $publicacion['id']) ?>">
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
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>