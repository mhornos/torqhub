<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>

    <h1>Diagnóstico asistido</h1>

    <p>
        Describe los síntomas del vehículo y TorqHub intentará mostrar posibles causas usando un sistema experto basado en reglas.
    </p>

    <?php if ($mensaje = flash_get('error')): ?>
        <p style="color: red;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= url('/diagnostico/analizar') ?>">
        <?= csrf_campo() ?>

        <div>
            <label for="sintomas">Síntomas del vehículo:</label><br>
            <textarea 
                id="sintomas" 
                name="sintomas" 
                rows="6" 
                cols="80"
                placeholder="Ejemplo: el coche no arranca, hace click y las luces se ven flojas"
            ><?= htmlspecialchars($sintomas ?? '') ?></textarea>
        </div>

        <br>

        <button type="submit">Analizar síntomas</button>
    </form>

    <?php if (!empty($resultados)): ?>
        <h2>Posibles diagnósticos:</h2>

        <?php foreach ($resultados as $resultado): ?>
            <div style="margin: 15px; padding: 15px; background: #ffffff45; border: 1px solid #00000023;">
                <h3>"<?= htmlspecialchars($resultado['titulo']) ?>"</h3>

                <p>
                    Confianza aproximada:
                    <strong><?= (int) $resultado['confianza'] ?>%</strong>
                </p>

                <p>
                    Coincidencias detectadas:
                    <?= (int) $resultado['coincidencias'] ?>
                </p>

                <p>
                    Recomendación:
                    <?= htmlspecialchars($resultado['recomendacion']) ?>
                </p>
            </div>
        <?php endforeach; ?>

    <?php elseif (($sintomas ?? '') !== ''): ?>
        <h2>Sin resultados claros</h2>
        <p>
            No se han detectado coincidencias suficientes. prueba escribiendo síntomas más concretos.
        </p>
    <?php endif; ?>

</body>
</html>