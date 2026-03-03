<?php

class ControladorBase {
    protected function render(string $vista, array $datos = []): void {
        Vista::render($vista, $datos);
    }

    protected function redirigir(string $ruta): void {
        header('Location: ' . BASE_URL . $ruta);
        exit;
    }
}

?>