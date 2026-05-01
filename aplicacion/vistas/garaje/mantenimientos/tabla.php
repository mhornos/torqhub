<?php if (empty($mantenimientos)): ?>
    <p><?= htmlspecialchars(t('garaje.historial.sin_mantenimientos')) ?>.</p>
<?php else: ?>
    <table class="tabla-mantenimientos">
        <thead>
            <tr>
                <th><?= htmlspecialchars(t('garaje.historial.fecha')) ?></th>
                <th><?= htmlspecialchars(t('garaje.historial.tipo')) ?></th>
                <th><?= htmlspecialchars(t('garaje.historial.descripcion')) ?></th>
                <th><?= htmlspecialchars(t('garaje.historial.kilometros')) ?></th>
                <th><?= htmlspecialchars(t('garaje.historial.coste')) ?></th>
                <th><?= htmlspecialchars(t('garaje.historial.acciones')) ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($mantenimientos as $mantenimiento): ?>
                <tr>
                    <td class="nowrap">
                        <?= formatear_fecha($mantenimiento['fecha_creacion']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($mantenimiento['tipo']) ?>
                    </td>

                    <td>
                        <?php if (!empty($mantenimiento['descripcion'])): ?>
                            <?= nl2br(htmlspecialchars($mantenimiento['descripcion'])) ?>
                        <?php else: ?>
                            (<?= htmlspecialchars(t('garaje.historial.no_indicada')) ?>)
                        <?php endif; ?>
                    </td>

                    <td class="nowrap">
                        <?php if (!is_null($mantenimiento['kilometros'])): ?>
                            <?= (int) $mantenimiento['kilometros'] ?> km
                        <?php else: ?>
                            (<?= htmlspecialchars(t('garaje.historial.no_indicados')) ?>)
                        <?php endif; ?>
                    </td>

                    <td class="nowrap">
                        <?php if (!is_null($mantenimiento['coste'])): ?>
                            <?= number_format((float) $mantenimiento['coste'], 2, ',', '.') ?> €
                        <?php else: ?>
                            (<?= htmlspecialchars(t('garaje.detalle.no_indicado')) ?>)
                        <?php endif; ?>
                    </td>

                    <td class="nowrap">
                        <form
                            action="<?= url('/garaje/mantenimientos/eliminar') ?>"
                            method="POST"
                            class="form-eliminar-mantenimiento"
                            data-confirmacion-eliminar="<?= htmlspecialchars(t('garaje.historial.confirmar_eliminar')) ?>">
                            <?= csrf_campo() ?>

                            <input type="hidden" name="mantenimiento_id" value="<?= (int) $mantenimiento['id'] ?>">

                            <button
                                type="button"
                                onclick="location.href='<?= url('/garaje/mantenimientos/editar?id=' . (int) $mantenimiento['id']) ?>'">
                                <?= htmlspecialchars(t('garaje.historial.editar')) ?>
                            </button>

                            <button type="submit"><?= htmlspecialchars(t('garaje.historial.eliminar')) ?></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>