<?php

class Vista {

    // renderiza una vista, extrayendo variables del array de datos y mostrando navbar
    public static function render(string $vista, array $datos = []): void {
        $ruta_vista = __DIR__ . '/../vistas/' . $vista . '.php';
        $ruta_navbar = __DIR__ . '/../vistas/plantillas/navbar.php';

        // verifica que la vista existe, si no mostrar error limpio
        if (!file_exists($ruta_vista)) {
            error_log('Vista no encontrada: ' . $ruta_vista);

            http_response_code(500);
            echo escapar(t('seguridad.error.servidor'));
            return;
        }

        // extrae variables del array como variables normales
        extract($datos);

        ob_start();
        require $ruta_navbar;
        $html_navbar = ob_get_clean();

        ob_start();
        require $ruta_vista;
        $html_vista = ob_get_clean();

        // inserta el navbar justo después de abrir el body
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

        // fallback por si alguna vista futura no tuviera body
        echo $html_navbar;
        echo $html_vista;
    }
}

?>