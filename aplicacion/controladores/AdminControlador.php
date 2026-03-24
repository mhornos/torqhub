<?php

class AdminControlador extends ControladorBase {
    
//muestra la página de administración, solo accesible para usuarios con rol admin
    public function index(): void {
        $this->render('admin/index');
    }
}