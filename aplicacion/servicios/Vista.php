<?php

class Vista
{

    // renderiza una vista, extrayendo variables del array de datos y mostrando navbar
    public static function render(string $vista, array $datos = []): void {
        $ruta_vista = __DIR__ . '/../vistas/' . $vista . '.php';
        $ruta_navbar = __DIR__ . '/../vistas/plantillas/navbar.php';

        if (!file_exists($ruta_vista)) {
            error_log('Vista no encontrada: ' . $ruta_vista);

            http_response_code(500);
            echo escapar(t('seguridad.error.servidor'));
            return;
        }

        $scripts_pagina = array_merge(
            [
                '/public/js/navbar.js',
                '/public/js/tarjetas-clicables.js',
            ],
            $datos['scripts'] ?? []
        );

        $scripts_pagina = array_values(array_unique(array_filter($scripts_pagina, 'is_string')));

        extract($datos);

        ob_start();
        require $ruta_navbar;
        $html_navbar = ob_get_clean();

        ob_start();
        require $ruta_vista;
        $html_vista = ob_get_clean();

        $html_scripts = self::renderizar_scripts($scripts_pagina);

        if (preg_match('/<\/body>/i', $html_vista)) {
            $html_vista = preg_replace(
                '/<\/body>/i',
                $html_scripts . PHP_EOL . '</body>',
                $html_vista,
                1
            );
        }

        if (preg_match('/<body[^>]*>/i', $html_vista)) {
            $html_vista = preg_replace(
                '/<body([^>]*)>/i',
                '<body$1>' . PHP_EOL . $html_navbar,
                $html_vista,
                1
            );

            echo $html_vista;
            return;
        }

        echo $html_navbar;
        echo $html_vista;
    }

//para cargar js sin poner scripts en vistas
    private static function renderizar_scripts(array $scripts): string {
        $html = '';

        foreach ($scripts as $script) {
            $script = trim($script);

            if ($script === '') {
                continue;
            }

            $html .= '<script defer src="' . escapar(url($script)) . '"></script>' . PHP_EOL;
        }

        return $html;
    }

    
}
