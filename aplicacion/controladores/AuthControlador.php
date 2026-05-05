<?php

require_once dirname(__DIR__, 2) . '/lib/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthControlador extends ControladorBase {

// muestra el formulario de login
    public function login(): void {
        $this->render('auth/login');
    }


// procesa el formulario de login
    public function login_post(): void {
        csrf_verificar();

        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            flash_set('error', t('auth.error.rellena_login'));
            $this->redirigir('/login');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', t('auth.error.email_no_valido'));
            $this->redirigir('/login');
        }

        try {
            $usuario = RepositorioUsuarios::buscar_por_email($email);
        } catch (PDOException $e) {
            flash_set('error', t('auth.error.servidor'));
            $this->redirigir('/login');
        }

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            flash_set('error', t('auth.error.credenciales'));
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

        flash_set('ok', t('auth.ok.sesion_iniciada'));
        $this->redirigir('/');
    }


// muestra el formulario de registro
     public function registro():void {
        $this->render('auth/registro');
    }

// procesa el formulario de registro
    public function registro_post(): void {
        csrf_verificar();

        $nombre = strtolower(trim($_POST['nombre'] ?? ''));
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $password_repetida = $_POST['password_repetida'] ?? '';

        if ($nombre === '' || $email === '' || $password === '' || $password_repetida === '') {
            flash_set('error', t('auth.error.registro_obligatorios'));
            $this->redirigir('/registro');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', t('auth.error.email_no_valido'));
            $this->redirigir('/registro');
        }

        if ($password !== $password_repetida) {
            flash_set('error', t('auth.error.password_no_coincide'));
            $this->redirigir('/registro');
        }

        if (!$this->nombre_usuario_cumple_requisitos($nombre)) {
            flash_set('error', t('auth.error.nombre_requisitos'));
            $this->redirigir('/registro');
        }

        if (!$this->password_cumple_requisitos($password)) {
            flash_set('error', t('auth.error.password_requisitos'));
            $this->redirigir('/registro');
        }

        if (RepositorioUsuarios::existe_nombre($nombre)) {
            flash_set('error', t('auth.error.nombre_uso'));
            $this->redirigir('/registro');
        }

        if (RepositorioUsuarios::existe_email($email)) {
            flash_set('error', t('auth.error.email_registrado'));
            $this->redirigir('/registro');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            RepositorioUsuarios::crear($nombre, $email, $hash);

            flash_set('ok', t('auth.ok.registro_completado'));
            $this->redirigir('/login');

        } catch (PDOException $e) {

            if (($e->getCode() ?? '') === '23000') {
                flash_set('error', t('auth.error.nombre_email_uso'));
                $this->redirigir('/registro');
            }

            flash_set('error', t('auth.error.registro_error'));
            $this->redirigir('/registro');
        }
    }

// procesa el logout
    public function logout(): void {
        unset($_SESSION['usuario']);
        flash_set('ok', t('auth.ok.sesion_cerrada'));
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


// muestra el formulario para solicitar recuperacion de contraseña
    public function formulario_password_olvidada(): void {
        $this->render('auth/password_olvidada');
    }

// envia el correo de recuperacion de contraseña
    public function enviar_recuperacion_password(): void {
        csrf_verificar();

        $email = strtolower(trim($_POST['email'] ?? ''));

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', t('auth.error.email_no_valido'));
            $this->redirigir('/password/olvidada');
        }

        try {
            $usuario = RepositorioUsuarios::buscar_por_email($email);

            if ($usuario) {
                $token = bin2hex(random_bytes(32));
                $token_hash = hash('sha256', $token);
                $fecha_expiracion = date('Y-m-d H:i:s', time() + 3600);

                RepositorioRecuperacionesPassword::invalidar_anteriores((int) $usuario['id']);
                RepositorioRecuperacionesPassword::crear((int) $usuario['id'], $token_hash, $fecha_expiracion);

                $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $dominio = $_SERVER['HTTP_HOST'];

                $enlace = $protocolo . '://' . $dominio . url('/password/restablecer?token=' . urlencode($token));

                $this->enviar_email_recuperacion($usuario['email'], $usuario['nombre'], $enlace);
            }
        } catch (PDOException $e) {
            error_log('Error en recuperación de contraseña: ' . $e->getMessage());

            flash_set('error', t('auth.error.servidor'));
            $this->redirigir('/password/olvidada');
        }

        flash_set('ok', t('auth.ok.recuperacion_enviada'));
        $this->redirigir('/password/olvidada');
    }

// envia el email con el enlace de recuperacion
    private function enviar_email_recuperacion(string $email, string $nombre, string $enlace): void {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USUARIO;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PUERTO;

            $mail->CharSet = 'UTF-8';

            $mail->setFrom(SMTP_REMITENTE, SMTP_NOMBRE_REMITENTE);
            $mail->addAddress($email, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Restablecer contraseña en TorqHub';

            $mail->Body = '
                <h2>Restablecer contraseña</h2>
                <p>Hola, ' . htmlspecialchars($nombre) . '.</p>
                <p>Has solicitado restablecer tu contraseña en TorqHub.</p>
                <p>Haz clic en el siguiente enlace:</p>
                <p><a href="' . htmlspecialchars($enlace) . '">Restablecer contraseña</a></p>
                <p>Este enlace caduca en 1 hora.</p>
                <p>Si no has sido tú, puedes ignorar este correo.</p>
            ';

            $mail->AltBody = "Hola, $nombre.\n\nHas solicitado restablecer tu contraseña en TorqHub.\n\nEnlace:\n$enlace\n\nEste enlace caduca en 1 hora.\n\nSi no has sido tú, puedes ignorar este correo.";

            $mail->send();
        } catch (Exception $e) {
            error_log('Error enviando recuperación de contraseña: ' . $mail->ErrorInfo);
        }
    }


// muestra formulario de restablecer contraseña
    public function formulario_restablecer_password(): void {
        $token = trim($_GET['token'] ?? '');

        if ($token === '') {
            flash_set('error', t('auth.error.token_no_valido'));
            $this->redirigir('/login');
        }

        $token_hash = hash('sha256', $token);

        $recuperacion = RepositorioRecuperacionesPassword::buscar_token_valido($token_hash);

        if (!$recuperacion) {
            flash_set('error', t('auth.error.enlace_expirado'));
            $this->redirigir('/login');
        }

        $this->render('auth/password_restablecer', [
            'token' => $token,
        ]);
    }

// guarda nueva contraseña desde token
    public function guardar_password_restablecida(): void {
        csrf_verificar();

        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_repetida = $_POST['password_repetida'] ?? '';

        if ($token === '') {
            flash_set('error', t('auth.error.token_no_valido'));
            $this->redirigir('/login');
        }

        if ($password === '' || $password_repetida === '') {
            flash_set('error', t('auth.error.campos_obligatorios'));
            $this->redirigir('/password/restablecer?token=' . urlencode($token));
        }

        if ($password !== $password_repetida) {
            flash_set('error', t('auth.error.password_no_coincide'));
            $this->redirigir('/password/restablecer?token=' . urlencode($token));
        }

        if (!$this->password_segura($password)) {
            flash_set('error', t('auth.error.password_minimos'));
            $this->redirigir('/password/restablecer?token=' . urlencode($token));
        }

        $token_hash = hash('sha256', $token);

        try {
            $recuperacion = RepositorioRecuperacionesPassword::buscar_token_valido($token_hash);

            if (!$recuperacion) {
                flash_set('error', t('auth.error.enlace_expirado'));
                $this->redirigir('/login');
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            RepositorioUsuarios::actualizar_password(
                (int) $recuperacion['usuario_id'],
                $password_hash
            );

            RepositorioRecuperacionesPassword::marcar_como_usado(
                (int) $recuperacion['id']
            );

            flash_set('ok', t('auth.ok.password_restablecida'));
            $this->redirigir('/login');
        } catch (PDOException $e) {
            error_log('Error restableciendo contraseña: ' . $e->getMessage());

            flash_set('error', t('auth.error.servidor'));
            $this->redirigir('/login');
        }
    }

// valida requisitos mínimos de contraseña
    private function password_segura(string $password): bool {
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
