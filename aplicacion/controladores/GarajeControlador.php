<?php

class GarajeControlador extends ControladorBase
{
    public function index(): void
    {
        $this->render("garaje/index");
    }
}

?>