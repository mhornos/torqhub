// manejar los likes con ajax en el listado de publicaciones de la comunidad

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('submit', async function (evento) {
        const formulario = evento.target.closest('.formulario-like-publicacion-listado');

        if (!formulario) {
            return;
        }

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

            mensaje.style.display = 'block';
        } catch (error) {
            mensaje.textContent = 'No se pudo actualizar el like';
            mensaje.style.display = 'block';
        } finally {
            boton.disabled = false;
        }
    });

    function construirTextoLikes(totalLikes) {
        totalLikes = Number(totalLikes);

        if (totalLikes === 1) {
            return '1 like';
        }

        return totalLikes + ' likes';
    }
});