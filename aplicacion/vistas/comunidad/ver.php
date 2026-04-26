<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Detalle de publicación</h1>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <article>
        <p>
            por: <a href="<?= url('/perfil?usuario=' . urlencode($publicacion['autor_nombre'])) ?>">
                    @<?= htmlspecialchars($publicacion['autor_nombre']) ?>
                </a>
            · <?= formatear_fecha($publicacion['fecha_creacion']) ?>
        </p>
        
        <?php if (!empty($publicacion['imagen'])): ?>
            <img 
                src="<?= url('/public/' . $publicacion['imagen']) ?>"
                alt="Imagen publicación"
                style="max-width: 600px; display:block; margin-bottom:15px; margin-left:20px; box-shadow: 0 2px 5px rgba(0,0,0,0.5);"
            >
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
        <?= (int) $total_likes ?> likes
    </p>

    <form
        action="<?= url('/comunidad/like-ajax') ?>"
        method="POST"
        id="formulario-like-publicacion"
        data-url="<?= url('/comunidad/like-ajax') ?>"
    >
        <?= csrf_campo() ?>

        <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">

        <button type="submit" id="boton-like-publicacion">
            <?= $ya_dio_like ? 'Quitar like' : 'Dar like' ?>
        </button>
    </form>

    <p id="mensaje-like-publicacion" style="display:none;"></p>
    
    <?php if ((int) $publicacion['usuario_id'] === (int) $_SESSION['usuario']['id']): ?>
        <p>
            <a href="<?= url('/comunidad/editar?id=' . $publicacion['id']) ?>">Editar publicación</a>
        </p>

        <form action="<?= url('/comunidad/eliminar') ?>" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar esta publicación?')">
            <?= csrf_campo() ?>
            <input type="hidden" name="id" value="<?= (int) $publicacion['id'] ?>">
            <button type="submit">Eliminar publicación</button>
        </form>
    <?php endif; ?>

    <hr>

    <section>
        <h3>Añadir comentario</h3>

        <form action="<?= url('/comunidad/comentar') ?>" method="POST">
            <?= csrf_campo() ?>

            <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">

            <div>
                <label for="contenido">Comentario:</label><br>
                <textarea name="contenido" id="contenido" rows="5" required></textarea>
            </div>
            <br>
            <button type="submit">Publicar comentario</button>
        </form>
    </section>

    <section>
        <h3>Comentarios</h3>

        <?php if (count($comentarios) === 0): ?>
            <p>Aún no hay comentarios en esta publicación.</p>
        <?php else: ?>
            <?php foreach ($comentarios as $comentario): ?>
                <article>
                    <p>
                        <strong><a href="<?= url('/perfil?usuario=' . urlencode($comentario['autor_nombre'])) ?>">
                                    @<?= htmlspecialchars($comentario['autor_nombre']) ?>
                                </a></strong>
                        · <?= formatear_fecha($comentario['fecha_creacion']) ?>
                    </p>

                    <p><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>

                    <?php if ((int) $comentario['usuario_id'] === (int) $_SESSION['usuario']['id']): ?>
                    <p>
                        <a href="<?= url('/comunidad/editar-comentario?id=' . $comentario['id']) ?>">Editar comentario</a>
                    </p>

                    <form action="<?= url('/comunidad/eliminar-comentario') ?>" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este comentario?')">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="id" value="<?= (int) $comentario['id'] ?>">
                        <button type="submit">Eliminar comentario</button>
                    </form>
                <?php endif; ?>
                
                <p>
                    <button
                        type="button"
                        class="boton-responder-comentario"
                        data-comentario-id="<?= (int) $comentario['id'] ?>"
                        data-usuario="<?= htmlspecialchars($comentario['autor_nombre']) ?>"
                    >
                        Responder
                    </button>
                </p>
                    
                <div
                    id="formulario-respuesta-<?= (int) $comentario['id'] ?>"
                    style="display:none; margin: 10px 0 15px 20px;"
                >
                    <form action="<?= url('/comunidad/responder-comentario') ?>" method="POST">
                        <?= csrf_campo() ?>
                    
                        <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">
                        <input type="hidden" name="respuesta_a_id" value="<?= (int) $comentario['id'] ?>">
                    
                        <div>
                            <label for="respuesta-<?= (int) $comentario['id'] ?>">Tu respuesta:</label> <br>
                            <textarea
                                name="contenido"
                                id="respuesta-<?= (int) $comentario['id'] ?>"
                                rows="4"
                                required
                            ></textarea>
                        </div>
                        <br>
                        <button type="submit">Publicar respuesta</button>
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
                            data-url="<?= url('/comunidad/respuestas-comentario') ?>"
                        >
                            <?php if ($total_respuestas === 1): ?>
                                Ver 1 respuesta
                            <?php else: ?>
                                Ver <?= $total_respuestas ?> respuestas
                            <?php endif; ?>
                        </button>
                    </p>
                            
                    <div
                        id="respuestas-comentario-<?= (int) $comentario['id'] ?>"
                        class="contenedor-respuestas-comentario"
                        data-cargadas="0"
                        style="display:none; margin: 10px 0 15px 20px;"
                    ></div>
                <?php endif; ?>
                        
                    <hr>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <p>
        <a href="<?= url('/comunidad') ?>">Volver a comunidad</a>
    </p>

    <script src="<?= url('/public/js/comunidad/ver-publicacion.js') ?>"></script>
    <script src="<?= url('/public/js/comunidad/like-publicacion.js') ?>"></script>
</body>
</html>