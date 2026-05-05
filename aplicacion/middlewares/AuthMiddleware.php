<?php 

class AuthMiddleware {

    // verifica que el usuario esté logueado, si no redirige a login
    public static function verificar(): void {
        
        if (!isset($_SESSION["usuario"])){
            flash_set('error', t('middleware.auth.error.login_requerido'));
            header('Location: ' . url('/login'));
            exit();
        }
    }
}

?>