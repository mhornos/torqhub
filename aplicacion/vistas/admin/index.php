<!DOCTYPE html>
<html lang="<?= escapar(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar(t('admin.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= escapar(url('/public/css/estilos.css')) ?>">
</head>

<body>
    <main class="admin-contenedor">
        <header class="admin-cabecera">
            <div class="admin-cabecera__texto">
                <h1><?= escapar(t('admin.titulo')) ?></h1>

                <p>
                    <?= escapar(t('admin.descripcion')) ?>
                </p>
            </div>
        </header>

        <section class="admin-tarjetas" aria-label="<?= escapar(t('admin.secciones')) ?>">
            <a href="<?= escapar(url('/admin/usuarios')) ?>" class="admin-tarjeta">
                <span class="admin-tarjeta__etiqueta">
                    <?= escapar(t('admin.tarjeta.usuarios.etiqueta')) ?>
                </span>

                <h2><?= escapar(t('admin.tarjeta.usuarios.titulo')) ?></h2>

                <p>
                    <?= escapar(t('admin.tarjeta.usuarios.descripcion')) ?>
                </p>
            </a>

            <a href="<?= escapar(url('/admin/publicaciones')) ?>" class="admin-tarjeta">
                <span class="admin-tarjeta__etiqueta">
                    <?= escapar(t('admin.tarjeta.publicaciones.etiqueta')) ?>
                </span>

                <h2><?= escapar(t('admin.tarjeta.publicaciones.titulo')) ?></h2>

                <p>
                    <?= escapar(t('admin.tarjeta.publicaciones.descripcion')) ?>
                </p>
            </a>

            <a href="<?= escapar(url('/admin/ia')) ?>" class="admin-tarjeta">
                <span class="admin-tarjeta__etiqueta">
                    <?= escapar(t('admin.tarjeta.ia.etiqueta')) ?>
                </span>

                <h2><?= escapar(t('admin.tarjeta.ia.titulo')) ?></h2>

                <p>
                    <?= escapar(t('admin.tarjeta.ia.descripcion')) ?>
                </p>
            </a>
        </section>
    </main>
</body>

</html>