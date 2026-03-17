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
            <?= (int) $vehiculo['cilindrada_cm3'] ?> cm3
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>
    
    <p><strong>fecha de alta:</strong> <?= htmlspecialchars($vehiculo['fecha_creacion']) ?></p>

    <p>
        <a href="<?= url('/garaje') ?>">volver al garaje</a>
        <a href="<?= url('/garaje/editar?id=' . (int) $vehiculo['id']) ?>">editar</a>
        <a href="<?= url('/garaje/eliminar?id=' . (int) $vehiculo['id']) ?>">eliminar</a>
    </p>
</body>
</html>