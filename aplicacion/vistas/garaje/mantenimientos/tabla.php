<?php if (empty($mantenimientos)): ?>
    <p>No hay mantenimientos que coincidan con los filtros aplicados.</p>
<?php else: ?>
    <table class="tabla-mantenimientos">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Kilómetros</th>
                <th>Coste</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($mantenimientos as $mantenimiento): ?>
                <tr>
                    <td class="nowrap">
                        <?= htmlspecialchars($mantenimiento['fecha']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($mantenimiento['tipo']) ?>
                    </td>

                    <td>
                        <?php if (!empty($mantenimiento['descripcion'])): ?>
                            <?= nl2br(htmlspecialchars($mantenimiento['descripcion'])) ?>
                        <?php else: ?>
                            (No indicada)
                        <?php endif; ?>
                    </td>

                    <td class="nowrap">
                        <?php if (!is_null($mantenimiento['kilometros'])): ?>
                            <?= (int) $mantenimiento['kilometros'] ?> km
                        <?php else: ?>
                            (No indicados)
                        <?php endif; ?>
                    </td>

                    <td class="nowrap">
                        <?php if (!is_null($mantenimiento['coste'])): ?>
                            <?= number_format((float) $mantenimiento['coste'], 2, ',', '.') ?> €
                        <?php else: ?>
                            (No indicado)
                        <?php endif; ?>
                    </td>

                    <td class="nowrap">
                        <form action="<?= url('/garaje/mantenimientos/eliminar') ?>" method="POST" onsubmit="return confirm('¿seguro que quieres eliminar este mantenimiento?');">
                            <?= csrf_campo() ?>

                            <input type="hidden" name="mantenimiento_id" value="<?= (int) $mantenimiento['id'] ?>">

                            <button
                                type="button"
                                onclick="location.href='<?= url('/garaje/mantenimientos/editar?id=' . (int) $mantenimiento['id']) ?>'">
                                editar
                            </button>

                            <button type="submit">eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>