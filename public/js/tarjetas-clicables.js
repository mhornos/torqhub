document.addEventListener('DOMContentLoaded', () => {
    const selectorTarjeta = '[data-url-tarjeta]';

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

    const abrirTarjeta = (tarjeta) => {
        const url = tarjeta.dataset.urlTarjeta;

        if (url) {
            window.location.href = url;
        }
    };

    document.addEventListener('click', (evento) => {
        const tarjeta = evento.target.closest(selectorTarjeta);

        if (!tarjeta) {
            return;
        }

        if (evento.target.closest(selectorInteractivo)) {
            return;
        }

        abrirTarjeta(tarjeta);
    });

    document.addEventListener('keydown', (evento) => {
        if (evento.key !== 'Enter' && evento.key !== ' ') {
            return;
        }

        const tarjeta = evento.target.closest(selectorTarjeta);

        if (!tarjeta) {
            return;
        }

        if (evento.target.closest(selectorInteractivo)) {
            return;
        }

        evento.preventDefault();
        abrirTarjeta(tarjeta);
    });
});