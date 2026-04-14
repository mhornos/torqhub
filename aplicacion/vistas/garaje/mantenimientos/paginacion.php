<?php if (($total_paginas ?? 1) > 1): ?>
    <div class="paginacion-mantenimientos">
        <?php if (($pagina_actual ?? 1) > 1): ?>
            <button type="button" class="btn-paginacion" data-pagina="<?= (int) $pagina_actual - 1 ?>">
                anterior
            </button>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <button
                type="button"
                class="btn-paginacion <?= ($i === (int) $pagina_actual) ? 'activo' : '' ?>"
                data-pagina="<?= $i ?>"
            >
                <?= $i ?>
            </button>
        <?php endfor; ?>

        <?php if (($pagina_actual ?? 1) < $total_paginas): ?>
            <button type="button" class="btn-paginacion" data-pagina="<?= (int) $pagina_actual + 1 ?>">
                siguiente
            </button>
        <?php endif; ?>
    </div>
<?php endif; ?>