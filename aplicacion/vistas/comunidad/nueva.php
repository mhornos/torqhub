<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('comunidad.form.nueva.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1><?= htmlspecialchars(t('comunidad.form.nueva.titulo')) ?></h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form action="<?= url('/comunidad/nueva') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_campo() ?>

        <div>
            <label for="contenido"><?= htmlspecialchars(t('comunidad.form.contenido')) ?>:</label>
            <textarea name="contenido" id="contenido" rows="8" required></textarea>
        </div>

        <div>
            <label for="imagen"><?= htmlspecialchars(t('comunidad.form.imagen_opcional')) ?>:</label>
            <input type="file" name="imagen" id="imagen" accept=".jpg,.jpeg,.png,.webp">
        </div>

        <button type="submit"><?= htmlspecialchars(t('comunidad.form.publicar')) ?></button>
    </form>

    <p>
        <a href="<?= url('/comunidad') ?>"><?= htmlspecialchars(t('comunidad.form.volver_comunidad')) ?></a>
    </p>
</body>
</html>