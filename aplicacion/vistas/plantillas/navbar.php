<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">

</head>
<body>

<nav>
    <a href="<?= url('/') ?>">inicio</a>

    <?php if (!isset($_SESSION['usuario'])): ?>
        <a href="<?= url('/login') ?>">login</a>
        <a href="<?= url('/registro') ?>">registro</a>
    <?php else: ?>
        <span>hola, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></span>
        <a href="<?= url('/logout') ?>">logout</a>
    <?php endif; ?>
</nav>
