<?php
$resumen_mantenimientos = $resumen_mantenimientos ?? [
    'total_mantenimientos' => 0,
    'coste_total' => 0,
];

$primer_mantenimiento = $mantenimientos[0] ?? null;
?>

<div class="resumen-mantenimientos">
    <div class="resumen-item">
        <span class="resumen-label"><?= htmlspecialchars(t('garaje.historial.total_visibles')) ?></span>
        <strong><?= (int) $resumen_mantenimientos['total_mantenimientos'] ?></strong>
    </div>

    <div class="resumen-item">
        <span class="resumen-label"><?= htmlspecialchars(t('garaje.historial.coste_total_visible')) ?></span>
        <strong><?= number_format((float) $resumen_mantenimientos['coste_total'], 2, ',', '.') ?> €</strong>
    </div>

    <div class="resumen-item">
        <span class="resumen-label"><?= htmlspecialchars(t('garaje.historial.primer_resultado')) ?></span>
        <strong>
            <?php if ($primer_mantenimiento): ?>
                <?= htmlspecialchars($primer_mantenimiento['fecha']) ?>
            <?php else: ?>
                <?= htmlspecialchars(t('garaje.historial.sin_resultados')) ?>
            <?php endif; ?>
        </strong>
    </div>
</div>