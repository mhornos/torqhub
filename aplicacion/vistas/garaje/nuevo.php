<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>añadir vehiculo</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= url('/garaje/nuevo') ?>">
        <?= csrf_campo() ?>

        <div>
            <label>marca</label>
            <input type="text" name="marca" required>
        </div>

        <div>
            <label>modelo</label>
            <input type="text" name="modelo" required>
        </div>

        <div>
            <label>año</label>
            <input type="number" name="any" min="1900" max="2026">
        </div>

        <div>
            <label>vin (opcional)</label>
            <input type="text" name="vin" maxlength="25">
        </div>

        <button type="submit">guardar</button>
    </form>

    <p><a href="<?= url('/garaje') ?>">volver</a></p>

</body>
</html>