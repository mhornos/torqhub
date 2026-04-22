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
        <h2><?= htmlspecialchars($publicacion['titulo']) ?></h2>

        <p>
            por <?= htmlspecialchars($publicacion['autor_nombre']) ?>
            · <?= formatear_fecha($publicacion['fecha_creacion']) ?>
        </p>

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
    
    <form action="<?= url('/comunidad/like') ?>" method="POST">
        <?= csrf_campo() ?>
        
        <input type="hidden" name="publicacion_id" value="<?= (int) $publicacion['id'] ?>">
        
        <button type="submit">
            <?= $ya_dio_like ? 'Quitar like' : 'Dar like' ?>
        </button>
    </form>
        
    <p>
        <?= $total_likes ?> likes
    </p>

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
                        <strong><?= htmlspecialchars($comentario['autor_nombre']) ?></strong>
                        · <?= formatear_fecha($comentario['fecha_creacion']) ?>
                    </p>

                    <p><?= nl2br(htmlspecialchars($comentario['contenido'])) ?></p>
                    <hr>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <p>
        <a href="<?= url('/comunidad') ?>">Volver a comunidad</a>
    </p>
</body>
</html>