<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vehículo público - torqhub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>

    <h1>
        <?= htmlspecialchars($vehiculo['marca']) ?>
        <?= htmlspecialchars($vehiculo['modelo']) ?>
    </h1>

    <p>
        propietario:
        <a href="<?= url('/perfil?usuario=' . urlencode($vehiculo['autor_nombre'])) ?>">
            @<?= htmlspecialchars($vehiculo['autor_nombre']) ?>
        </a>
    </p>

    <?php if (!empty($vehiculo['imagen'])): ?>
        <img 
            src="<?= url('/public/uploads/vehiculos/' . rawurlencode($vehiculo['imagen'])) ?>" 
            alt="Imagen del vehículo"
            style="max-width: 700px; width: 100%; display:block; margin-bottom:20px;"
        >
    <?php endif; ?>

    <section class="perfil-bloque">
        <h2>Detalles públicos</h2>

        <p><strong>Año:</strong> <?= htmlspecialchars($vehiculo['any']) ?></p>

        <?php if (!empty($vehiculo['carroceria'])): ?>
            <p><strong>Carrocería:</strong> <?= htmlspecialchars($vehiculo['carroceria']) ?></p>
        <?php endif; ?>

        <?php if (!empty($vehiculo['tipo_combustible'])): ?>
            <p><strong>Combustible:</strong> <?= htmlspecialchars($vehiculo['tipo_combustible']) ?></p>
        <?php endif; ?>

        <?php if (!empty($vehiculo['tipo_cambio'])): ?>
            <p><strong>Cambio:</strong> <?= htmlspecialchars($vehiculo['tipo_cambio']) ?></p>
        <?php endif; ?>

        <?php if (!empty($vehiculo['potencia_cv'])): ?>
            <p><strong>Potencia:</strong> <?= (int) $vehiculo['potencia_cv'] ?> cv</p>
        <?php endif; ?>

        <?php if (!empty($vehiculo['cilindrada_cm3'])): ?>
            <p><strong>Cilindrada:</strong> <?= (int) $vehiculo['cilindrada_cm3'] ?> cm³</p>
        <?php endif; ?>
    </section>

    <p>
        <a href="<?= url('/perfil?usuario=' . urlencode($vehiculo['autor_nombre'])) ?>">
            Volver al perfil
        </a>
    </p>

</body>
</html>