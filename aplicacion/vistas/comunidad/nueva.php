<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Nueva publicación</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form action="<?= url('/comunidad/nueva') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_campo() ?>

        <div>
            <label for="contenido">Contenido</label>
            <textarea name="contenido" id="contenido" rows="8" required></textarea>
        </div>

        <div>
            <label for="imagen">Imagen opcional</label>
            <input type="file" name="imagen" id="imagen" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <button type="submit">Publicar</button>
    </form>

    <p>
        <a href="<?= url('/comunidad') ?>">Volver a comunidad</a>
    </p>
</body>
</html>