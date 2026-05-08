<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('auth.registro.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">

</head>

<body class="auth-pagina">
    <main class="auth-contenedor">
        <?php if ($mensaje = flash_get('error')): ?>
            <p class="mensaje-error"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <?php if ($mensaje = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>
        <section class="auth-panel">
            <header class="auth-cabecera">
                <h1><?= htmlspecialchars(t('auth.registro.titulo')) ?></h1>
            </header>

            <form action="<?= url('/registro') ?>" method="POST" id="formulario-registro" class="auth-formulario">
                <?= csrf_campo() ?>

                <div class="auth-formulario__campo">
                    <label for="nombre"><?= htmlspecialchars(t('auth.registro.nombre')) ?></label>
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        required
                        maxlength="80"
                        pattern="^(?!.*\.\.)(?!.*\.$)[a-z0-9._]+$"
                        title="<?= htmlspecialchars(t('auth.registro.nombre_ayuda')) ?>"
                        autocomplete="username">
                    <small><?= htmlspecialchars(t('auth.registro.nombre_ayuda')) ?></small>
                </div>

                <div class="auth-formulario__campo">
                    <label for="email"><?= htmlspecialchars(t('auth.registro.email')) ?></label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        maxlength="120"
                        autocomplete="email">
                </div>

                <div class="auth-formulario__campo">
                    <label for="password"><?= htmlspecialchars(t('auth.registro.password')) ?></label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        minlength="8"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                        title="<?= htmlspecialchars(t('auth.registro.password_ayuda')) ?>"
                        autocomplete="new-password">
                </div>

                <div class="auth-formulario__campo">
                    <label for="password_repetida"><?= htmlspecialchars(t('auth.registro.password_repetida')) ?>:</label>
                    <input
                        type="password"
                        name="password_repetida"
                        id="password_repetida"
                        required
                        minlength="8"
                        maxlength="255"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                        title="<?= htmlspecialchars(t('auth.registro.password_ayuda')) ?>"
                        autocomplete="new-password">
                    <div id="error-password-repetida" class="mensaje-error-campo" style="display:none;">
                        <?= htmlspecialchars(t('auth.registro.error_password_repetida')) ?>
                    </div>
                    <small><?= htmlspecialchars(t('auth.registro.password_ayuda')) ?></small>
                </div>

                <div class="auth-formulario__acciones">
                    <button type="submit"><?= htmlspecialchars(t('auth.registro.boton')) ?></button>
                </div>
            </form>
        </section>
    </main>

    <script src="<?= url('/public/js/auth/registro.js') ?>"></script>
</body>

</html>