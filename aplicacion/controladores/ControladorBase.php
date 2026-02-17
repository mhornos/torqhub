<?php

class ControladorBase
{
    protected function render (string $vista):void 
    {
        Vista::render($vista);
    }

    protected function redirigir(string $ruta): void
    {
        header('Location: ' . BASE_URL . $ruta);
        exit;
    }
}

?>