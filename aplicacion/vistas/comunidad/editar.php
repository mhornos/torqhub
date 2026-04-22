<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Editar publicación</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form action="<?= url('/comunidad/editar') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_campo() ?>

        <input type="hidden" name="id" value="<?= (int) $publicacion['id'] ?>">

        <div>
            <label for="contenido">Contenido: </label>
            <textarea name="contenido" id="contenido" rows="8" required><?= htmlspecialchars($publicacion['contenido']) ?></textarea>
        </div>

        <?php if (!empty($publicacion['imagen'])): ?>
            <div>
                <p>Imagen actual:</p>
                <img 
                    src="<?= url('/public/' . $publicacion['imagen']) ?>"
                    alt="Imagen actual de la publicación"
                    style="max-width: 400px; display:block; margin-bottom:10px;"
                >
            </div>
        <?php endif; ?>

        <div>
            <label for="imagen">Nueva imagen opcional</label>
            <input type="file" name="imagen" id="imagen" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <button type="submit">Guardar cambios</button>
    </form>

    <p>
        <a href="<?= url('/comunidad/ver?id=' . $publicacion['id']) ?>">Volver a la publicación</a>
    </p>
</body>
</html>