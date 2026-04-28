<?php
$total_publicaciones = $total_publicaciones ?? 0;
?>

<div id="resultado-publicaciones-comunidad">
    <section class="bloque-contador-publicaciones">
        <p class="contador-publicaciones">
            <?= (int) $total_publicaciones ?>
            <?= (int) $total_publicaciones === 1 ? 'publicación encontrada' : 'publicaciones encontradas' ?>
        </p>
    </section>

    <?php require __DIR__ . '/_paginacion_publicaciones.php'; ?>

    <?php require __DIR__ . '/_listado_publicaciones.php'; ?>
</div>