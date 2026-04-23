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
            flash_set('error', 'Rellena email y password');
            $this->redirigir('/login');
        }

        try {
            $usuario = RepositorioUsuarios::buscar_por_email($email);
        } catch (PDOException $e) {
            flash_set('error', 'Error de servidor, intentalo mas tarde');
            $this->redirigir('/login');
        }

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            flash_set('error', 'Credenciales incorrectas');
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

        flash_set('ok', 'Sesión iniciada');
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
            flash_set('error', 'Debes rellenar nombre de usuario, correo electrónico y contraseña');
            $this->redirigir('/registro');
        }

        if (!$this->nombre_usuario_cumple_requisitos($nombre)) {
            flash_set('error', 'El nombre de usuario solo puede contener letras minúsculas, números, puntos y guiones bajos, sin espacios, sin puntos consecutivos y sin terminar en punto');
            $this->redirigir('/registro');
        }

        if (!$this->password_cumple_requisitos($password)) {
            flash_set('error', 'La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula y un número');
            $this->redirigir('/registro');
        }

        if (RepositorioUsuarios::existe_nombre($nombre)) {
            flash_set('error', 'Ese nombre de usuario ya está en uso');
            $this->redirigir('/registro');
        }

        if (RepositorioUsuarios::existe_email($email)) {
            flash_set('error', 'Ese correo electrónico ya está registrado');
            $this->redirigir('/registro');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            RepositorioUsuarios::crear($nombre, $email, $hash);

            flash_set('ok', 'Registro completado, ahora inicia sesión');
            $this->redirigir('/login');

        } catch (PDOException $e) {

            if (($e->getCode() ?? '') === '23000') {
                flash_set('error', 'El nombre de usuario o el correo electrónico ya están en uso');
                $this->redirigir('/registro');
            }

            flash_set('error', 'Se produjo un error al registrar la cuenta');
            $this->redirigir('/registro');
        }
    }

// procesa el logout
    public function logout(): void {
        unset($_SESSION['usuario']);
        flash_set('ok', 'Sesión cerrada correctamente');
        $this->redirigir('/');
    }

// valida el formato del nombre de usuario
    private function nombre_usuario_cumple_requisitos(string $nombre): bool {
        return preg_match('/^(?!.*\.\.)(?!.*\.$)[a-z0-9._]+$/', $nombre) === 1;
    }

// verifica que la password cumpla los requisitos de seguridad
    private function password_cumple_requisitos(string $password): bool {
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        return true;
    }

}
