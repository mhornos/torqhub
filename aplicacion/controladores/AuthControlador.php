<?php

class AuthControlador
{
    public function login()
    {
        Vista::render('auth/login');
    }

    public function registro()
    {
        Vista::render('auth/registro');
    }
}
