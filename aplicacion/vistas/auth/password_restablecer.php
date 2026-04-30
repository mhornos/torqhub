<?php
    $token = isset($token) && is_string($token) ? trim($token) : '';
    
    if ($token === '') {
        flash_set('error', 'El enlace para restablecer la contraseña no es válido');
        header('Location: ' . url('/login'));
        exit;
    }
    
    $errores = isset($errores) && is_array($errores) ? $errores : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>restablecer contraseña - torqhub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>

    <h1>Restablecer contraseña</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form action="<?= url('/password/restablecer') ?>" method="POST">
        <?= csrf_campo() ?>

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div>
            <label for="password">Nueva contraseña:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div>
            <label for="password_repetida">Repetir contraseña:</label>
            <input type="password" name="password_repetida" id="password_repetida" required>
        </div>

        <br>

        <button type="submit">Guardar nueva contraseña</button>
    </form>

</body>
</html>