<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>mi garaje</h1>
 
    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <p><a href="<?= url('/garaje/nuevo') ?>">añadir vehiculo</a></p>

    <?php if (count($vehiculos) === 0): ?>
        <p>no tienes vehiculos aún</p>
    <?php else: ?>
        <ul>
            <?php foreach ($vehiculos as $v): ?>
                <li>
                    <?= htmlspecialchars($v['marca']) ?> <?= htmlspecialchars($v['modelo']) ?>
                    <?php if (!empty($v['any'])): ?>
                        (<?= (int) $v['any'] ?>)
                    <?php endif; ?>
                    
                    <a href="<?= url('/garaje/ver?id=' . (int) $v['id']) ?>">ver</a>
                    <a href="<?= url('/garaje/editar?id=' . (int) $v['id']) ?>">editar</a>
                    <a href="<?= url('/garaje/eliminar?id=' . (int) $v['id']) ?>">eliminar</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>