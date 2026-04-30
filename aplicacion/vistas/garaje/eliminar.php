<?php
if (!isset($vehiculo) || !is_array($vehiculo)) {
    flash_set('error', 'No se ha podido cargar el vehículo para editar');
    header('Location: ' . url('/garaje'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Eliminar vehículo</h1>

    <p>¿Seguro que quieres eliminar este vehículo?</p>

    <p>
        <strong><?= htmlspecialchars($vehiculo['marca']) ?> <?= htmlspecialchars($vehiculo['modelo']) ?></strong>
        <?php if (!empty($vehiculo['any'])): ?>
            (<?= (int) $vehiculo['any'] ?>)
        <?php endif; ?>
    </p>

    <form method="post" action="<?= url('/garaje/eliminar') ?>">
        <?= csrf_campo() ?>
        <input type="hidden" name="id" value="<?= (int) $vehiculo['id'] ?>">
        <button type="button" onclick="history.back()">Cancelar</button>
        <button type="submit">Eliminar</button>
    </form>


</body>
</html>