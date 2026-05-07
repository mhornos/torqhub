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

    const bajarAlFinal = () => {
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth'
        });

        chat.scrollTop = chat.scrollHeight;
    };

    const crearCabeceraMensaje = (autorTexto) => {
        const cabecera = document.createElement('header');
        cabecera.classList.add('diagnostico__mensaje-cabecera');

        const autor = document.createElement('strong');
        autor.classList.add('diagnostico__mensaje-autor');
        autor.textContent = autorTexto;

        cabecera.appendChild(autor);

        return cabecera;
    };

    const crearParrafoMensaje = (texto) => {
        const contenido = document.createElement('div');
        contenido.classList.add('diagnostico__mensaje-contenido');

        const parrafo = document.createElement('p');
        parrafo.textContent = texto;

        contenido.appendChild(parrafo);

        return contenido;
    };

    const crearMensajeUsuario = (texto) => {
        const mensaje = document.createElement('article');
        mensaje.classList.add('diagnostico__mensaje', 'diagnostico__mensaje--usuario');

        mensaje.appendChild(crearCabeceraMensaje(textos.usuario));
        mensaje.appendChild(crearParrafoMensaje(texto));

        chat.appendChild(mensaje);
    };

    const crearMensajeCargando = () => {
        const mensaje = document.createElement('article');
        mensaje.classList.add('diagnostico__mensaje', 'diagnostico__mensaje--ia');
        mensaje.id = 'diagnostico-cargando';

        mensaje.appendChild(crearCabeceraMensaje(textos.ia));
        mensaje.appendChild(crearParrafoMensaje(textos.cargando));

        chat.appendChild(mensaje);
    };

    const eliminarMensajeCargando = () => {
        const mensajeCargando = document.getElementById('diagnostico-cargando');

        if (mensajeCargando) {
            mensajeCargando.remove();
        }
    };

    const crearProgresoConfianza = (confianza) => {
        const progreso = document.createElement('progress');
        progreso.classList.add('diagnostico__progreso');
        progreso.max = 100;
        progreso.value = confianza;
        progreso.textContent = `${confianza}%`;

        return progreso;
    };

    const crearResultado = (resultado) => {
        const confianzaNumero = Math.max(0, Math.min(100, parseInt(resultado.confianza) || 0));
        const coincidenciasNumero = parseInt(resultado.coincidencias) || 0;

        const articulo = document.createElement('article');
        articulo.classList.add('diagnostico__resultado');

        const cabecera = document.createElement('header');
        cabecera.classList.add('diagnostico__resultado-cabecera');

        const titulo = document.createElement('h2');
        titulo.textContent = resultado.titulo || '';

        cabecera.appendChild(titulo);

        const contenido = document.createElement('div');
        contenido.classList.add('diagnostico__resultado-contenido');

        const confianza = document.createElement('p');
        const confianzaTexto = document.createTextNode(`${textos.confianza}: `);

        const confianzaValor = document.createElement('strong');
        confianzaValor.textContent = `${confianzaNumero}%`;

        confianza.appendChild(confianzaTexto);
        confianza.appendChild(confianzaValor);

        const coincidencias = document.createElement('p');
        coincidencias.textContent = `${textos.coincidencias}: ${coincidenciasNumero}`;

        const recomendacion = document.createElement('p');

        const recomendacionTitulo = document.createElement('strong');
        recomendacionTitulo.textContent = `${textos.recomendacion}: `;

        const recomendacionTexto = document.createTextNode(resultado.recomendacion || '');

        recomendacion.appendChild(recomendacionTitulo);
        recomendacion.appendChild(recomendacionTexto);

        contenido.appendChild(confianza);
        contenido.appendChild(crearProgresoConfianza(confianzaNumero));
        contenido.appendChild(coincidencias);
        contenido.appendChild(recomendacion);

        articulo.appendChild(cabecera);
        articulo.appendChild(contenido);

        return articulo;
    };

    const crearMensajeIA = (resultados) => {
        const mensaje = document.createElement('article');
        mensaje.classList.add('diagnostico__mensaje', 'diagnostico__mensaje--ia');

        mensaje.appendChild(crearCabeceraMensaje(textos.ia));

        const contenido = document.createElement('div');
        contenido.classList.add('diagnostico__mensaje-contenido');

        if (resultados.length > 0) {
            const intro = document.createElement('p');
            intro.textContent = textos.resultadosIntro;

            const contenedorResultados = document.createElement('div');
            contenedorResultados.classList.add('diagnostico__resultados');

            resultados.forEach((resultado) => {
                contenedorResultados.appendChild(crearResultado(resultado));
            });

            contenido.appendChild(intro);
            contenido.appendChild(contenedorResultados);
        } else {
            const parrafo = document.createElement('p');
            parrafo.textContent = textos.sinResultados;

            contenido.appendChild(parrafo);
        }

        mensaje.appendChild(contenido);
        chat.appendChild(mensaje);
    };

    const mostrarError = (texto) => {
        eliminarMensajeCargando();

        const mensaje = document.createElement('article');
        mensaje.classList.add('diagnostico__mensaje', 'diagnostico__mensaje--ia');

        mensaje.appendChild(crearCabeceraMensaje(textos.ia));
        mensaje.appendChild(crearParrafoMensaje(texto));

        chat.appendChild(mensaje);
        bajarAlFinal();
    };

    actualizarBotonEnviar();

    campoSintomas.addEventListener('input', () => {
        actualizarBotonEnviar();
    });

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