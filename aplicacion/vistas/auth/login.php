<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">

</head>
<body>
    <h1>Iniciar sesión</h1>

<!-- // mensajes flash -->
    <?php if ($mensaje = flash_get('error')): ?>
    <p><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <?php if ($mensaje = flash_get('ok')): ?>
    <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

<!-- // formulario de login -->
    <form method="post" action="<?= url('/login') ?>">
        <?= csrf_campo() ?>

        <div>
            <label>Correo electrónico</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Entrar</button>
    </form>

    <p>
        <a href="<?= url('/password/olvidada') ?>">He olvidado la contraseña</a>
    </p>

</body>
</html>