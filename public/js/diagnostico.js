document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('formulario-diagnostico');
    const campoSintomas = document.getElementById('sintomas');
    const chat = document.getElementById('chat-diagnostico');

    if (!formulario || !campoSintomas || !chat) {
        return;
    }

    const textos = {
        usuario: formulario.dataset.textoUsuario || 'Tú',
        ia: formulario.dataset.textoIa || 'TorqHub IA',
        cargando: formulario.dataset.textoCargando || 'Analizando...',
        analizando: formulario.dataset.textoAnalizando || 'Analizando...',
        analizar: formulario.dataset.textoAnalizar || 'Analizar',
        errorAnalisis: formulario.dataset.errorAnalisis || 'Ha ocurrido un error al analizar los síntomas',
        errorConexion: formulario.dataset.errorConexion || 'No se ha podido conectar con el sistema de diagnóstico',
        resultadosIntro: formulario.dataset.resultadosIntro || 'He encontrado estas posibles causas:',
        sinResultados: formulario.dataset.sinResultados || 'No he encontrado una causa clara.',
        confianza: formulario.dataset.confianza || 'Confianza aproximada',
        coincidencias: formulario.dataset.coincidencias || 'Coincidencias detectadas',
        recomendacion: formulario.dataset.recomendacion || 'Recomendación',
    };

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
        autor.textContent = textos.usuario;

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
        autor.textContent = textos.ia;

        const parrafo = document.createElement('p');
        parrafo.textContent = textos.cargando;

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
        const confianzaTexto = document.createTextNode(textos.confianza + ': ');

        const confianzaValor = document.createElement('strong');
        confianzaValor.textContent = parseInt(resultado.confianza) + '%';

        confianza.appendChild(confianzaTexto);
        confianza.appendChild(confianzaValor);

        const coincidencias = document.createElement('p');
        coincidencias.textContent = textos.coincidencias + ': ' + parseInt(resultado.coincidencias);

        const recomendacion = document.createElement('p');

        const recomendacionTitulo = document.createElement('strong');
        recomendacionTitulo.textContent = textos.recomendacion + ': ';

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
        autor.textContent = textos.ia;

        mensaje.appendChild(autor);

        if (resultados.length > 0) {
            const intro = document.createElement('p');
            intro.textContent = textos.resultadosIntro;

            const contenedorResultados = document.createElement('div');
            contenedorResultados.classList.add('diagnostico__resultados');

            resultados.forEach((resultado) => {
                contenedorResultados.appendChild(crearResultado(resultado));
            });

            mensaje.appendChild(intro);
            mensaje.appendChild(contenedorResultados);
        } else {
            const parrafo = document.createElement('p');
            parrafo.textContent = textos.sinResultados;

            mensaje.appendChild(parrafo);
        }

        chat.appendChild(mensaje);
    };

    const mostrarError = (texto) => {
        eliminarMensajeCargando();

        const mensaje = document.createElement('div');
        mensaje.classList.add('diagnostico__mensaje', 'diagnostico__mensaje--ia');

        const autor = document.createElement('strong');
        autor.textContent = textos.ia;

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
            botonEnviar.textContent = textos.analizando;
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
                mostrarError(datos.mensaje || textos.errorAnalisis);
                return;
            }

            crearMensajeIA(datos.resultados);
            bajarAlFinal();
        } catch (error) {
            mostrarError(textos.errorConexion);
        } finally {
            if (botonEnviar) {
                botonEnviar.textContent = textos.analizar;
            }
            
            actualizarBotonEnviar();
            campoSintomas.focus();
        }
    });

    bajarAlFinal();
});