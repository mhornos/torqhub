//manejar la actualización dinámica del historial de mantenimientos en la vista de un vehículo con filtros y paginación mediante ajax

document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('form-filtros-mantenimientos');
    const contenedorHistorial = document.getElementById('contenedor-historial-mantenimientos');
    const botonLimpiar = document.getElementById('btn-limpiar-filtros');

    if (!formulario || !contenedorHistorial) {
        return;
    }

//función para actualizar la tabla de mantenimientos mediante AJAX
    const actualizarTabla = async function () {
        const urlAjax = formulario.dataset.urlAjax;
        const datosFormulario = new FormData(formulario);
        const parametros = new URLSearchParams(datosFormulario);

        try {
            const respuesta = await fetch(urlAjax + '?' + parametros.toString(), {
                headers: {
                    'X-Requested-With': 'fetch'
                }
            });

            const html = await respuesta.text();
            contenedorHistorial.innerHTML = html;
        } catch (error) {
            contenedorHistorial.innerHTML = '<p>no se pudo actualizar el historial.</p>';
        }
    };

//función para cambiar la página de la paginación
    const cambiarPagina = function (pagina) {
        const campoPagina = document.getElementById('pagina-historial');

        if (!campoPagina) {
            return;
        }

        campoPagina.value = pagina;
        actualizarTabla();
    };
    
    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        const campoPagina = document.getElementById('pagina-historial');
        if (campoPagina) {
            campoPagina.value = 1;
        }

        actualizarTabla();
    });

//agregar eventos a los campos de filtro para actualizar automáticamente al cambiar
    const camposAutoActualizar = formulario.querySelectorAll('select');

    camposAutoActualizar.forEach(function (campo) {
        campo.addEventListener('change', function () {
            const campoPagina = document.getElementById('pagina-historial');

            if (campoPagina) {
                campoPagina.value = 1;
            }

            actualizarTabla();
        });
    });

    if (botonLimpiar) {
        botonLimpiar.addEventListener('click', function () {
            formulario.reset();

            const campoId = formulario.querySelector('input[name="id"]');
            const campoVehiculoId = formulario.querySelector('input[name="vehiculo_id"]');

            if (campoId) {
                campoId.value = campoVehiculoId ? campoVehiculoId.value : campoId.value;
            }

            const campoPagina = document.getElementById('pagina-historial');
            if (campoPagina) {
                campoPagina.value = 1;
            }

            actualizarTabla();
        });
    }

//delegar el evento de clic en los botones de paginación
    document.addEventListener('click', function (evento) {
        const botonPaginacion = evento.target.closest('.btn-paginacion');

        if (!botonPaginacion) {
            return;
        }

        const pagina = botonPaginacion.dataset.pagina;

        if (!pagina) {
            return;
        }

        cambiarPagina(pagina);
    });

});