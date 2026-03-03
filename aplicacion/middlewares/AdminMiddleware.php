<?php

class AdminMiddleware
{
    public static function verificar(): void
    {
        if (!isset($_SESSION['usuario'])) {
            flash_set('error', 'debes iniciar sesión');
            header('Location: ' . url('/login'));
            exit;
        }

        if (($_SESSION['usuario']['rol'] ?? 'usuario') !== 'admin') {
            http_response_code(403);
            echo "403 - acceso denegado";
            exit;
        }
    }
}