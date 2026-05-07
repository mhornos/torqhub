// manejar búsqueda, ordenación y paginación ajax de publicaciones

document.addEventListener('DOMContentLoaded', () => {
    const formularioFiltros = document.querySelector('.formulario-filtros-comunidad');

    if (!formularioFiltros) {
        return;
    }

    const campoBusqueda = formularioFiltros.querySelector('#busqueda');
    const selectorOrden = formularioFiltros.querySelector('#orden');
    const enlaceLimpiar = formularioFiltros.querySelector('.enlace-limpiar-filtros');

    let temporizadorBusqueda = null;

    const obtenerContenedorResultado = () => {
        return document.querySelector('#resultado-publicaciones-comunidad');
    };

    const actualizarBotonLimpiar = () => {
        if (!enlaceLimpiar) {
            return;
        }

        const hayBusqueda = campoBusqueda && campoBusqueda.value.trim() !== '';
        const hayOrdenDistinto = selectorOrden && selectorOrden.value !== 'recientes';

        if (hayBusqueda || hayOrdenDistinto) {
            enlaceLimpiar.classList.remove('enlace-limpiar-filtros--oculto');
            return;
        }

        enlaceLimpiar.classList.add('enlace-limpiar-filtros--oculto');
    };

    const construirUrlFiltros = () => {
        const parametros = new URLSearchParams();

        const busqueda = campoBusqueda ? campoBusqueda.value.trim() : '';
        const orden = selectorOrden ? selectorOrden.value : 'recientes';

        if (busqueda !== '') {
            parametros.set('busqueda', busqueda);
        }

        if (orden !== 'recientes') {
            parametros.set('orden', orden);
        }

        parametros.set('pagina', '1');

        return `${formularioFiltros.action}?${parametros.toString()}`;
    };

    const cargarPublicaciones = async (url) => {
        const contenedorResultado = obtenerContenedorResultado();

        if (!contenedorResultado) {
            window.location.href = url;
            return;
        }

        try {
            const respuesta = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!respuesta.ok) {
                window.location.href = url;
                return;
            }

            const html = await respuesta.text();

            contenedorResultado.outerHTML = html;
            window.history.pushState({}, '', url);
            actualizarBotonLimpiar();
        } catch (error) {
            window.location.href = url;
        }
    };

    const aplicarFiltros = () => {
        cargarPublicaciones(construirUrlFiltros());
    };

    formularioFiltros.addEventListener('submit', (evento) => {
        evento.preventDefault();
        aplicarFiltros();
    });

    if (campoBusqueda) {
        campoBusqueda.addEventListener('input', () => {
            actualizarBotonLimpiar();

            clearTimeout(temporizadorBusqueda);

            temporizadorBusqueda = setTimeout(() => {
                aplicarFiltros();
            }, 350);
        });
    }

    if (selectorOrden) {
        selectorOrden.addEventListener('change', () => {
            actualizarBotonLimpiar();
            aplicarFiltros();
        });
    }

    document.addEventListener('click', (evento) => {
        const enlaceLimpiarPulsado = evento.target.closest('.enlace-limpiar-filtros');

        if (enlaceLimpiarPulsado) {
            evento.preventDefault();

            if (campoBusqueda) {
                campoBusqueda.value = '';
            }

            if (selectorOrden) {
                selectorOrden.value = 'recientes';
            }

            actualizarBotonLimpiar();
            cargarPublicaciones(enlaceLimpiarPulsado.href);
            return;
        }

        const enlacePaginacion = evento.target.closest('#contenedor-paginacion-comunidad a');

        if (!enlacePaginacion) {
            return;
        }

        evento.preventDefault();

        cargarPublicaciones(enlacePaginacion.href);
    });

    window.addEventListener('popstate', () => {
        cargarPublicaciones(window.location.href);
    });

    actualizarBotonLimpiar();
});