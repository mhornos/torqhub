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

    <hr>

    <section>
        <h2>Comentarios</h2>

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