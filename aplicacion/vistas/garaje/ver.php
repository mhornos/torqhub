<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>detalle del vehiculo</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <p><strong>marca:</strong> <?= htmlspecialchars($vehiculo['marca']) ?></p>

    <p><strong>modelo:</strong> <?= htmlspecialchars($vehiculo['modelo']) ?></p>

    <p>
        <strong>año:</strong>
        <?php if (!empty($vehiculo['any'])): ?>
            <?= (int) $vehiculo['any'] ?>
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>vin:</strong>
        <?php if (!empty($vehiculo['vin'])): ?>
            <?= htmlspecialchars($vehiculo['vin']) ?>
        <?php else: ?>  
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>carroceria:</strong>
        <?php if (!empty($vehiculo['carroceria'])): ?>
            <?= htmlspecialchars($vehiculo['carroceria']) ?>
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>tipo de combustible:</strong>
        <?php if (!empty($vehiculo['tipo_combustible'])): ?>
            <?= htmlspecialchars($vehiculo['tipo_combustible']) ?>
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>tipo de cambio:</strong>
        <?php if (!empty($vehiculo['tipo_cambio'])): ?>
            <?= htmlspecialchars($vehiculo['tipo_cambio']) ?>
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>potencia:</strong>
        <?php if (!is_null($vehiculo['potencia_cv'])): ?>
            <?= (int) $vehiculo['potencia_cv'] ?> cv
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>cilindrada:</strong>
        <?php if (!is_null($vehiculo['cilindrada_cm3'])): ?>
            <?= (int) $vehiculo['cilindrada_cm3'] ?> cc
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>
    
    <p><strong>fecha de alta:</strong> <?= htmlspecialchars($vehiculo['fecha_creacion']) ?></p>

    <p>
        <button type="button" onclick="location.href='<?= url('/garaje') ?>'">volver al garaje</button>
        <button type="button" onclick="location.href='<?= url('/garaje/editar?id=' . (int) $vehiculo['id']) ?>'">editar</button>
        <button type="button" onclick="location.href='<?= url('/garaje/eliminar?id=' . (int) $vehiculo['id']) ?>'">eliminar</button>
    </p>

    <hr>
    <h1>historial de mantenimiento</h1>

    <p>
        <a href="<?= url('/garaje/mantenimientos/nuevo?vehiculo_id=' . (int) $vehiculo['id']) ?>">
            añadir mantenimiento
        </a>
    </p>

    <?php if (empty($mantenimientos)): ?>
        <p>este vehículo todavía no tiene mantenimientos registrados.</p>
    <?php else: ?>
        <table class="tabla-mantenimientos">
            <thead>
                <tr>
                    <th>fecha</th>
                    <th>tipo</th>
                    <th>descripción</th>
                    <th>kilómetros</th>
                    <th>coste</th>
                    <th>acciones</th>
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
                                no indicada
                            <?php endif; ?>
                        </td>

                        <td class="nowrap">
                            <?php if (!is_null($mantenimiento['kilometros'])): ?>
                                <?= (int) $mantenimiento['kilometros'] ?> km
                            <?php else: ?>
                                no indicados
                            <?php endif; ?>
                        </td>

                        <td class="nowrap">
                            <?php if (!is_null($mantenimiento['coste'])): ?>
                                <?= number_format((float) $mantenimiento['coste'], 2, ',', '.') ?> €
                            <?php else: ?>
                                no indicado
                            <?php endif; ?>
                        </td>

                        <td class="nowrap">
                            <form action="<?= url('/garaje/mantenimientos/eliminar') ?>" method="POST" onsubmit="return confirm('¿seguro que quieres eliminar este mantenimiento?');">

                                <?= csrf_campo() ?>

                                <input type="hidden" name="mantenimiento_id" value="<?= (int) $mantenimiento['id'] ?>">

                                <button type="button"
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
    
</body>
</html>