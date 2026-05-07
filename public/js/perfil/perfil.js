document.addEventListener('DOMContentLoaded', () => {
    const botones = document.querySelectorAll('[data-perfil-boton]');
    const panelGaraje = document.querySelector('[data-perfil-panel="garaje"]');
    const panelPublicaciones = document.querySelector('[data-perfil-panel="publicaciones"]');
    const panelConfiguracion = document.querySelector('[data-perfil-panel="configuracion"]');

    const botonPublicaciones = document.querySelector('[data-perfil-boton="publicaciones"]');
    const botonConfiguracion = document.querySelector('[data-perfil-boton="configuracion"]');
    const botonAtras = document.querySelector('[data-perfil-boton="atras"]');

    if (!botones.length || !panelGaraje) {
        return;
    }

    const mostrar = (elemento) => {
        if (elemento) {
            elemento.hidden = false;
        }
    };

    const ocultar = (elemento) => {
        if (elemento) {
            elemento.hidden = true;
        }
    };

    const activarBoton = (botonActivo) => {
        botones.forEach((boton) => {
            boton.classList.remove('perfil-boton-control--activo');
        });

        if (botonActivo) {
            botonActivo.classList.add('perfil-boton-control--activo');
        }
    };

    const mostrarInicio = () => {
        mostrar(panelGaraje);
        ocultar(panelPublicaciones);
        ocultar(panelConfiguracion);

        mostrar(botonPublicaciones);
        mostrar(botonConfiguracion);
        ocultar(botonAtras);

        activarBoton(null);
    };

    const mostrarPublicaciones = () => {
        mostrar(panelPublicaciones);
        mostrar(panelGaraje);
        ocultar(panelConfiguracion);

        ocultar(botonPublicaciones);
        mostrar(botonConfiguracion);
        mostrar(botonAtras);

        activarBoton(botonPublicaciones);
    };

    const mostrarConfiguracion = () => {
        ocultar(panelPublicaciones);
        ocultar(panelGaraje);
        mostrar(panelConfiguracion);

        mostrar(botonPublicaciones);
        ocultar(botonConfiguracion);
        mostrar(botonAtras);

        activarBoton(botonConfiguracion);
    };

    botones.forEach((boton) => {
        boton.addEventListener('click', () => {
            const accion = boton.dataset.perfilBoton;

            if (accion === 'publicaciones') {
                mostrarPublicaciones();
                return;
            }

            if (accion === 'configuracion') {
                mostrarConfiguracion();
                return;
            }

            if (accion === 'atras') {
                mostrarInicio();
            }
        });
    });

    mostrarInicio();
});