<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>añadir mantenimiento</h1>
    
    <?php
        $datos_formulario = $datos_formulario ?? [];
    ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <p>
        <ul>
            <li><strong> vehículo:</strong>
                <?= htmlspecialchars($vehiculo['marca']) ?>
                <?= htmlspecialchars($vehiculo['modelo']) ?>
                <?php if (!empty($vehiculo['any'])): ?>
                    (<?= (int) $vehiculo['any'] ?>)
                <?php endif; ?>
            </li>
        </ul>
    </p>

    <form action="<?= url('/garaje/mantenimientos/nuevo') ?>" method="POST">
        <?= csrf_campo() ?>
        <input type="hidden" name="vehiculo_id" value="<?= (int) $vehiculo['id'] ?>">

        <div>
            <label for="fecha">fecha: *</label>
            <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($datos_formulario['fecha'] ?? '') ?>" required>
        </div>

        <div>
            <label for="tipo">tipo: *</label>
            <input type="text" id="tipo" name="tipo" maxlength="100" value="<?= htmlspecialchars($datos_formulario['tipo'] ?? '') ?>" required>
        </div>

        <div>
            <label for="descripcion">descripción:</label><br>
            <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($datos_formulario['descripcion'] ?? '') ?></textarea>
        </div>

        <div>
            <label for="kilometros">kilómetros:</label>
            <input type="number" id="kilometros" name="kilometros" min="0" step="1" value="<?= htmlspecialchars($datos_formulario['kilometros'] ?? '') ?>">
        </div>

        <div>
            <label for="coste">coste (€):</label>
            <input type="number" id="coste" name="coste" min="0" step="0.01" value="<?= htmlspecialchars($datos_formulario['coste'] ?? '') ?>">
        </div><br>

        <button type="button" onclick="location.href='<?= url('/garaje/ver?id=' . (int) $vehiculo['id']) ?>'">volver</button>
        <button type="submit">guardar mantenimiento</button>   
    </form>

</body>
</html>