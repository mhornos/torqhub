// carrusel de fotos del detalle de vehículo

document.addEventListener('DOMContentLoaded', function () {
    const carruseles = document.querySelectorAll('.carrusel-vehiculo');

    carruseles.forEach(function (carrusel) {
        const imagenPrincipal = carrusel.querySelector('.carrusel-vehiculo__imagen');
        const contador = carrusel.querySelector('.carrusel-vehiculo__contador');
        const botonAnterior = carrusel.querySelector('.carrusel-vehiculo__control--anterior');
        const botonSiguiente = carrusel.querySelector('.carrusel-vehiculo__control--siguiente');
        const miniaturas = Array.from(carrusel.querySelectorAll('.carrusel-vehiculo__miniatura'));

        const visor = carrusel.querySelector('.carrusel-vehiculo__visor');

        const prepararUrlCss = function (url) {
            return 'url("' + String(url).replace(/\\/g, '\\\\').replace(/"/g, '\\"') + '")';
        };

        const actualizarFondoVisor = function (urlImagen) {
            if (!visor || !urlImagen) {
                return;
            }

            visor.style.setProperty('--imagen-carrusel-fondo', prepararUrlCss(urlImagen));
        };

        if (imagenPrincipal) {
            actualizarFondoVisor(imagenPrincipal.getAttribute('src'));
        }

        if (!imagenPrincipal || miniaturas.length === 0) {
            if (botonAnterior) {
                botonAnterior.style.display = 'none';
            }

            if (botonSiguiente) {
                botonSiguiente.style.display = 'none';
            }

            return;
        }

        let indiceActual = 0;

        const mostrarImagen = function (indice) {
            if (indice < 0) {
                indiceActual = miniaturas.length - 1;
            } else if (indice >= miniaturas.length) {
                indiceActual = 0;
            } else {
                indiceActual = indice;
            }

            const miniaturaActiva = miniaturas[indiceActual];

            const nuevaImagen = miniaturaActiva.dataset.src;

            imagenPrincipal.src = nuevaImagen;
            imagenPrincipal.alt = miniaturaActiva.dataset.alt || '';

            actualizarFondoVisor(nuevaImagen);

            miniaturas.forEach(function (miniatura) {
                miniatura.classList.remove('carrusel-vehiculo__miniatura--activa');
            });

            miniaturaActiva.classList.add('carrusel-vehiculo__miniatura--activa');

            if (contador) {
                contador.textContent = (indiceActual + 1) + ' / ' + miniaturas.length;
            }
        };

        miniaturas.forEach(function (miniatura, indice) {
            miniatura.addEventListener('click', function () {
                mostrarImagen(indice);
            });
        });

        if (botonAnterior) {
            botonAnterior.addEventListener('click', function () {
                mostrarImagen(indiceActual - 1);
            });
        }

        if (botonSiguiente) {
            botonSiguiente.addEventListener('click', function () {
                mostrarImagen(indiceActual + 1);
            });
        }
    });
});