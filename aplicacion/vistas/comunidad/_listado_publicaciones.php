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

            <?php
            $url_foto_autor = !empty($publicacion['autor_foto_perfil'])
                ? url_publica_segura('uploads/perfiles/' . $publicacion['autor_foto_perfil'])
                : null;
            ?>

            <article
                class="comunidad-publicacion tarjeta-clicable"
                data-url-tarjeta="<?= escapar(url('/comunidad/ver?id=' . (int) $publicacion['id'])) ?>"
                tabindex="0"
                role="link">
                <header class="comunidad-publicacion__cabecera">
                    <p class="comunidad-autor">
                        <a
                            class="comunidad-autor__enlace"
                            href="<?= escapar(url('/perfil?usuario=' . urlencode($publicacion['autor_nombre']))) ?>">

                            <?php if ($url_foto_autor): ?>
                                <img
                                    class="comunidad-autor__avatar"
                                    src="<?= escapar($url_foto_autor) ?>"
                                    alt="<?= htmlspecialchars($publicacion['autor_nombre']) ?>">
                            <?php else: ?>
                                <span class="comunidad-autor__avatar comunidad-autor__avatar--vacio">
                                    <?= htmlspecialchars(strtoupper(substr($publicacion['autor_nombre'], 0, 1))) ?>
                                </span>
                            <?php endif; ?>

                            <span class="comunidad-autor__nombre">
                                @<?= htmlspecialchars($publicacion['autor_nombre']) ?>
                            </span>
                        </a>

                        <span class="comunidad-autor__separador">·</span>
                        <span class="comunidad-autor__fecha"><?= formatear_fecha($publicacion['fecha_creacion']) ?></span>
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
                        <p>·</p>
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

                                <button type="submit" class="boton--peligro boton-eliminar-publicacion">
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