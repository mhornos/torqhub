<?php
$pagina_actual = isset($pagina_actual) ? (int) $pagina_actual : 1;
$total_paginas = isset($total_paginas) ? (int) $total_paginas : 1;

if ($pagina_actual < 1) {
    $pagina_actual = 1;
}

if ($total_paginas < 1) {
    $total_paginas = 1;
}
?>

<?php if ($total_paginas > 1): ?>
    <div class="paginacion-mantenimientos">
        <?php if ($pagina_actual > 1): ?>
            <button type="button" class="btn-paginacion" data-pagina="<?= $pagina_actual - 1 ?>">
                <?= htmlspecialchars(t('garaje.historial.anterior')) ?>
            </button>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <button
                type="button"
                class="btn-paginacion <?= ($i === $pagina_actual) ? 'activo' : '' ?>"
                data-pagina="<?= $i ?>"
            >
                <?= $i ?>
            </button>
        <?php endfor; ?>

        <?php if ($pagina_actual < $total_paginas): ?>
            <button type="button" class="btn-paginacion" data-pagina="<?= $pagina_actual + 1 ?>">
                <?= htmlspecialchars(t('garaje.historial.siguiente')) ?>
            </button>
        <?php endif; ?>
    </div>
<?php endif; ?>