<?php
$usuario_sesion = $_SESSION['usuario'] ?? null;
$usuario_autenticado = isset($usuario_sesion);
$usuario_admin = $usuario_autenticado && (($usuario_sesion['rol'] ?? '') === 'admin');
$nombre_usuario = $usuario_sesion['nombre'] ?? '';
?>

<header class="barra-superior">
    <nav class="navbar" aria-label="<?= htmlspecialchars(t('navbar.navegacion_principal')) ?>">
        <div class="navbar__contenedor">
            <a href="<?= escapar(url('/')) ?>" class="navbar__marca">
                TorqHub
            </a>

            <button
                type="button"
                class="navbar__boton-menu"
                aria-controls="navbar-menu-principal"
                aria-expanded="false"
                aria-label="<?= htmlspecialchars(t('navbar.abrir_menu')) ?>"
                data-navbar-boton
                data-texto-abrir="<?= htmlspecialchars(t('navbar.abrir_menu')) ?>"
                data-texto-cerrar="<?= htmlspecialchars(t('navbar.cerrar_menu')) ?>">
                <span class="navbar__boton-menu-linea"></span>
                <span class="navbar__boton-menu-linea"></span>
                <span class="navbar__boton-menu-linea"></span>
            </button>

            <div
                id="navbar-menu-principal"
                class="navbar__menu"
                data-navbar-menu>
                <div class="navbar__grupo navbar__grupo--principal">
                    <a href="<?= escapar(url('/')) ?>" class="navbar__enlace">
                        <?= htmlspecialchars(t('navbar.inicio')) ?>
                    </a>

                    <?php if (!$usuario_autenticado): ?>
                        <a href="<?= escapar(url('/login')) ?>" class="navbar__enlace">
                            <?= htmlspecialchars(t('navbar.login')) ?>
                        </a>

                        <a href="<?= escapar(url('/registro')) ?>" class="navbar__enlace">
                            <?= htmlspecialchars(t('navbar.registro')) ?>
                        </a>
                    <?php else: ?>
                        <a href="<?= escapar(url('/garaje')) ?>" class="navbar__enlace">
                            <?= htmlspecialchars(t('navbar.garaje')) ?>
                        </a>

                        <a href="<?= escapar(url('/comunidad')) ?>" class="navbar__enlace">
                            <?= htmlspecialchars(t('navbar.comunidad')) ?>
                        </a>

                        <a href="<?= escapar(url('/diagnostico')) ?>" class="navbar__enlace">
                            <?= htmlspecialchars(t('navbar.diagnostico')) ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="navbar__grupo navbar__grupo--usuario">
                    <?php if ($usuario_autenticado): ?>
                        <?php if ($usuario_admin): ?>
                            <a href="<?= escapar(url('/admin')) ?>" class="navbar__enlace navbar__enlace--admin">
                                <?= htmlspecialchars(t('navbar.admin')) ?>
                            </a>
                        <?php endif; ?>

                        <a
                            href="<?= escapar(url('/perfil?usuario=' . urlencode($nombre_usuario))) ?>"
                            class="navbar__enlace">
                            <?= htmlspecialchars(t('navbar.perfil')) ?>
                        </a>

                        <form method="POST" action="<?= escapar(url('/logout')) ?>" class="navbar__logout">
                            <?= csrf_campo() ?>

                            <button type="submit" class="navbar__logout-boton">
                                <?= htmlspecialchars(t('navbar.logout')) ?>
                            </button>
                        </form>
                    <?php endif; ?>

                    <form
                        method="post"
                        action="<?= escapar(url('/idioma')) ?>"
                        class="navbar__idioma"
                        aria-label="<?= htmlspecialchars(t('navbar.cambiar_idioma')) ?>">
                        <?= csrf_campo() ?>

                        <input
                            type="hidden"
                            name="volver"
                            value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? url('/')) ?>">

                        <button
                            type="submit"
                            name="idioma"
                            value="es"
                            class="navbar__idioma-boton <?= idioma_actual() === 'es' ? 'navbar__idioma-boton--activo' : '' ?>">
                            <?= htmlspecialchars(t('navbar.idioma_es')) ?>
                        </button>

                        <button
                            type="submit"
                            name="idioma"
                            value="ca"
                            class="navbar__idioma-boton <?= idioma_actual() === 'ca' ? 'navbar__idioma-boton--activo' : '' ?>">
                            <?= htmlspecialchars(t('navbar.idioma_ca')) ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>