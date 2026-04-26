<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>perfil - torqhub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>

    <h1>Perfil de @<?= htmlspecialchars($usuario['nombre']) ?></h1>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <section>
        <?php if (!empty($usuario['foto_perfil'])): ?>
            <img 
                src="<?= url('/public/' . $usuario['foto_perfil']) ?>" 
                alt="Foto de perfil"
                style="width:120px;height:120px;object-fit:cover;border-radius:50%;"
            >
        <?php else: ?>
            <div style="width:120px;height:120px;border-radius:50%;background:#ddd;display:flex;align-items:center;justify-content:center;">
                sin foto
            </div>
        <?php endif; ?>

        <p><strong>@<?= htmlspecialchars($usuario['nombre']) ?></strong></p>

        <?php if ($es_mi_perfil): ?>
            <p>correo electrónico: <?= htmlspecialchars($usuario['email']) ?></p>
            <p>contraseña: ********</p>

            <p>
                <a href="#">editar perfil</a>
            </p>

            <p>
                <a href="#">cambiar contraseña</a>
            </p>
        <?php endif; ?>
    </section>

    <hr>

    <h2>Publicaciones</h2>

    <?php if (count($publicaciones) === 0): ?>
        <p>Este usuario todavía no tiene publicaciones.</p>
    <?php else: ?>
        <?php foreach ($publicaciones as $publicacion): ?>
            <article>
                <p>
                    <?= formatear_fecha($publicacion['fecha_creacion']) ?>
                </p>

                <?php if (!empty($publicacion['imagen'])): ?>
                    <img 
                        src="<?= url('/public/' . $publicacion['imagen']) ?>"
                        alt="Imagen publicación"
                        style="max-width:400px;display:block;margin-bottom:10px;"
                    >
                <?php endif; ?>

                <p><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>

                <p>
                    <?= (int) $publicacion['total_comentarios'] ?> comentarios ·
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