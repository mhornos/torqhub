<?php
$total_publicaciones = isset($total_publicaciones) ? (int) $total_publicaciones : 0;

$texto_resultado = $total_publicaciones === 1
    ? t('comunidad.index.publicacion_encontrada')
    : t('comunidad.index.publicaciones_encontradas');
?>

<section id="resultado-publicaciones-comunidad" class="comunidad-resultados">
    <header class="comunidad-resultados__cabecera">
        <p class="contador-publicaciones">
            <?= $total_publicaciones ?>
            <?= htmlspecialchars($texto_resultado) ?>
        </p>
    </header>

    <?php require __DIR__ . '/_paginacion_publicaciones.php'; ?>

    <?php require __DIR__ . '/_listado_publicaciones.php'; ?>
</section>