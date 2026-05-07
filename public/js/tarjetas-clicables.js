document.addEventListener('DOMContentLoaded', () => {
    const tarjetas = document.querySelectorAll('[data-url-tarjeta]');

    if (!tarjetas.length) {
        return;
    }

    const selectorInteractivo = [
        'a',
        'button',
        'input',
        'select',
        'textarea',
        'label',
        'form',
        '[role="button"]',
        '[data-no-click-tarjeta]'
    ].join(',');

    tarjetas.forEach((tarjeta) => {
        tarjeta.addEventListener('click', (evento) => {
            if (evento.target.closest(selectorInteractivo)) {
                return;
            }

            const url = tarjeta.dataset.urlTarjeta;

            if (url) {
                window.location.href = url;
            }
        });

        tarjeta.addEventListener('keydown', (evento) => {
            if (evento.key !== 'Enter' && evento.key !== ' ') {
                return;
            }

            if (evento.target.closest(selectorInteractivo)) {
                return;
            }

            evento.preventDefault();

            const url = tarjeta.dataset.urlTarjeta;

            if (url) {
                window.location.href = url;
            }
        });
    });
});