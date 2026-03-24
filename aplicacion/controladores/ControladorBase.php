<?php

class ControladorBase {

//renderiza una vista pasando un array de datos opcional
    protected function render(string $vista, array $datos = []): void {
        Vista::render($vista, $datos);
    }

//redirige a una ruta relativa a la base de la aplicación
    protected function redirigir(string $ruta): void {
        header('Location: ' . BASE_URL . $ruta);
        exit;
    }
}

?>