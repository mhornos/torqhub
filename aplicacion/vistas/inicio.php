<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">

</head>
<body>
    <?php if (isset($_SESSION['usuario'])): ?>
        <p>Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></p>
    <?php endif; ?>

    <h1>inicio</h1>
    <p>bienvenido a torqhub</p>
</body>
</html>