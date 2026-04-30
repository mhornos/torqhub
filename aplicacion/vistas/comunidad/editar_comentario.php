<?php
    if (!isset($comentario) || !is_array($comentario)) {
        flash_set('error', 'No se ha podido cargar el comentario');
        header('Location: ' . url('/comunidad'));
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Editar comentario</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form action="<?= url('/comunidad/editar-comentario') ?>" method="POST">
        <?= csrf_campo() ?>

        <input type="hidden" name="id" value="<?= (int) $comentario['id'] ?>">

        <div>
            <label for="contenido">Comentario</label>
            <textarea name="contenido" id="contenido" rows="6" required><?= htmlspecialchars($comentario['contenido']) ?></textarea>
        </div>

        <button type="submit">Guardar cambios</button>
    </form>

    <p>
        <a href="<?= url('/comunidad/ver?id=' . $comentario['publicacion_id']) ?>">Volver a la publicación</a>
    </p>
</body>
</html>