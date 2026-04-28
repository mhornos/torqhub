document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('formulario-diagnostico');
    const campoSintomas = document.getElementById('sintomas');
    const chat = document.getElementById('chat-diagnostico');

    if (!formulario || !campoSintomas || !chat) {
        return;
    }

    const botonEnviar = formulario.querySelector('button[type="submit"]');

    const hayTextoValido = () => campoSintomas.value.trim().length > 0;

    const actualizarBotonEnviar = () => {
        if (!botonEnviar) {
            return;
        }

        botonEnviar.disabled = !hayTextoValido();
    };

    actualizarBotonEnviar();

    campoSintomas.addEventListener('input', () => {
        actualizarBotonEnviar();
    });

    const bajarAlFinal = () => {
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth'
        });

        chat.scrollTop = chat.scrollHeight;
    };

    const crearMensajeUsuario = (texto) => {
        const mensaje = document.createElement('div');
        mensaje.classList.add('diagnostico__mensaje', 'diagnostico__mensaje--usuario');

        const autor = document.createElement('strong');
        autor.textContent = 'Tú';

        const parrafo = document.createElement('p');
        parrafo.textContent = texto;

        mensaje.appendChild(autor);
        mensaje.appendChild(parrafo);

        chat.appendChild(mensaje);
    };

    const crearMensajeCargando = () => {
        const mensaje = document.createElement('div');
        mensaje.classList.add('diagnostico__mensaje', 'diagnostico__mensaje--ia');
        mensaje.id = 'diagnostico-cargando';

        const autor = document.createElement('strong');
        autor.textContent = 'TorqHub IA';

        const parrafo = document.createElement('p');
        parrafo.textContent = 'analizando síntomas...';

        mensaje.appendChild(autor);
        mensaje.appendChild(parrafo);

        chat.appendChild(mensaje);
    };

    const eliminarMensajeCargando = () => {
        const mensajeCargando = document.getElementById('diagnostico-cargando');

        if (mensajeCargando) {
            mensajeCargando.remove();
        }
    };

    const crearBarraConfianza = (confianza) => {
        const barra = document.createElement('div');
        barra.classList.add('diagnostico__barra');

        const progreso = document.createElement('span');
        progreso.style.width = `${confianza}%`;

        barra.appendChild(progreso);

        return barra;
    };

    const crearResultado = (resultado) => {
        const articulo = document.createElement('article');
        articulo.classList.add('diagnostico__resultado');

        const titulo = document.createElement('h3');
        titulo.textContent = '"' + resultado.titulo + '"';

        const confianza = document.createElement('p');
        confianza.innerHTML = `Confianza aproximada: <strong>${parseInt(resultado.confianza)}%</strong>`;

        const coincidencias = document.createElement('p');
        coincidencias.textContent = `Coincidencias detectadas: ${parseInt(resultado.coincidencias)}`;

        const recomendacion = document.createElement('p');

        const recomendacionTitulo = document.createElement('strong');
        recomendacionTitulo.textContent = 'Recomendación: ';

        const recomendacionTexto = document.createTextNode(resultado.recomendacion);

        recomendacion.appendChild(recomendacionTitulo);
        recomendacion.appendChild(recomendacionTexto);

        articulo.appendChild(titulo);
        articulo.appendChild(confianza);
        articulo.appendChild(crearBarraConfianza(parseInt(resultado.confianza)));
        articulo.appendChild(coincidencias);
        articulo.appendChild(recomendacion);

        return articulo;
    };

    const crearMensajeIA = (resultados) => {
        const mensaje = document.createElement('div');
        mensaje.classList.add('diagnostico__mensaje', 'diagnostico__mensaje--ia');

        const autor = document.createElement('strong');
        autor.textContent = 'TorqHub IA';

        mensaje.appendChild(autor);

        if (resultados.length > 0) {
            const intro = document.createElement('p');
            intro.textContent = 'He encontrado estas posibles causas:';

            const contenedorResultados = document.createElement('div');
            contenedorResultados.classList.add('diagnostico__resultados');

            resultados.forEach((resultado) => {
                contenedorResultados.appendChild(crearResultado(resultado));
            });

            mensaje.appendChild(intro);
            mensaje.appendChild(contenedorResultados);
        } else {
            const parrafo = document.createElement('p');
            parrafo.textContent = 'No he encontrado una causa clara. Prueba describiendo síntomas más concretos como ruido, temperatura, arranque, frenos, dirección o pérdida de potencia.';

            mensaje.appendChild(parrafo);
        }

        chat.appendChild(mensaje);
    };

    const mostrarError = (texto) => {
        eliminarMensajeCargando();

        const mensaje = document.createElement('div');
        mensaje.classList.add('diagnostico__mensaje', 'diagnostico__mensaje--ia');

        const autor = document.createElement('strong');
        autor.textContent = 'TorqHub IA';

        const parrafo = document.createElement('p');
        parrafo.textContent = texto;

        mensaje.appendChild(autor);
        mensaje.appendChild(parrafo);

        chat.appendChild(mensaje);
        bajarAlFinal();
    };

    campoSintomas.addEventListener('keydown', (evento) => {
        if (evento.key === 'Enter' && !evento.shiftKey) {
            evento.preventDefault();

            if (!hayTextoValido()) {
                return;
            }

            formulario.requestSubmit();
        }
    });

    formulario.addEventListener('submit', async (evento) => {
        evento.preventDefault();

        const sintomas = campoSintomas.value.trim();

        if (sintomas === '') {
            actualizarBotonEnviar();
            campoSintomas.focus();
            return;
        }

        const datosFormulario = new FormData(formulario);
        const urlAjax = formulario.dataset.urlAjax;

        crearMensajeUsuario(sintomas);
        campoSintomas.value = '';
        crearMensajeCargando();
        bajarAlFinal();

        if (botonEnviar) {
            botonEnviar.disabled = true;
            botonEnviar.textContent = 'analizando...';
        }

        try {
            const respuesta = await fetch(urlAjax, {
                method: 'POST',
                body: datosFormulario,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const datos = await respuesta.json();

            eliminarMensajeCargando();

            if (!respuesta.ok || !datos.ok) {
                mostrarError(datos.mensaje || 'ha ocurrido un error al analizar los sintomas');
                return;
            }

            crearMensajeIA(datos.resultados);
            bajarAlFinal();
        } catch (error) {
            mostrarError('no se ha podido conectar con el sistema de diagnostico');
        } finally {
            if (botonEnviar) {
                botonEnviar.textContent = 'analizar';
            }
            
            actualizarBotonEnviar();
            campoSintomas.focus();
        }
    });

    bajarAlFinal();
});