<?php

class AuthControlador extends ControladorBase {

// muestra el formulario de login
    public function login(): void {
        $this->render('auth/login');
    }


// procesa el formulario de login
    public function login_post(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            flash_set('error', 'rellena email y password');
            $this->redirigir('/login');
        }

        $usuario = RepositorioUsuarios::buscar_por_email($email);

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            flash_set('error', 'credenciales incorrectas');
            $this->redirigir('/login');
        }

        $_SESSION['usuario'] = [
            'id' => (int) $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'rol' => $usuario['rol'],
        ];

        flash_set('ok', 'sesion iniciada');
        $this->redirigir('/');
    }


// muestra el formulario de registro
     public function registro():void {
        $this->render('auth/registro');
    }

// procesa el formulario de registro
    public function registro_post(): void {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nombre === '' || $email === '' || $password === '') {
            flash_set('error', 'rellena nombre, email y password');
            $this->redirigir('/registro');
        }

        if (RepositorioUsuarios::existe_nombre($nombre)) {
            flash_set('error', 'ese nombre ya esta en uso');
            $this->redirigir('/registro');
        }

        if (RepositorioUsuarios::existe_email($email)) {
            flash_set('error', 'ese email ya esta registrado');
            $this->redirigir('/registro');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        RepositorioUsuarios::crear($nombre, $email, $hash);

        flash_set('ok', 'registro completado, ahora inicia sesion');
        $this->redirigir('/login');
    }

// procesa el logout
    public function logout(): void {
        unset($_SESSION['usuario']);
        flash_set('ok', 'sesion cerrada');
        $this->redirigir('/');
    }

}
