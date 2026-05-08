<?php
$codigo_error = isset($codigo_error) ? (int) $codigo_error : 500;
$titulo_error = isset($titulo_error) ? (string) $titulo_error : t('error.500.titulo');
$mensaje_error = isset($mensaje_error) ? (string) $mensaje_error : t('error.500.mensaje');
$detalle_error = isset($detalle_error) ? (string) $detalle_error : t('error.descripcion.generica');

$texto_boton_principal = isset($texto_boton_principal)
    ? (string) $texto_boton_principal
    : t('error.boton.inicio');

$url_boton_principal = isset($url_boton_principal)
    ? (string) $url_boton_principal
    : url('/');

$usuario_autenticado = isset($_SESSION['usuario']);
?>

<!DOCTYPE html>
<html lang="<?= escapar(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar($codigo_error . ' - ' . $titulo_error) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= escapar(url('/public/css/estilos.css')) ?>">
</head>

<body>
    <main class="error-contenedor">
        <section class="error-panel" aria-labelledby="titulo-error">
            <p class="error-codigo">
                <?= escapar((string) $codigo_error) ?>
            </p>

            <div class="error-contenido">
                <h1 id="titulo-error">
                    <?= escapar($titulo_error) ?>
                </h1>

                <p class="error-mensaje">
                    <?= escapar($mensaje_error) ?>
                </p>

                <p class="error-detalle">
                    <?= escapar($detalle_error) ?>
                </p>

                <div class="error-acciones">
                    <a href="<?= escapar($url_boton_principal) ?>" class="error-boton error-boton--principal">
                        <?= escapar($texto_boton_principal) ?>
                    </a>

                    <?php if ($usuario_autenticado): ?>
                        <a href="<?= escapar(url('/garaje')) ?>" class="error-boton">
                            <?= escapar(t('error.boton.garaje')) ?>
                        </a>
                    <?php else: ?>
                        <a href="<?= escapar(url('/login')) ?>" class="error-boton">
                            <?= escapar(t('error.boton.login')) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
</body>

</html>