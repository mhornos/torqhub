<nav>
    <a href="<?= url('/') ?>">Inicio</a>

    <?php if (!isset($_SESSION['usuario'])): ?>
        <a href="<?= url('/login') ?>">Iniciar sesión</a>
        <a href="<?= url('/registro') ?>">Registrarse</a>
    <?php else: ?>
        <a href="<?= url('/garaje') ?>">Mi garaje</a>
        <a href="<?= url('/comunidad') ?>">Comunidad</a>

        <?php if (($_SESSION['usuario']['rol'] ?? '') === 'admin'): ?>
            <a href="<?= url('/admin') ?>">Panel de administración</a>
        <?php endif; ?>
        
        <a href="<?= url('/logout') ?>">Cerrar sesión</a>
    <?php endif; ?>
    
</nav>
