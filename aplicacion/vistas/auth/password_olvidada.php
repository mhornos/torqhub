<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>recuperar contraseña - torqhub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>

    <h1>Recuperar contraseña</h1>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form action="<?= url('/password/olvidada') ?>" method="POST">
        <?= csrf_campo() ?>

        <div>
            <label for="email">Correo electrónico:</label>
            <input 
                type="email" 
                name="email" 
                id="email" 
                required
            >
        </div>

        <br>

        <button type="submit">Enviar enlace de recuperación</button>
    </form>

    <p>
        <a href="<?= url('/login') ?>">Volver a iniciar sesión</a>
    </p>

</body>
</html>