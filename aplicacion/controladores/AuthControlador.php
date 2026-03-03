<?php

class AuthControlador extends ControladorBase {

// muestra el formulario de login
    public function login(): void {
        $this->render('auth/login');
    }


// procesa el formulario de login
    public function login_post(): void {
        csrf_verificar();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            flash_set('error', 'rellena email y password');
            $this->redirigir('/login');
        }

        try {
            $usuario = RepositorioUsuarios::buscar_por_email($email);
        } catch (PDOException $e) {
            flash_set('error', 'error de servidor, intentalo mas tarde');
            $this->redirigir('/login');
        }

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            flash_set('error', 'credenciales incorrectas');
            $this->redirigir('/login');
        }

        // regenerar id de sesion para evitar session fixation
        session_regenerate_id(true);

        $_SESSION['usuario'] = [
            'id' => (int) $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
            'rol' => $usuario['rol'],
        ];

        flash_set('ok', 'sesión iniciada');
        $this->redirigir('/');
    }


// muestra el formulario de registro
     public function registro():void {
        $this->render('auth/registro');
    }

// procesa el formulario de registro
    public function registro_post(): void {
        csrf_verificar();

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
        
        try {
            RepositorioUsuarios::crear($nombre, $email, $hash);

            flash_set('ok', 'registro completado, ahora inicia sesion');
            $this->redirigir('/login');

        } catch (PDOException $e) {

            if (($e->getCode() ?? '') === '23000') {
                flash_set('error', 'nombre o email ya estan en uso');
                $this->redirigir('/registro');
            }

            flash_set('error', 'error al registrar, intentalo mas tarde');
            $this->redirigir('/registro');
        }
    }

// procesa el logout
    public function logout(): void {
        unset($_SESSION['usuario']);
        flash_set('ok', 'sesión cerrada');
        $this->redirigir('/');
    }

}
