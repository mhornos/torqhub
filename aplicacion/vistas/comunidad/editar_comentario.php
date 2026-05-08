<?php
if (!isset($comentario) || !is_array($comentario)) {
    flash_set('error', t('comunidad.form.editar_comentario.error_cargar'));
    header('Location: ' . url('/comunidad'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('comunidad.form.editar_comentario.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <h1><?= htmlspecialchars(t('comunidad.form.editar_comentario.titulo')) ?></h1>

    <?php if ($m = flash_get('error')): ?>
        <p class="mensaje-error"><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('ok')): ?>
        <p class="mensaje-ok"><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form action="<?= url('/comunidad/editar-comentario') ?>" method="POST">
        <?= csrf_campo() ?>

        <input type="hidden" name="id" value="<?= (int) $comentario['id'] ?>">

        <div>
            <label for="contenido"><?= htmlspecialchars(t('comunidad.form.editar_comentario.comentario')) ?>:</label>
            <textarea name="contenido" id="contenido" rows="6" required><?= htmlspecialchars($comentario['contenido']) ?></textarea>
        </div>

        <button type="submit"><?= htmlspecialchars(t('comunidad.form.guardar_cambios')) ?></button>
    </form>

    <p>
        <a href="<?= url('/comunidad/ver?id=' . $comentario['publicacion_id']) ?>"><?= htmlspecialchars(t('comunidad.form.volver_publicacion')) ?></a>
    </p>
</body>

</html>