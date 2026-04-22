<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Comunidad</h1>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <p>
        <a href="<?= url('/comunidad/nueva') ?>">Crear publicación</a>
    </p>
    
    <?php if (count($publicaciones) === 0): ?>
        <p>Aún no hay publicaciones en la comunidad.</p>
    <?php else: ?>
        <?php foreach ($publicaciones as $publicacion): ?>
            <article>
                <h2><?= htmlspecialchars($publicacion['titulo']) ?></h2>

                <p>
                    por <?= htmlspecialchars($publicacion['autor_nombre']) ?>
                    · <?= formatear_fecha($publicacion['fecha_creacion']) ?>
                </p>

                <p><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>

                <p>
                    <?php if ((int) $publicacion['total_comentarios'] === 1): ?>
                        1 comentario
                    <?php else: ?>
                        <?= (int) $publicacion['total_comentarios'] ?> comentarios
                    <?php endif; ?>
                </p>
                <p>
                    <?= (int) $publicacion['total_likes'] ?> likes
                </p>

                <p>
                    <a href="<?= url('/comunidad/ver?id=' . $publicacion['id']) ?>">Ver publicación</a>
                </p>

                <hr>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>