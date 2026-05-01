// manejar los likes con ajax del detalle de una publicación

document.addEventListener('DOMContentLoaded', function () {
    const formulario = document.getElementById('formulario-like-publicacion');

    if (!formulario) {
        return;
    }

    const boton = document.getElementById('boton-like-publicacion');
    const textoTotalLikes = document.getElementById('texto-total-likes-publicacion');
    const mensaje = document.getElementById('mensaje-like-publicacion');

    formulario.addEventListener('submit', async function (evento) {
        evento.preventDefault();

        if (!boton || !textoTotalLikes || !mensaje) {
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
                mensaje.textContent = datos.mensaje || formulario.dataset.errorLike || 'No se pudo actualizar el like';
                mensaje.style.display = 'block';
                boton.disabled = false;
                return;
            }

            boton.textContent = datos.texto_boton;
            textoTotalLikes.textContent = construirTextoLikes(datos.total_likes, formulario);

            mensaje.style.display = 'block';
        } catch (error) {
            mensaje.textContent = formulario.dataset.errorLike || 'No se pudo actualizar el like';
            mensaje.style.display = 'block';
        } finally {
            boton.disabled = false;
        }
    });

    function construirTextoLikes(totalLikes, formulario) {
        totalLikes = Number(totalLikes);

        const singular = formulario.dataset.likeSingular || 'like';
        const plural = formulario.dataset.likePlural || 'likes';

        if (totalLikes === 1) {
            return '1 ' + singular;
        }

        return totalLikes + ' ' + plural;
    }
});