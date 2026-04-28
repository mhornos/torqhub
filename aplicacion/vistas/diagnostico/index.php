<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <?php
    $mensajes = $mensajes ?? [];
    ?>

    <section class="diagnostico">
        <div class="diagnostico__cabecera">
            <h1>diagnóstico asistido</h1>
            <p>
                describe los síntomas del vehículo y torqhub analizará posibles causas mediante un sistema experto basado en reglas.
            </p>
        </div>

        <form method="POST" action="<?= url('/diagnostico/reiniciar') ?>" class="diagnostico__reiniciar">
            <?= csrf_campo() ?>
            <button type="submit">reiniciar chat</button>
        </form>

        <div class="diagnostico__chat" id="chat-diagnostico">
            <div class="diagnostico__mensaje diagnostico__mensaje--ia">
                <strong>torqhub ia</strong>
                <p>hola, dime qué le ocurre a tu coche. por ejemplo: “el coche no arranca y hace click”.</p>
            </div>

            <?php foreach ($mensajes as $mensaje): ?>

                <?php if ($mensaje['tipo'] === 'usuario'): ?>
                    <div class="diagnostico__mensaje diagnostico__mensaje--usuario">
                        <strong>tú</strong>
                        <p><?= htmlspecialchars($mensaje['texto']) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($mensaje['tipo'] === 'ia'): ?>
                    <div class="diagnostico__mensaje diagnostico__mensaje--ia">
                        <strong>torqhub ia</strong>

                        <?php if (!empty($mensaje['resultados'])): ?>
                            <p>he encontrado estas posibles causas:</p>

                            <div class="diagnostico__resultados">
                                <?php foreach ($mensaje['resultados'] as $resultado): ?>
                                    <article class="diagnostico__resultado">
                                        <h3><?= htmlspecialchars($resultado['titulo']) ?></h3>

                                        <p>
                                            confianza aproximada:
                                            <strong><?= (int) $resultado['confianza'] ?>%</strong>
                                        </p>

                                        <div class="diagnostico__barra">
                                            <span style="width: <?= (int) $resultado['confianza'] ?>%;"></span>
                                        </div>

                                        <p>
                                            coincidencias detectadas:
                                            <?= (int) $resultado['coincidencias'] ?>
                                        </p>

                                        <p>
                                            <strong>recomendación:</strong>
                                            <?= htmlspecialchars($resultado['recomendacion']) ?>
                                        </p>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>
                                no he encontrado una causa clara. prueba describiendo síntomas más concretos como ruido,
                                temperatura, arranque, frenos, dirección o pérdida de potencia.
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
            id="formulario-diagnostico">
            <?= csrf_campo() ?>

            <label for="sintomas">síntomas del vehículo</label>

            <div class="diagnostico__entrada">
                <textarea
                    id="sintomas"
                    name="sintomas"
                    rows="3"
                    placeholder="escribe aquí los síntomas..."></textarea>

                <button type="submit">analizar</button>
            </div>
        </form>
    </section>

    <script src="<?= url('/public/js/diagnostico.js') ?>"></script>
</body>

</html>