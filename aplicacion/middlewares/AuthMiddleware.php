<?php 

class AuthMiddleware {

    public static function verificar(): void {
        
        if (!isset($_SESSION["usuario"])){
            flash_set("error", "debes iniciar sesión para acceder a esta página");
            header ("Location: " . url("login"));
            exit();
        }
    }
}

?>