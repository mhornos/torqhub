// manejar los filtros y la paginación de las publicaciones en la comunidad

document.addEventListener('DOMContentLoaded', () => {
    const formularioFiltros = document.querySelector('.formulario-filtros-comunidad');
    const contenedorResultado = document.querySelector('#resultado-publicaciones-comunidad');

    if (!formularioFiltros || !contenedorResultado) {
        return;
    }

    const cargarPublicaciones = async (url) => {
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
        } catch (error) {
            window.location.href = url;
        }
    };

    formularioFiltros.addEventListener('submit', (evento) => {
        evento.preventDefault();

        const datosFormulario = new FormData(formularioFiltros);
        datosFormulario.set('pagina', '1');

        const parametros = new URLSearchParams(datosFormulario);
        const url = `${formularioFiltros.action}?${parametros.toString()}`;

        cargarPublicaciones(url);
    });

    document.addEventListener('click', (evento) => {
        const enlaceLimpiar = evento.target.closest('.enlace-limpiar-filtros');

        if (enlaceLimpiar) {
            evento.preventDefault();

            formularioFiltros.reset();

            cargarPublicaciones(enlaceLimpiar.href);
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
});