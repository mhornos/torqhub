<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('diagnostico.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <?php
    $mensajes = $mensajes ?? [];
    ?>

    <section class="diagnostico">
        <div class="diagnostico__cabecera">
            <h1><?= htmlspecialchars(t('diagnostico.titulo')) ?></h1>
            <p>
                <?= htmlspecialchars(t('diagnostico.descripcion')) ?>
            </p>
        </div>

        <form method="POST" action="<?= url('/diagnostico/reiniciar') ?>" class="diagnostico__reiniciar">
            <?= csrf_campo() ?>
            <button type="submit"><?= htmlspecialchars(t('diagnostico.reiniciar')) ?></button>
        </form>

        <div class="diagnostico__chat" id="chat-diagnostico">
            <div class="diagnostico__mensaje diagnostico__mensaje--ia">
                <strong><?= htmlspecialchars(t('diagnostico.ia_nombre')) ?></strong>
                <p><?= htmlspecialchars(t('diagnostico.mensaje_inicial')) ?></p>
            </div>

            <?php foreach ($mensajes as $mensaje): ?>

                <?php if ($mensaje['tipo'] === 'usuario'): ?>
                    <div class="diagnostico__mensaje diagnostico__mensaje--usuario">
                        <strong><?= htmlspecialchars(t('diagnostico.usuario')) ?></strong>
                        <p><?= htmlspecialchars($mensaje['texto']) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($mensaje['tipo'] === 'ia'): ?>
                    <div class="diagnostico__mensaje diagnostico__mensaje--ia">
                        <strong><?= htmlspecialchars(t('diagnostico.ia_nombre')) ?></strong>

                        <?php if (!empty($mensaje['resultados'])): ?>
                            <p><?= htmlspecialchars(t('diagnostico.resultados_intro')) ?></p>

                            <div class="diagnostico__resultados">
                                <?php foreach ($mensaje['resultados'] as $resultado): ?>
                                    <article class="diagnostico__resultado">
                                        <h3>"<?= htmlspecialchars($resultado['titulo']) ?>"</h3>

                                        <p>
                                            <?= htmlspecialchars(t('diagnostico.confianza_aproximada')) ?>:
                                            <strong><?= (int) $resultado['confianza'] ?>%</strong>
                                        </p>

                                        <div class="diagnostico__barra">
                                            <span style="width: <?= (int) $resultado['confianza'] ?>%;"></span>
                                        </div>

                                        <p>
                                            <?= htmlspecialchars(t('diagnostico.coincidencias_detectadas')) ?>:
                                            <?= (int) $resultado['coincidencias'] ?>
                                        </p>

                                        <p>
                                            <strong><?= htmlspecialchars(t('diagnostico.recomendacion')) ?>:</strong>
                                            <?= htmlspecialchars($resultado['recomendacion']) ?>
                                        </p>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>
                                <?= htmlspecialchars(t('diagnostico.sin_resultados')) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>

        <?php if ($mensaje = flash_get('error')): ?>
            <p class="diagnostico__error"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <form
            class="diagnostico__formulario"
            method="POST"
            action="<?= url('/diagnostico/analizar') ?>"
            data-url-ajax="<?= url('/diagnostico/ajax') ?>"
            data-texto-usuario="<?= htmlspecialchars(t('diagnostico.usuario')) ?>"
            data-texto-ia="<?= htmlspecialchars(t('diagnostico.ia_nombre')) ?>"
            data-texto-cargando="<?= htmlspecialchars(t('diagnostico.estado_analizando')) ?>"
            data-texto-analizando="<?= htmlspecialchars(t('diagnostico.estado_analizando')) ?>"
            data-texto-analizar="<?= htmlspecialchars(t('diagnostico.boton_analizar')) ?>"
            data-error-analisis="<?= htmlspecialchars(t('diagnostico.error.analisis')) ?>"
            data-error-conexion="<?= htmlspecialchars(t('diagnostico.error.conexion')) ?>"
            data-resultados-intro="<?= htmlspecialchars(t('diagnostico.resultados_intro')) ?>"
            data-sin-resultados="<?= htmlspecialchars(t('diagnostico.sin_resultados')) ?>"
            data-confianza="<?= htmlspecialchars(t('diagnostico.confianza_aproximada')) ?>"
            data-coincidencias="<?= htmlspecialchars(t('diagnostico.coincidencias_detectadas')) ?>"
            data-recomendacion="<?= htmlspecialchars(t('diagnostico.recomendacion')) ?>"
            id="formulario-diagnostico">
            <?= csrf_campo() ?>

            <label for="sintomas"><?= htmlspecialchars(t('diagnostico.label_sintomas')) ?>:</label>

            <div class="diagnostico__entrada">
                <textarea
                    id="sintomas"
                    name="sintomas"
                    rows="3"
                    placeholder="<?= htmlspecialchars(t('diagnostico.placeholder_sintomas')) ?>"></textarea>

                <button type="submit"><?= htmlspecialchars(t('diagnostico.boton_analizar')) ?></button>
            </div>
        </form>
    </section>

    <script src="<?= url('/public/js/diagnostico.js') ?>"></script>
</body>

</html>