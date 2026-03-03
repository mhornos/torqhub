<?php

class AdminControlador extends ControladorBase {
    public function index(): void {
        $this->render('admin/index');
    }
}