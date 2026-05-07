<?php
$publicaciones = $publicaciones ?? [];
?>

<div id="contenedor-publicaciones-comunidad" class="comunidad-listado">
    <?php if (count($publicaciones) === 0): ?>
        <div class="comunidad-estado-vacio">
            <p><?= htmlspecialchars(t('comunidad.index.sin_publicaciones')) ?>.</p>
        </div>
    <?php else: ?>
        <?php foreach ($publicaciones as $publicacion): ?>
            <?php
            $usuario_id_actual = (int) $_SESSION['usuario']['id'];

            $ya_dio_like = RepositorioLikesPublicaciones::usuario_ya_dio_like(
                (int) $publicacion['id'],
                $usuario_id_actual
            );
            ?>

            <article class="comunidad-publicacion">
                <header class="comunidad-publicacion__cabecera">
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
                    <div class="comunidad-publicacion__imagen">
                        <img
                            src="<?= escapar(url_publica_segura($publicacion['imagen'])) ?>"
                            alt="<?= htmlspecialchars(t('comunidad.index.alt_imagen_publicacion')) ?>">
                    </div>
                <?php endif; ?>

                <div class="comunidad-publicacion__contenido">
                    <p><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>
                </div>

                <footer class="comunidad-publicacion__pie">
                    <div class="comunidad-publicacion__estadisticas">
                        <p>
                            <?php if ((int) $publicacion['total_comentarios'] === 1): ?>
                                1 <?= htmlspecialchars(t('comunidad.index.comentario_singular')) ?>
                            <?php else: ?>
                                <?= (int) $publicacion['total_comentarios'] ?> <?= htmlspecialchars(t('comunidad.index.comentario_plural')) ?>
                            <?php endif; ?>
                        </p>

                        <p
                            class="texto-total-likes-publicacion-listado"
                            data-publicacion-id="<?= (int) $publicacion['id'] ?>">
                            <?php if ((int) $publicacion['total_likes'] === 1): ?>
                                1 <?= htmlspecialchars(t('comunidad.index.like_singular')) ?>
                            <?php else: ?>
                                <?= (int) $publicacion['total_likes'] ?> <?= htmlspecialchars(t('comunidad.index.like_plural')) ?>
                            <?php endif; ?>
                        </p>
                    </div>

                    <div class="comunidad-publicacion__acciones">
                        <form
                            action="<?= url('/comunidad/like-ajax') ?>"
                            method="POST"
                            class="formulario-like-publicacion-listado"
                            data-url="<?= escapar(url('/comunidad/like-ajax')) ?>"
                            data-like-singular="<?= htmlspecialchars(t('comunidad.index.like_singular')) ?>"
                            data-like-plural="<?= htmlspecialchars(t('comunidad.index.like_plural')) ?>"
                            data-error-like="<?= htmlspecialchars(t('comunidad.error.like_actualizar')) ?>">
                            <?= csrf_campo() ?>

                            <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">

                            <button
                                type="submit"
                                class="boton-like-publicacion-listado">
                                <?= htmlspecialchars($ya_dio_like ? t('comunidad.index.quitar_like') : t('comunidad.index.dar_like')) ?>
                            </button>
                        </form>

                        <a
                            href="<?= escapar(url('/comunidad/ver?id=' . (int) $publicacion['id'])) ?>"
                            class="boton-enlace-publicacion">
                            <?= htmlspecialchars(t('comunidad.index.ver_publicacion')) ?>
                        </a>

                        <?php if ((int) $publicacion['usuario_id'] === (int) $_SESSION['usuario']['id']): ?>
                            <a
                                href="<?= escapar(url('/comunidad/editar?id=' . (int) $publicacion['id'])) ?>"
                                class="boton-enlace-publicacion">
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

                    <p
                        class="mensaje-like-publicacion-listado"
                        data-publicacion-id="<?= (int) $publicacion['id'] ?>"
                        style="display:none;"></p>
                </footer>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>