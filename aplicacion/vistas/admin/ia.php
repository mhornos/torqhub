<!DOCTYPE html>
<html lang="<?= escapar(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar(t('admin.ia.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= escapar(url('/public/css/estilos.css')) ?>">
</head>

<body>
    <main class="admin-panel">
        <section class="admin-panel__cabecera">
            <h1><?= escapar(t('admin.ia.titulo')) ?></h1>

            <p>
                <?= escapar(t('admin.ia.descripcion')) ?>
            </p>

            <a href="<?= escapar(url('/admin')) ?>" class="admin-enlace-volver">
                <?= escapar(t('admin.ia.volver')) ?>
            </a>
        </section>

        <section class="admin-bloque-info">
            <h2><?= escapar(t('admin.ia.estado_actual.titulo')) ?></h2>

            <p>
                <?= escapar(t('admin.ia.estado_actual.texto')) ?>
            </p>

            <ul>
                <li><?= escapar(t('admin.ia.estado_actual.punto_1')) ?></li>
                <li><?= escapar(t('admin.ia.estado_actual.punto_2')) ?></li>
                <li><?= escapar(t('admin.ia.estado_actual.punto_3')) ?></li>
                <li><?= escapar(t('admin.ia.estado_actual.punto_4')) ?></li>
            </ul>
        </section>

        <section class="admin-bloque-info">
            <h2><?= escapar(t('admin.ia.funcionamiento.titulo')) ?></h2>

            <ol>
                <li><?= escapar(t('admin.ia.funcionamiento.paso_1')) ?></li>
                <li><?= escapar(t('admin.ia.funcionamiento.paso_2')) ?></li>
                <li><?= escapar(t('admin.ia.funcionamiento.paso_3')) ?></li>
                <li><?= escapar(t('admin.ia.funcionamiento.paso_4')) ?></li>
                <li><?= escapar(t('admin.ia.funcionamiento.paso_5')) ?></li>
            </ol>
        </section>

        <section class="admin-bloque-info">
            <h2><?= escapar(t('admin.ia.decision.titulo')) ?></h2>

            <p>
                <?= escapar(t('admin.ia.decision.texto')) ?>
            </p>

            <div class="admin-aviso-defensa">
                <?= escapar(t('admin.ia.decision.defensa')) ?>
            </div>
        </section>

        <section class="admin-bloque-info">
            <h2><?= escapar(t('admin.ia.futuro.titulo')) ?></h2>

            <p>
                <?= escapar(t('admin.ia.futuro.texto')) ?>
            </p>

            <div class="admin-tabla-contenedor">
                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th><?= escapar(t('admin.ia.futuro.tabla')) ?></th>
                            <th><?= escapar(t('admin.ia.futuro.descripcion')) ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td><code>ia_causas</code></td>
                            <td><?= escapar(t('admin.ia.futuro.ia_causas')) ?></td>
                        </tr>

                        <tr>
                            <td><code>ia_keywords</code></td>
                            <td><?= escapar(t('admin.ia.futuro.ia_keywords')) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="admin-bloque-info">
            <h2><?= escapar(t('admin.ia.conclusion.titulo')) ?></h2>

            <p>
                <?= escapar(t('admin.ia.conclusion.texto')) ?>
            </p>
        </section>
    </main>
</body>

</html>