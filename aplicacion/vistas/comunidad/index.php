<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Comunidad</h1>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <p>
        <a href="<?= url('/comunidad/nueva') ?>">Crear publicación</a>
    </p>
    
    <section>
        <form action="<?= url('/comunidad') ?>" method="GET" class="formulario-filtros-comunidad">
            <div class="campo-filtro-comunidad">
                <label for="busqueda">Buscar publicaciones</label>
                <input
                    type="text"
                    id="busqueda"
                    name="busqueda"
                    value="<?= htmlspecialchars($busqueda ?? '') ?>"
                    placeholder="Contenido o autor"
                >
            </div>

            <div class="campo-filtro-comunidad">
                <label for="orden">Ordenar por</label>
                <select id="orden" name="orden">
                    <option value="recientes" <?= ($orden ?? '') === 'recientes' ? 'selected' : '' ?>>Más recientes</option>
                    <option value="antiguas" <?= ($orden ?? '') === 'antiguas' ? 'selected' : '' ?>>Más antiguas</option>
                    <option value="likes" <?= ($orden ?? '') === 'likes' ? 'selected' : '' ?>>Más likes</option>
                    <option value="comentarios" <?= ($orden ?? '') === 'comentarios' ? 'selected' : '' ?>>Más comentarios</option>
                </select>
            </div>

            <div class="acciones-filtros-comunidad">
                <button type="submit">Aplicar filtros</button>

                <?php if (!empty($busqueda) || ($orden ?? 'recientes') !== 'recientes'): ?>
                    <a href="<?= url('/comunidad') ?>" class="enlace-limpiar-filtros">Limpiar</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <?php require __DIR__ . '/_resultado_publicaciones.php'; ?>

    <script src="<?= url('/public/js/comunidad/listado-publicaciones.js') ?>"></script>
    <script src="<?= url('/public/js/comunidad/filtros-publicaciones.js') ?>"></script>
</body>
</html>