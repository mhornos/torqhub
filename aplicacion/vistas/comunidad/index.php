<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('comunidad.index.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <main class="comunidad-contenedor">
        <header class="comunidad-cabecera">
            <div>
                <h1><?= htmlspecialchars(t('comunidad.index.titulo')) ?></h1>
            </div>

            <div class="comunidad-cabecera__acciones">
                <a href="<?= url('/comunidad/nueva') ?>" class="comunidad-boton-enlace">
                    <?= htmlspecialchars(t('comunidad.index.crear_publicacion')) ?>
                </a>
            </div>
        </header>

        <?php if ($m = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <section class="comunidad-filtros" aria-label="<?= htmlspecialchars(t('comunidad.index.buscar_publicaciones')) ?>">
            <form action="<?= url('/comunidad') ?>" method="GET" class="formulario-filtros-comunidad">
                <div class="campo-filtro-comunidad">
                    <label for="busqueda"><?= htmlspecialchars(t('comunidad.index.buscar_publicaciones')) ?></label>

                    <input
                        type="text"
                        id="busqueda"
                        name="busqueda"
                        value="<?= htmlspecialchars($busqueda ?? '') ?>"
                        placeholder="<?= htmlspecialchars(t('comunidad.index.placeholder_busqueda')) ?>">
                </div>

                <div class="campo-filtro-comunidad">
                    <label for="orden"><?= htmlspecialchars(t('comunidad.index.ordenar_por')) ?></label>

                    <select id="orden" name="orden">
                        <option value="recientes" <?= ($orden ?? '') === 'recientes' ? 'selected' : '' ?>>
                            <?= htmlspecialchars(t('comunidad.index.orden.recientes')) ?>
                        </option>

                        <option value="antiguas" <?= ($orden ?? '') === 'antiguas' ? 'selected' : '' ?>>
                            <?= htmlspecialchars(t('comunidad.index.orden.antiguas')) ?>
                        </option>

                        <option value="likes" <?= ($orden ?? '') === 'likes' ? 'selected' : '' ?>>
                            <?= htmlspecialchars(t('comunidad.index.orden.likes')) ?>
                        </option>

                        <option value="comentarios" <?= ($orden ?? '') === 'comentarios' ? 'selected' : '' ?>>
                            <?= htmlspecialchars(t('comunidad.index.orden.comentarios')) ?>
                        </option>
                    </select>
                </div>

                <div class="acciones-filtros-comunidad">
                    <a
                        href="<?= url('/comunidad') ?>"
                        class="enlace-limpiar-filtros comunidad-boton-enlace <?= empty($busqueda) && ($orden ?? 'recientes') === 'recientes' ? 'enlace-limpiar-filtros--oculto' : '' ?>">
                        <?= htmlspecialchars(t('comunidad.index.limpiar')) ?>
                    </a>
                </div>
            </form>
        </section>

        <?php require __DIR__ . '/_resultado_publicaciones.php'; ?>
    </main>
</body>

</html>