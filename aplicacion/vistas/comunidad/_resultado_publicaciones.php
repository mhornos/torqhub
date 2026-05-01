<?php
$total_publicaciones = isset($total_publicaciones) ? (int) $total_publicaciones : 0;

$texto_resultado = $total_publicaciones === 1
    ? t('comunidad.index.publicacion_encontrada')
    : t('comunidad.index.publicaciones_encontradas');
?>

<div id="resultado-publicaciones-comunidad">
    <section class="bloque-contador-publicaciones">
        <p class="contador-publicaciones">
            <?= $total_publicaciones ?>
            <?= htmlspecialchars($texto_resultado) ?>
        </p>
    </section>

    <?php require __DIR__ . '/_paginacion_publicaciones.php'; ?>

    <?php require __DIR__ . '/_listado_publicaciones.php'; ?>
</div>