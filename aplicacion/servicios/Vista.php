<?php

class Vista {
    
    // renderiza una vista, extrayendo variables del array de datos y mostrando navbar
    public static function render(string $vista, array $datos = []): void {
        $ruta_vista = __DIR__ . '/../vistas/' . $vista . '.php';
        $ruta_navbar = __DIR__ . '/../vistas/plantillas/navbar.php';

        // verifica que la vista existe, si no mostrar error
        if (!file_exists($ruta_vista)) {
            http_response_code(500);
            echo "vista no encontrada: " . $vista;
            return;
        }

        // extrae variables del array como variables normales
        extract($datos);

        require $ruta_navbar;
        require $ruta_vista;
    }
}

?>