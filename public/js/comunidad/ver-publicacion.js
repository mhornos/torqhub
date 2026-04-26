//manejar la visualización de respuestas a comentarios en la vista de publicación con ajax

document.addEventListener('DOMContentLoaded', function () {
    inicializarBotonesResponder();
    inicializarBotonesRespuestas();
});

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
                boton.textContent = construirTextoVer(totalRespuestas);
                return;
            }

            if (contenedor.dataset.cargadas === '1') {
                contenedor.style.display = 'block';
                boton.textContent = 'Ocultar respuestas';
                return;
            }

            contenedor.style.display = 'block';
            contenedor.innerHTML = '<p>Cargando respuestas...</p>';

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
                    contenedor.innerHTML = '<p>No se pudieron cargar las respuestas</p>';
                    return;
                }

                contenedor.innerHTML = renderizarRespuestas(datos.respuestas);
                contenedor.dataset.cargadas = '1';
                boton.textContent = 'Ocultar respuestas';
            } catch (error) {
                contenedor.innerHTML = '<p>No se pudieron cargar las respuestas</p>';
            }
        });
    });
}

function construirTextoVer(totalRespuestas) {
    if (totalRespuestas === 1) {
        return 'Ver 1 respuesta';
    }

    return 'Ver ' + totalRespuestas + ' respuestas';
}

function renderizarRespuestas(respuestas) {
    if (!Array.isArray(respuestas) || respuestas.length === 0) {
        return '<p>No hay respuestas</p>';
    }

    let html = '';

    respuestas.forEach(function (respuesta) {
        html += ''
            + '<article style="margin-bottom: 15px; padding-left: 15px; border-left: 3px solid #b8b8b8;">'
            + '    <p><strong><a href="/torqhub/perfil?usuario=' + encodeURIComponent(respuesta.autor_nombre) + '">@' + escaparHtml(respuesta.autor_nombre) + '</a></strong> · ' + escaparHtml(respuesta.fecha_creacion) + '</p>'
            + '    <p>' + convertirSaltosLinea(escaparHtml(respuesta.contenido)) + '</p>'
            + '</article>';
    });

    return html;
}

function escaparHtml(texto) {
    const div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
}

function convertirSaltosLinea(texto) {
    return texto.replace(/\n/g, '<br>');
}