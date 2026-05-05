<?php
    if (!isset($publicacion) || !is_array($publicacion)) {
        flash_set('error', t('comunidad.form.editar.error_cargar'));
        header('Location: ' . url('/comunidad'));
        exit;
    }
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('comunidad.form.editar.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1><?= htmlspecialchars(t('comunidad.form.editar.titulo')) ?></h1>

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
            <label for="contenido"><?= htmlspecialchars(t('comunidad.form.contenido')) ?>:</label>
            <textarea name="contenido" id="contenido" rows="8" required><?= htmlspecialchars($publicacion['contenido']) ?></textarea>
        </div>

        <?php if (!empty($publicacion['imagen'])): ?>
            <div>
                <p><?= htmlspecialchars(t('comunidad.form.imagen_actual')) ?>:</p>
                <img 
                    src="<?= escapar(url_publica_segura($publicacion['imagen'])) ?>"
                    alt="<?= htmlspecialchars(t('comunidad.form.alt_imagen_actual')) ?>"
                    style="max-width: 400px; display:block; margin-bottom:10px;"
                >
            </div>
        <?php endif; ?>

        <div>
            <label for="imagen"><?= htmlspecialchars(t('comunidad.form.nueva_imagen_opcional')) ?></label>
            <input type="file" name="imagen" id="imagen" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <button type="submit"><?= htmlspecialchars(t('comunidad.form.guardar_cambios')) ?></button>
    </form>

    <p>
        <a href="<?= url('/comunidad/ver?id=' . $publicacion['id']) ?>"><?= htmlspecialchars(t('comunidad.form.volver_publicacion')) ?></a>
    </p>
</body>
</html>