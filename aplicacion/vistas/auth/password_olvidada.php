<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('auth.password_olvidada.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body class="auth-pagina">

    <main class="auth-contenedor">
        <?php if ($m = flash_get('ok')): ?>
                <p class="mensaje-ok"><?= htmlspecialchars($m) ?></p>
            <?php endif; ?>

            <?php if ($m = flash_get('error')): ?>
                <p class="mensaje-error"><?= htmlspecialchars($m) ?></p>
            <?php endif; ?>
            
        <section class="auth-panel">
            <header class="auth-cabecera">
                <h1><?= htmlspecialchars(t('auth.password_olvidada.titulo')) ?></h1>
            </header>

            <form action="<?= url('/password/olvidada') ?>" method="POST" class="auth-formulario">
                <?= csrf_campo() ?>

                <div class="auth-formulario__campo">
                    <label for="email"><?= htmlspecialchars(t('auth.password_olvidada.email')) ?></label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        maxlength="120"
                        autocomplete="email">
                </div>

                <div class="auth-formulario__acciones">
                    <button type="submit"><?= htmlspecialchars(t('auth.password_olvidada.boton')) ?></button>
                </div>
            </form>

            <div class="auth-enlaces">
                <a href="<?= url('/login') ?>">
                    <?= htmlspecialchars(t('auth.password_olvidada.volver_login')) ?>
                </a>
            </div>
        </section>
    </main>
</body>

</html>