// consulta vin por ajax y autocompleta los campos del formulario de garaje

document.addEventListener('DOMContentLoaded', function () {
    const botonesVin = document.querySelectorAll('.boton-consultar-vin');

    if (!botonesVin.length) {
        return;
    }

    botonesVin.forEach(function (boton) {
        boton.addEventListener('click', function () {
            consultarVin(boton);
        });
    });
});

function traducirVin(elemento, clave, reemplazos = {}) {
    if (typeof traducirGaraje === 'function') {
        return traducirGaraje(elemento, clave, reemplazos);
    }

    return clave;
}

// función principal para consultar el VIN y autocompletar el formulario
async function consultarVin(boton) {
    const formulario = boton.closest('form');

    if (!formulario) {
        return;
    }

    const campoVin = formulario.querySelector('[name="vin"]');
    const campoCsrf = formulario.querySelector('[name="csrf_token"]');
    const mensaje = formulario.querySelector('.mensaje-vin');
    const urlConsultarVin = formulario.dataset.urlConsultarVin;

    if (!campoVin || !campoCsrf || !urlConsultarVin) {
        mostrarMensajeVin(mensaje, traducirVin(formulario, 'vin_no_preparar'), 'error');
        return;
    }

    const vin = campoVin.value.trim().toUpperCase();

    campoVin.value = vin;

    if (vin === '') {
        mostrarMensajeVin(mensaje, traducirVin(formulario, 'vin_introduce'), 'error');
        campoVin.focus();
        return;
    }

    if (!/^[A-HJ-NPR-Z0-9]{17}$/.test(vin)) {
        mostrarMensajeVin(mensaje, traducirVin(formulario, 'vin_formato'), 'error');
        campoVin.focus();
        return;
    }

    const datos = new FormData();
    datos.append('vin', vin);
    datos.append('csrf_token', campoCsrf.value);

    const textoOriginalBoton = boton.textContent.trim();

    boton.disabled = true;
    boton.textContent = traducirVin(formulario, 'vin_consultando_boton');
    mostrarMensajeVin(mensaje, traducirVin(formulario, 'vin_consultando_mensaje'), 'info');

    try {
        const respuesta = await fetch(urlConsultarVin, {
            method: 'POST',
            body: datos,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const textoRespuesta = await respuesta.text();
        let datosRespuesta = null;

        try {
            datosRespuesta = JSON.parse(textoRespuesta);
        } catch (error) {
            throw new Error(traducirVin(formulario, 'vin_respuesta_no_json'));
        }

        if (!respuesta.ok || !datosRespuesta.ok) {
            throw new Error(datosRespuesta.mensaje || traducirVin(formulario, 'vin_no_consultar'));
        }

        autocompletarFormularioVin(formulario, datosRespuesta.campos_torqhub);

        const origen = datosRespuesta.origen === 'cache'
            ? traducirVin(formulario, 'vin_origen_cache')
            : traducirVin(formulario, 'vin_origen_api');

        mostrarMensajeVin(
            mensaje,
            traducirVin(formulario, 'vin_ok_desde', { origen: origen }),
            'ok'
        );

    } catch (error) {
        mostrarMensajeVin(mensaje, error.message, 'error');

    } finally {
        boton.disabled = false;
        boton.textContent = textoOriginalBoton;
    }
}

// función para autocompletar los campos del formulario con los datos obtenidos del vin
function autocompletarFormularioVin(formulario, campos) {
    if (!campos) {
        return;
    }

    rellenarCampoFormulario(formulario, 'marca', campos.marca);
    rellenarCampoFormulario(formulario, 'modelo', campos.modelo);
    rellenarCampoFormulario(formulario, 'any', campos.any);
    rellenarCampoFormulario(formulario, 'carroceria', campos.carroceria);
    rellenarCampoFormulario(formulario, 'tipo_combustible', campos.tipo_combustible);
    rellenarCampoFormulario(formulario, 'tipo_cambio', campos.tipo_cambio);
    rellenarCampoFormulario(formulario, 'potencia_cv', campos.potencia_cv);
    rellenarCampoFormulario(formulario, 'cilindrada_cm3', campos.cilindrada_cm3);
}

// función para rellenar un campo del formulario y disparar los eventos necesarios para que se actualice la interfaz
function rellenarCampoFormulario(formulario, nombreCampo, valor) {
    if (valor === null || valor === undefined || valor === '') {
        return;
    }

    const campo = formulario.querySelector(`[name="${nombreCampo}"]`);

    if (!campo) {
        return;
    }

    if (campo.tagName === 'SELECT') {
        const opcionExiste = Array.from(campo.options).some(function (opcion) {
            return opcion.value === String(valor);
        });

        if (!opcionExiste) {
            return;
        }
    }

    campo.value = valor;

    campo.dispatchEvent(new Event('input', { bubbles: true }));
    campo.dispatchEvent(new Event('change', { bubbles: true }));
}

// función para mostrar un mensaje debajo del campo de vin con el resultado de la consulta
function mostrarMensajeVin(mensaje, texto, tipo) {
    if (!mensaje) {
        return;
    }

    mensaje.textContent = texto;
    mensaje.classList.remove('mensaje-vin--ok', 'mensaje-vin--error', 'mensaje-vin--info');
    mensaje.classList.add(`mensaje-vin--${tipo}`);
}