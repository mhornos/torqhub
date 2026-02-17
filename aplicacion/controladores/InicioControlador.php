<?php

class InicioControlador extends ControladorBase{
    
    public function index(): void {
        $this->render('inicio');
    }

}

?>