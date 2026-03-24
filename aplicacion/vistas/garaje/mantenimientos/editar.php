<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>editar mantenimiento</h1>

    <?php
        $datos_formulario = $datos_formulario ?? [];
        $fecha_valor = $datos_formulario['fecha'] ?? $mantenimiento['fecha'] ?? '';
        $tipo_valor = $datos_formulario['tipo'] ?? $mantenimiento['tipo'] ?? '';
        $descripcion_valor = $datos_formulario['descripcion'] ?? $mantenimiento['descripcion'] ?? '';
        $kilometros_valor = $datos_formulario['kilometros'] ?? $mantenimiento['kilometros'] ?? '';
        $coste_valor = $datos_formulario['coste'] ?? $mantenimiento['coste'] ?? '';
    ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <p>
        <ul>
            <li><strong>vehículo:</strong>
                <?= htmlspecialchars($vehiculo['marca']) ?>
                <?= htmlspecialchars($vehiculo['modelo']) ?>
                <?php if (!empty($vehiculo['any'])): ?>
                    (<?= (int) $vehiculo['any'] ?>)
                <?php endif; ?>
            </li>
        </ul>
    </p>

    <form action="<?= url('/garaje/mantenimientos/editar') ?>" method="POST">
        <?= csrf_campo() ?>
        <input type="hidden" name="mantenimiento_id" value="<?= (int) $mantenimiento['id'] ?>">

        <div>
            <label for="fecha">fecha: *</label>
            <input
                type="date"
                id="fecha"
                name="fecha"
                value="<?= htmlspecialchars((string) $fecha_valor) ?>"
                required
            >
        </div>

        <div>
            <label for="tipo">tipo: *</label>
            <input
                type="text"
                id="tipo"
                name="tipo"
                maxlength="100"
                value="<?= htmlspecialchars((string) $tipo_valor) ?>"
                required
            >
        </div>

        <div>
            <label for="descripcion">descripción:</label><br>
            <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars((string) $descripcion_valor) ?></textarea>
        </div>

        <div>
            <label for="kilometros">kilómetros:</label>
            <input
                type="number"
                id="kilometros"
                name="kilometros"
                min="0"
                step="1"
                value="<?= htmlspecialchars((string) $kilometros_valor) ?>"
            >
        </div>

        <div>
            <label for="coste">coste (€):</label>
            <input
                type="number"
                id="coste"
                name="coste"
                min="0"
                step="0.01"
                value="<?= htmlspecialchars((string) $coste_valor) ?>"
            >
        </div><br>

        
        <button type="button" onclick="location.href='<?= url('/garaje/ver?id=' . (int) $vehiculo['id']) ?>'">volver</button>
        <button type="submit">guardar cambios</button>
    </form>

</body>
</html>