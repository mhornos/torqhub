document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('form-filtros-mantenimientos');
    const contenedorTabla = document.getElementById('contenedor-tabla-mantenimientos');
    const botonLimpiar = document.getElementById('btn-limpiar-filtros');

    if (!formulario || !contenedorTabla) {
        return;
    }

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
            contenedorTabla.innerHTML = html;
        } catch (error) {
            contenedorTabla.innerHTML = '<p>no se pudo actualizar el historial.</p>';
        }
    };

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();
        actualizarTabla();
    });

    if (botonLimpiar) {
        botonLimpiar.addEventListener('click', function () {
            formulario.reset();

            const campoId = formulario.querySelector('input[name="id"]');
            const campoVehiculoId = formulario.querySelector('input[name="vehiculo_id"]');

            if (campoId) {
                campoId.value = campoVehiculoId ? campoVehiculoId.value : campoId.value;
            }

            actualizarTabla();
        });
    }
});