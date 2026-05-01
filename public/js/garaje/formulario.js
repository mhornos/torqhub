//lógica de validación de los formularios de garaje

document.addEventListener('DOMContentLoaded', function () {
    const formularios = document.querySelectorAll('.formulario-garaje-validado');

    if (!formularios.length) {
        return;
    }

    const etiquetasCampos = {
        marca: 'campo_marca',
        modelo: 'campo_modelo',
        any: 'campo_any',
        vin: 'campo_vin',
        carroceria: 'campo_carroceria',
        tipo_combustible: 'campo_tipo_combustible',
        tipo_cambio: 'campo_tipo_cambio',
        potencia_cv: 'campo_potencia_cv',
        cilindrada_cm3: 'campo_cilindrada_cm3',
        imagen: 'campo_imagen',
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


function obtenerTraduccionesGaraje(elemento) {
    const formulario = elemento.closest('form');

    if (!formulario || !formulario.dataset.traduccionesGaraje) {
        return {};
    }

    try {
        return JSON.parse(formulario.dataset.traduccionesGaraje);
    } catch (error) {
        return {};
    }
}

function traducirGaraje(elemento, clave, reemplazos = {}) {
    const traducciones = obtenerTraduccionesGaraje(elemento);
    let texto = traducciones[clave] || clave;

    Object.keys(reemplazos).forEach(function (nombre) {
        texto = texto.replace(`{${nombre}}`, reemplazos[nombre]);
    });

    return texto;
}


function validarCampo(campo, etiquetasCampos) {
    campo.dataset.tocado = '1';

    const contenedor = campo.parentElement;
    const nombreCampo = campo.name || 'campo';
    const claveEtiqueta = etiquetasCampos[nombreCampo] || 'campo_este_campo';
    const etiqueta = traducirGaraje(campo, claveEtiqueta);
    let mensajeError = '';

    if (campo.type === 'file') {
        mensajeError = validarArchivoImagen(campo);
    } else {
        const valor = campo.value.trim();

        if (campo.hasAttribute('required') && valor === '') {
            mensajeError = traducirGaraje(campo, 'error_falta', { campo: etiqueta });
        } else if (nombreCampo === 'any' && valor !== '') {
            const anyo = Number(valor);
            const minimo = Number(campo.getAttribute('min') || 1900);
            const maximo = Number(campo.getAttribute('max') || new Date().getFullYear());

            if (!Number.isInteger(anyo) || anyo < minimo || anyo > maximo) {
                mensajeError = traducirGaraje(campo, 'error_any_rango', {
                    minimo: minimo,
                    maximo: maximo,
                });
            }
        } else if (nombreCampo === 'vin' && valor !== '') {
            const regexVin = /^[A-HJ-NPR-Z0-9]{17}$/i;

            // if (!regexVin.test(valor)) {
            //     mensajeError = 'El VIN debe tener 17 caracteres y no puede contener i, o ni q';
            // }
        } else if (nombreCampo === 'potencia_cv' && valor !== '') {
            const potencia = Number(valor);
            if (!Number.isInteger(potencia) || potencia < 0) {
                mensajeError = traducirGaraje(campo, 'error_potencia_entero');
            }
        } else if (nombreCampo === 'cilindrada_cm3' && valor !== '') {
            const cilindrada = Number(valor);
            if (!Number.isInteger(cilindrada) || cilindrada < 0) {
                mensajeError = traducirGaraje(campo, 'error_cilindrada_entero');
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
        return traducirGaraje(campo, 'error_imagen_tipo');
    }

    if (archivo.size > tamanyoMaximo) {
        return traducirGaraje(campo, 'error_imagen_tamanyo');
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