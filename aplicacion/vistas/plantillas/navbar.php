<nav>
    <a href="<?= url('/') ?>"><?= htmlspecialchars(t('navbar.inicio')) ?></a>

    <?php if (!isset($_SESSION['usuario'])): ?>
        <a href="<?= url('/login') ?>"><?= htmlspecialchars(t('navbar.login')) ?></a>
        <a href="<?= url('/registro') ?>"><?= htmlspecialchars(t('navbar.registro')) ?></a>
    <?php else: ?>
        <a href="<?= url('/garaje') ?>"><?= htmlspecialchars(t('navbar.garaje')) ?></a>
        <a href="<?= url('/comunidad') ?>"><?= htmlspecialchars(t('navbar.comunidad')) ?></a>
        <a href="<?= url('/diagnostico') ?>"><?= htmlspecialchars(t('navbar.diagnostico')) ?></a>
        <a href="<?= url('/perfil?usuario=' . $_SESSION['usuario']['nombre']) ?>"><?= htmlspecialchars(t('navbar.perfil')) ?></a>

        <?php if (($_SESSION['usuario']['rol'] ?? '') === 'admin'): ?>
            <a href="<?= url('/admin') ?>"><?= htmlspecialchars(t('navbar.admin')) ?></a>
        <?php endif; ?>
        
        <a href="<?= url('/logout') ?>"><?= htmlspecialchars(t('navbar.logout')) ?></a>
    <?php endif; ?>

    <form 
        method="post" 
        action="<?= url('/idioma') ?>" 
        class="formulario-idioma" 
        aria-label="<?= htmlspecialchars(t('navbar.cambiar_idioma')) ?>"
    >
        <?= csrf_campo() ?>

        <input 
            type="hidden" 
            name="volver" 
            value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? url('/')) ?>"
        >

        <button 
            type="submit" 
            name="idioma" 
            value="es" 
            class="boton-idioma <?= idioma_actual() === 'es' ? 'activo' : '' ?>"
        >
            <?= htmlspecialchars(t('navbar.idioma_es')) ?>
        </button>

        <button 
            type="submit" 
            name="idioma" 
            value="ca" 
            class="boton-idioma <?= idioma_actual() === 'ca' ? 'activo' : '' ?>"
        >
            <?= htmlspecialchars(t('navbar.idioma_ca')) ?>
        </button>
    </form>
</nav>