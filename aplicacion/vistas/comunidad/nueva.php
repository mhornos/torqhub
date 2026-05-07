<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('comunidad.form.nueva.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <main class="comunidad-formulario-contenedor">
        <header class="comunidad-formulario-cabecera">
            <h1><?= htmlspecialchars(t('comunidad.form.nueva.titulo')) ?></h1>
        </header>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <form action="<?= url('/comunidad/nueva') ?>" method="POST" enctype="multipart/form-data" class="comunidad-formulario-panel">
            <?= csrf_campo() ?>

            <div class="comunidad-formulario-campo">
                <label for="contenido"><?= htmlspecialchars(t('comunidad.form.contenido')) ?>:</label>

                <textarea
                    name="contenido"
                    id="contenido"
                    rows="8"
                    required></textarea>
            </div>

            <div class="comunidad-formulario-campo">
                <label for="imagen"><?= htmlspecialchars(t('comunidad.form.imagen_opcional')) ?>:</label>

                <input
                    type="file"
                    name="imagen"
                    id="imagen"
                    accept=".jpg,.jpeg,.png,.webp">
            </div>

            <div class="comunidad-formulario-acciones">
                <a href="<?= url('/comunidad') ?>" class="comunidad-boton-enlace">
                    <?= htmlspecialchars(t('comunidad.form.volver_comunidad')) ?>
                </a>

                <button type="submit">
                    <?= htmlspecialchars(t('comunidad.form.publicar')) ?>
                </button>
            </div>
        </form>
    </main>
</body>

</html>