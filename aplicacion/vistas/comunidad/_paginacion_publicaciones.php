<?php
$total_paginas = isset($total_paginas) ? (int) $total_paginas : 1;
$pagina_actual = isset($pagina_actual) ? (int) $pagina_actual : 1;
$busqueda = $busqueda ?? '';
$orden = $orden ?? 'recientes';

if ($total_paginas < 1) {
    $total_paginas = 1;
}

if ($pagina_actual < 1) {
    $pagina_actual = 1;
}

$texto_estado_paginacion = str_replace(
    ['{actual}', '{total}'],
    [$pagina_actual, $total_paginas],
    t('comunidad.index.pagina_de')
);
?>

<div id="contenedor-paginacion-comunidad">
    <?php if ($total_paginas > 1): ?>
        <div class="paginacion-comunidad" aria-label="<?= htmlspecialchars(t('comunidad.index.paginacion_aria')) ?>">
            <p class="estado-paginacion">
                <?= htmlspecialchars($texto_estado_paginacion) ?>
            </p>

            <div class="enlaces-paginacion">
                <?php if ($pagina_actual > 1): ?>
                    <a href="<?= url('/comunidad?' . http_build_query([
                                    'busqueda' => $busqueda,
                                    'orden' => $orden,
                                    'pagina' => $pagina_actual - 1,
                                ])) ?>">
                        <?= htmlspecialchars(t('comunidad.index.anterior')) ?>
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <?php if ($i === $pagina_actual): ?>
                        <span class="pagina-activa"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= url('/comunidad?' . http_build_query([
                                        'busqueda' => $busqueda,
                                        'orden' => $orden,
                                        'pagina' => $i,
                                    ])) ?>">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <a href="<?= url('/comunidad?' . http_build_query([
                                    'busqueda' => $busqueda,
                                    'orden' => $orden,
                                    'pagina' => $pagina_actual + 1,
                                ])) ?>">
                        <?= htmlspecialchars(t('comunidad.index.siguiente')) ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>