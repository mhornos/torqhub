<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>editar vehiculo</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= url('/garaje/editar') ?>">
        <?= csrf_campo() ?>

        <input type="hidden" name="id" value="<?= (int) $vehiculo['id'] ?>">

        <div>
            <label>marca</label>
            <input type="text" name="marca" value="<?= htmlspecialchars($vehiculo['marca']) ?>" required>
        </div>

        <div>
            <label>modelo</label>
            <input type="text" name="modelo" value="<?= htmlspecialchars($vehiculo['modelo']) ?>" required>
        </div>

        <div>
            <label>año</label>
            <input type="number" name="any" min="1900" max="2100" value="<?= htmlspecialchars((string) ($vehiculo['any'] ?? '')) ?>">
        </div>

        <div>
            <label>vin</label>
            <input type="text" name="vin" maxlength="25" value="<?= htmlspecialchars((string) ($vehiculo['vin'] ?? '')) ?>">
        </div>

        <button type="button" onclick="location.href='<?= url('/garaje') ?>'">cancelar</button>
        <button type="submit">guardar cambios</button>
    </form>

</body>
</html>