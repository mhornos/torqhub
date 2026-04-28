<div id="contenedor-paginacion-comunidad">
    <?php if ($total_paginas > 1): ?>
        <div class="paginacion-comunidad" aria-label="Paginación de publicaciones">
            <p class="estado-paginacion">
                Página <?= (int) $pagina_actual ?> de <?= (int) $total_paginas ?>
            </p>

            <div class="enlaces-paginacion">
                <?php if ($pagina_actual > 1): ?>
                    <a href="<?= url('/comunidad?' . http_build_query([
                        'busqueda' => $busqueda,
                        'orden' => $orden,
                        'pagina' => $pagina_actual - 1,
                    ])) ?>">
                        Anterior
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <?php if ($i === (int) $pagina_actual): ?>
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
                        Siguiente
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>