// este archivo contiene la lógica de validación de los formularios de garaje
document.addEventListener('DOMContentLoaded', function () {
    const formularios = document.querySelectorAll('.formulario-garaje-validado');

    if (!formularios.length) {
        return;
    }

    const etiquetasCampos = {
        marca: 'la marca',
        modelo: 'el modelo',
        any: 'el año',
        vin: 'el vin',
        carroceria: 'la carroceria',
        tipo_combustible: 'el tipo de combustible',
        tipo_cambio: 'el tipo de cambio',
        potencia_cv: 'la potencia',
        cilindrada_cm3: 'la cilindrada',
        imagen: 'la imagen',
    };

    formularios.forEach(function (formulario) {
        const campos = formulario.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="button"]), select');

        campos.forEach(function (campo) {
            campo.addEventListener('blur', function () {
                validarCampo(campo, etiquetasCampos);
            });

            campo.addEventListener('input', function () {
                if (campo.dataset.tocado === '1') {
                    validarCampo(campo, etiquetasCampos);
                }
            });

            campo.addEventListener('change', function () {
                validarCampo(campo, etiquetasCampos);
            });
        });

        formulario.addEventListener('submit', function (evento) {
            let formularioValido = true;

            campos.forEach(function (campo) {
                const esValido = validarCampo(campo, etiquetasCampos);
                if (!esValido) {
                    formularioValido = false;
                }
            });

            if (!formularioValido) {
                evento.preventDefault();

                const primerCampoConError = formulario.querySelector('.campo-con-error input, .campo-con-error select');
                if (primerCampoConError) {
                    primerCampoConError.focus();
                }
            }
        });
    });
});

function validarCampo(campo, etiquetasCampos) {
    campo.dataset.tocado = '1';

    const contenedor = campo.parentElement;
    const nombreCampo = campo.name || 'campo';
    const etiqueta = etiquetasCampos[nombreCampo] || 'este campo';
    let mensajeError = '';

    if (campo.type === 'file') {
        mensajeError = validarArchivoImagen(campo);
    } else {
        const valor = campo.value.trim();

        if (campo.hasAttribute('required') && valor === '') {
            mensajeError = `Falta ${etiqueta}`;
        } else if (nombreCampo === 'any' && valor !== '') {
            const anyo = Number(valor);
            if (!Number.isInteger(anyo) || anyo < 1900 || anyo > 2026) {
                mensajeError = 'El año debe estar entre 1900 y 2026';
            }
        } else if (nombreCampo === 'vin' && valor !== '') {
            const regexVin = /^[A-HJ-NPR-Z0-9]+$/i;

            if (valor.length < 6 || valor.length > 25) {
                mensajeError = 'El VIN debe tener entre 6 y 25 caracteres';
            } else if (!regexVin.test(valor)) {
                mensajeError = 'El VIN solo puede contener letras y números validos';
            }
        } else if (nombreCampo === 'potencia_cv' && valor !== '') {
            const potencia = Number(valor);
            if (!Number.isInteger(potencia) || potencia < 0) {
                mensajeError = 'La potencia debe ser un número entero positivo';
            }
        } else if (nombreCampo === 'cilindrada_cm3' && valor !== '') {
            const cilindrada = Number(valor);
            if (!Number.isInteger(cilindrada) || cilindrada < 0) {
                mensajeError = 'La cilindrada debe ser un número entero positivo';
            }
        }
    }

    pintarEstadoCampo(contenedor, campo, mensajeError);

    return mensajeError === '';
}

function validarArchivoImagen(campo) {
    if (!campo.files || !campo.files.length) {
        return '';
    }

    const archivo = campo.files[0];
    const tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
    const tamanyoMaximo = 3 * 1024 * 1024;

    if (!tiposPermitidos.includes(archivo.type)) {
        return 'Solo se permiten imagenes jpg, png o webp';
    }

    if (archivo.size > tamanyoMaximo) {
        return 'La imagen no puede superar los 3 mb';
    }

    return '';
}

function pintarEstadoCampo(contenedor, campo, mensajeError) {
    contenedor.classList.remove('campo-con-error');
    contenedor.classList.remove('campo-valido');

    let mensaje = contenedor.querySelector('.mensaje-error-campo');

    if (!mensaje) {
        mensaje = document.createElement('small');
        mensaje.className = 'mensaje-error-campo';
        contenedor.appendChild(mensaje);
    }

    if (mensajeError !== '') {
        contenedor.classList.add('campo-con-error');
        mensaje.textContent = mensajeError;
        mensaje.style.display = 'block';
    } else {
        mensaje.textContent = '';
        mensaje.style.display = 'none';

        if (campo.value.trim() !== '' || (campo.type === 'file' && campo.files && campo.files.length)) {
            contenedor.classList.add('campo-valido');
        }
    }
}