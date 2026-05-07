document.addEventListener('DOMContentLoaded', () => {
    const botonMenu = document.querySelector('[data-navbar-boton]');
    const menu = document.querySelector('[data-navbar-menu]');

    if (!botonMenu || !menu) {
        return;
    }

    const textoAbrir = botonMenu.dataset.textoAbrir || 'Abrir menú';
    const textoCerrar = botonMenu.dataset.textoCerrar || 'Cerrar menú';

    const cerrarMenu = () => {
        menu.classList.remove('navbar__menu--abierto');
        botonMenu.setAttribute('aria-expanded', 'false');
        botonMenu.setAttribute('aria-label', textoAbrir);
    };

    const abrirMenu = () => {
        menu.classList.add('navbar__menu--abierto');
        botonMenu.setAttribute('aria-expanded', 'true');
        botonMenu.setAttribute('aria-label', textoCerrar);
    };

    botonMenu.addEventListener('click', () => {
        const estaAbierto = menu.classList.contains('navbar__menu--abierto');

        if (estaAbierto) {
            cerrarMenu();
            return;
        }

        abrirMenu();
    });

    menu.querySelectorAll('a').forEach((enlace) => {
        enlace.addEventListener('click', () => {
            cerrarMenu();
        });
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape') {
            cerrarMenu();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 760) {
            cerrarMenu();
        }
    });
});