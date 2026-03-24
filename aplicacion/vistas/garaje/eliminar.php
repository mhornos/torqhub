<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>eliminar vehiculo</h1>

    <p>¿seguro que quieres eliminar este vehiculo?</p>

    <p>
        <strong><?= htmlspecialchars($vehiculo['marca']) ?> <?= htmlspecialchars($vehiculo['modelo']) ?></strong>
        <?php if (!empty($vehiculo['any'])): ?>
            (<?= (int) $vehiculo['any'] ?>)
        <?php endif; ?>
    </p>

    <form method="post" action="<?= url('/garaje/eliminar') ?>">
        <?= csrf_campo() ?>
        <input type="hidden" name="id" value="<?= (int) $vehiculo['id'] ?>">
        <button type="button" onclick="history.back()">cancelar</button>
        <button type="submit">eliminar</button>
    </form>


</body>
</html>