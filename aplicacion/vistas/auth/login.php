<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('auth.login.titulo_pagina')) ?> - TorqHub</title>
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
                <h1><?= htmlspecialchars(t('auth.login.titulo')) ?></h1>
            </header>

            <form method="post" action="<?= url('/login') ?>" class="auth-formulario">
                <?= csrf_campo() ?>

                <div class="auth-formulario__campo">
                    <label for="email"><?= htmlspecialchars(t('auth.login.email')) ?></label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        maxlength="120"
                        autocomplete="email">
                </div>

                <div class="auth-formulario__campo">
                    <label for="password"><?= htmlspecialchars(t('auth.login.password')) ?></label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        maxlength="255"
                        autocomplete="current-password">
                </div>

                <div class="auth-formulario__acciones">
                    <button type="submit"><?= htmlspecialchars(t('auth.login.boton')) ?></button>
                </div>
            </form>

            <div class="auth-enlaces">
                <a href="<?= url('/password/olvidada') ?>">
                    <?= htmlspecialchars(t('auth.login.password_olvidada')) ?>
                </a>
            </div>
        </section>
    </main>

</body>

</html>