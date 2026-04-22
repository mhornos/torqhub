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
                <p>
                    por: <?= htmlspecialchars($publicacion['autor_nombre']) ?>
                    · <?= formatear_fecha($publicacion['fecha_creacion']) ?>
                </p>
                
                <?php if (!empty($publicacion['imagen'])): ?>
                    <img 
                        src="<?= url('/public/' . $publicacion['imagen']) ?>"
                        alt="Imagen publicación"
                        style="max-width: 400px; display:block; margin-bottom:10px; margin-left:20px; box-shadow: 0 2px 5px rgba(0,0,0,0.5);"
                    >
                <?php endif; ?>

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
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>