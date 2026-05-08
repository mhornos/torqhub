<?php
$mensajes = isset($mensajes) && is_array($mensajes)
    ? $mensajes
    : [];
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('diagnostico.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <main class="diagnostico-contenedor">
        <header class="diagnostico-cabecera">
            <div class="diagnostico-cabecera__texto">
                <h1><?= htmlspecialchars(t('diagnostico.titulo')) ?></h1>

                <p>
                    <?= htmlspecialchars(t('diagnostico.descripcion')) ?>
                </p>
            </div>
        </header>

        <?php if ($mensaje = flash_get('error')): ?>
            <p class="mensaje-error diagnostico-error"><?= htmlspecialchars($mensaje) ?></p>
        <?php endif; ?>

        <section class="diagnostico-panel" aria-label="<?= htmlspecialchars(t('diagnostico.titulo')) ?>">
            <div class="diagnostico-chat" id="chat-diagnostico" aria-live="polite">
                <article class="diagnostico__mensaje diagnostico__mensaje--ia">
                    <header class="diagnostico__mensaje-cabecera">
                        <strong class="diagnostico__mensaje-autor">
                            <?= htmlspecialchars(t('diagnostico.ia_nombre')) ?>
                        </strong>
                    </header>

                    <div class="diagnostico__mensaje-contenido">
                        <p><?= htmlspecialchars(t('diagnostico.mensaje_inicial')) ?></p>
                    </div>
                </article>

                <?php foreach ($mensajes as $mensaje): ?>
                    <?php if (($mensaje['tipo'] ?? '') === 'usuario'): ?>
                        <article class="diagnostico__mensaje diagnostico__mensaje--usuario">
                            <header class="diagnostico__mensaje-cabecera">
                                <strong class="diagnostico__mensaje-autor">
                                    <?= htmlspecialchars(t('diagnostico.usuario')) ?>
                                </strong>
                            </header>

                            <div class="diagnostico__mensaje-contenido">
                                <p><?= htmlspecialchars($mensaje['texto'] ?? '') ?></p>
                            </div>
                        </article>
                    <?php endif; ?>

                    <?php if (($mensaje['tipo'] ?? '') === 'ia'): ?>
                        <article class="diagnostico__mensaje diagnostico__mensaje--ia">
                            <header class="diagnostico__mensaje-cabecera">
                                <strong class="diagnostico__mensaje-autor">
                                    <?= htmlspecialchars(t('diagnostico.ia_nombre')) ?>
                                </strong>
                            </header>

                            <div class="diagnostico__mensaje-contenido">
                                <?php if (!empty($mensaje['resultados'])): ?>
                                    <p><?= htmlspecialchars(t('diagnostico.resultados_intro')) ?></p>

                                    <div class="diagnostico__resultados">
                                        <?php foreach ($mensaje['resultados'] as $resultado): ?>
                                            <?php
                                            $confianza = max(0, min(100, (int) ($resultado['confianza'] ?? 0)));
                                            ?>

                                            <article class="diagnostico__resultado">
                                                <header class="diagnostico__resultado-cabecera">
                                                    <h2><?= htmlspecialchars($resultado['titulo'] ?? '') ?></h2>
                                                </header>

                                                <div class="diagnostico__resultado-contenido">
                                                    <p>
                                                        <?= htmlspecialchars(t('diagnostico.confianza_aproximada')) ?>:
                                                        <strong><?= $confianza ?>%</strong>
                                                    </p>

                                                    <progress
                                                        class="diagnostico__progreso"
                                                        max="100"
                                                        value="<?= $confianza ?>">
                                                        <?= $confianza ?>%
                                                    </progress>

                                                    <p>
                                                        <?= htmlspecialchars(t('diagnostico.coincidencias_detectadas')) ?>:
                                                        <?= (int) ($resultado['coincidencias'] ?? 0) ?>
                                                    </p>

                                                    <p>
                                                        <strong><?= htmlspecialchars(t('diagnostico.recomendacion')) ?>:</strong>
                                                        <?= htmlspecialchars($resultado['recomendacion'] ?? '') ?>
                                                    </p>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p><?= htmlspecialchars(t('diagnostico.sin_resultados')) ?></p>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <form
                class="diagnostico-formulario"
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

                <div class="diagnostico-formulario__campo">
                    <label for="sintomas"><?= htmlspecialchars(t('diagnostico.label_sintomas')) ?>:</label>

                    <div class="diagnostico-formulario__entrada">
                        <textarea
                            id="sintomas"
                            name="sintomas"
                            rows="3"
                            placeholder="<?= htmlspecialchars(t('diagnostico.placeholder_sintomas')) ?>"></textarea>

                        <button type="submit">
                            <?= htmlspecialchars(t('diagnostico.boton_analizar')) ?>
                        </button>
                    </div>
                </div>
            </form>

            <div class="diagnostico-panel__acciones">
                <form method="POST" action="<?= url('/diagnostico/reiniciar') ?>" class="diagnostico-reiniciar">
                    <?= csrf_campo() ?>

                    <button type="submit">
                        <?= htmlspecialchars(t('diagnostico.reiniciar')) ?>
                    </button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>