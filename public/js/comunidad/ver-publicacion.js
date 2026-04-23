document.addEventListener('DOMContentLoaded', function () {
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
});