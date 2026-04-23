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
            <label for="nombre">Nombre de usuario</label>
            <input
                type="text"
                name="nombre"
                id="nombre"
                required
                maxlength="80"
                pattern="^(?!.*\.\.)(?!.*\.$)[a-z0-9._]+$"
                title="Solo letras minúsculas, números, puntos y guiones bajos, sin espacios, sin puntos consecutivos y sin terminar en punto"
                autocomplete="username"
            >
            <small>Solo letras minúsculas, números, puntos y guiones bajos, sin espacios, sin puntos consecutivos y sin terminar en punto</small>
        </div>

        <div>
            <label>Correo electrónico</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label for="password">Contraseña</label>
            <input
                type="password"
                name="password"
                id="password"
                required
                minlength="8"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                title="Mínimo 8 caracteres, una mayúscula, una minúscula y un número"
                autocomplete="new-password"
            >
            <small>Mínimo 8 caracteres, una mayúscula, una minúscula y un número</small>
        </div>
        <br>
        <button type="submit">Crear cuenta</button>
    </form>

</body>
</html>