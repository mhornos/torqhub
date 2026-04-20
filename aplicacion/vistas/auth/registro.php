<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">

</head>
<body>
    <h1>Registrarse</h1>
    
<!-- // mensajes flash -->
    <?php if ($mensaje = flash_get('error')): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

<!-- // formulario de registro -->
    <form method="post" action="<?= url('/registro') ?>">
        <?= csrf_campo() ?>

        <div>
            <label>Nombre</label>
            <input type="text" name="nombre" required>
        </div>

        <div>
            <label>Correo electrónico</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">Crear cuenta</button>
    </form>

</body>
</html>