//manejar los likes con ajax en el listado de publicaciones de la comunidad

document.addEventListener('DOMContentLoaded', function () {
    const formularios = document.querySelectorAll('.formulario-like-publicacion-listado');

    formularios.forEach(function (formulario) {
        formulario.addEventListener('submit', async function (evento) {
            evento.preventDefault();

            const boton = formulario.querySelector('.boton-like-publicacion-listado');
            const campoPublicacionId = formulario.querySelector('input[name="publicacion_id"]');

            if (!boton || !campoPublicacionId) {
                return;
            }

            const publicacionId = campoPublicacionId.value;
            const textoTotalLikes = document.querySelector(
                '.texto-total-likes-publicacion-listado[data-publicacion-id="' + publicacionId + '"]'
            );
            const mensaje = document.querySelector(
                '.mensaje-like-publicacion-listado[data-publicacion-id="' + publicacionId + '"]'
            );

            if (!textoTotalLikes || !mensaje) {
                return;
            }

            const url = formulario.dataset.url;
            const datosFormulario = new FormData(formulario);

            boton.disabled = true;
            mensaje.style.display = 'none';

            try {
                const respuesta = await fetch(url, {
                    method: 'POST',
                    body: datosFormulario,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const datos = await respuesta.json();

                if (!respuesta.ok || !datos.ok) {
                    mensaje.textContent = datos.mensaje || 'No se pudo actualizar el like';
                    mensaje.style.display = 'block';
                    boton.disabled = false;
                    return;
                }

                boton.textContent = datos.texto_boton;
                textoTotalLikes.textContent = construirTextoLikes(datos.total_likes);

                /* if (datos.accion === 'añadido') {
                    mensaje.textContent = 'Like añadido correctamente';
                } else {
                    mensaje.textContent = 'Like eliminado correctamente';
                } */

                mensaje.style.display = 'block';
            } catch (error) {
                mensaje.textContent = 'No se pudo actualizar el like';
                mensaje.style.display = 'block';
            } finally {
                boton.disabled = false;
            }
        });
    });

    function construirTextoLikes(totalLikes) {
        if (totalLikes === 1) {
            return '1 like';
        }

        return totalLikes + ' likes';
    }
    
});