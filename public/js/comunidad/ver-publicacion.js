// manejar la visualización de respuestas a comentarios en la vista de publicación con ajax

document.addEventListener('DOMContentLoaded', function () {
    inicializarBotonesResponder();
    inicializarBotonesRespuestas();
    inicializarConfirmacionesComunidad();
});

// función para manejar la visualización del formulario de respuesta a comentarios
function inicializarBotonesResponder() {
    const botonesResponder = document.querySelectorAll('.boton-responder-comentario');

    botonesResponder.forEach(function (boton) {
        boton.addEventListener('click', function () {
            const comentarioId = boton.dataset.comentarioId;
            const usuario = boton.dataset.usuario;
            const contenedorFormulario = document.getElementById('formulario-respuesta-' + comentarioId);
            const textarea = document.getElementById('respuesta-' + comentarioId);

            if (!contenedorFormulario || !textarea) {
                return;
            }

            const estaOculto =
                contenedorFormulario.style.display === 'none' ||
                contenedorFormulario.style.display === '';

            document.querySelectorAll('[id^="formulario-respuesta-"]').forEach(function (elemento) {
                elemento.style.display = 'none';
            });

            if (estaOculto) {
                contenedorFormulario.style.display = 'block';

                if (textarea.value.trim() === '') {
                    textarea.value = '@' + usuario + ' ';
                }

                textarea.focus();
                textarea.setSelectionRange(textarea.value.length, textarea.value.length);
            } else {
                contenedorFormulario.style.display = 'none';
            }
        });
    });
}

// función para manejar la visualización de respuestas a comentarios con carga mediante AJAX
function inicializarBotonesRespuestas() {
    const botonesToggle = document.querySelectorAll('.boton-toggle-respuestas');

    botonesToggle.forEach(function (boton) {
        boton.addEventListener('click', async function () {
            const publicacionId = boton.dataset.publicacionId;
            const comentarioId = boton.dataset.comentarioId;
            const totalRespuestas = parseInt(boton.dataset.totalRespuestas, 10);
            const urlBase = boton.dataset.url;

            const contenedor = document.getElementById('respuestas-comentario-' + comentarioId);

            if (!contenedor) {
                return;
            }

            const estaVisible = contenedor.style.display === 'block';

            if (estaVisible) {
                contenedor.style.display = 'none';
                boton.textContent = construirTextoVer(totalRespuestas, boton);
                return;
            }

            if (contenedor.dataset.cargadas === '1') {
                contenedor.style.display = 'block';
                boton.textContent = boton.dataset.textoOcultar || 'Ocultar respuestas';
                return;
            }

            contenedor.style.display = 'block';
            reemplazarContenidoTexto(contenedor, boton.dataset.textoCargando || 'Cargando respuestas...');

            const url = urlBase
                + '?publicacion_id=' + encodeURIComponent(publicacionId)
                + '&comentario_id=' + encodeURIComponent(comentarioId);

            try {
                const respuesta = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const datos = await respuesta.json();

                if (!respuesta.ok || !datos.ok) {
                    reemplazarContenidoTexto(contenedor, datos.mensaje || boton.dataset.textoError || 'No se pudieron cargar las respuestas');
                    return;
                }

                renderizarRespuestasEnContenedor(contenedor, datos.respuestas, boton);
                contenedor.dataset.cargadas = '1';
                boton.textContent = boton.dataset.textoOcultar || 'Ocultar respuestas';
            } catch (error) {
                reemplazarContenidoTexto(contenedor, boton.dataset.textoError || 'No se pudieron cargar las respuestas');
            }
        });
    });
}

// función para mostrar confirmaciones antes de eliminar publicaciones o comentarios
function inicializarConfirmacionesComunidad() {
    document.addEventListener('submit', function (evento) {
        const formulario = evento.target.closest('.form-eliminar-publicacion, .form-eliminar-comentario');

        if (!formulario) {
            return;
        }

        const mensaje = formulario.dataset.confirmacion || '¿Seguro que quieres eliminar?';

        if (!confirm(mensaje)) {
            evento.preventDefault();
        }
    });
}

// función para construir el texto del botón "Ver respuestas" dependiendo del número de respuestas
function construirTextoVer(totalRespuestas, boton) {
    if (totalRespuestas === 1) {
        return boton.dataset.textoVerUna || 'Ver 1 respuesta';
    }

    const texto = boton.dataset.textoVerVarias || 'Ver {total} respuestas';

    return texto.replace('{total}', totalRespuestas);
}

// función para reemplazar el contenido del contenedor con un mensaje de texto simple
function reemplazarContenidoTexto(contenedor, texto) {
    contenedor.replaceChildren();

    const parrafo = document.createElement('p');
    parrafo.textContent = texto;

    contenedor.appendChild(parrafo);
}

// función para renderizar respuestas en el contenedor correspondiente
function renderizarRespuestasEnContenedor(contenedor, respuestas, boton) {
    contenedor.replaceChildren();

    if (!Array.isArray(respuestas) || respuestas.length === 0) {
        reemplazarContenidoTexto(contenedor, boton.dataset.textoSinRespuestas || 'No hay respuestas');
        return;
    }

    respuestas.forEach(function (respuesta) {
        const articulo = document.createElement('article');
        articulo.classList.add('comunidad-respuesta');

        const cabecera = document.createElement('p');

        const fuerte = document.createElement('strong');
        const enlace = document.createElement('a');

        const autor = String(respuesta.autor_nombre || '');
        const urlPerfil = boton.dataset.urlPerfil || '/perfil';

        enlace.href = urlPerfil + '?usuario=' + encodeURIComponent(autor);
        enlace.textContent = '@' + autor;

        fuerte.appendChild(enlace);

        cabecera.appendChild(fuerte);
        cabecera.appendChild(document.createTextNode(' · ' + String(respuesta.fecha_creacion || '')));

        const contenido = document.createElement('p');
        contenido.textContent = String(respuesta.contenido || '');
        contenido.classList.add('comunidad-respuesta__contenido');

        articulo.appendChild(cabecera);
        articulo.appendChild(contenido);

        contenedor.appendChild(articulo);
    });
}