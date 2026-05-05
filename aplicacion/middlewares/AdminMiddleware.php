<?php

class AdminMiddleware
{
    // verifica que el usuario esté logueado y tenga rol de admin, si no redirige o muestra error
    public static function verificar(): void {
        if (!isset($_SESSION['usuario'])) {
            if (peticion_ajax()) {
                respuesta_json([
                    'ok' => false,
                    'mensaje' => t('middleware.admin.error.login_requerido'),
                    'redirigir' => url('/login'),
                ], 401);
            }

            flash_set('error', t('middleware.admin.error.login_requerido'));
            header('Location: ' . url('/login'));
            exit;
        }

        if (($_SESSION['usuario']['rol'] ?? 'usuario') !== 'admin') {
            if (peticion_ajax()) {
                respuesta_json([
                    'ok' => false,
                    'mensaje' => t('middleware.admin.error.acceso_denegado'),
                ], 403);
            }

            http_response_code(403);
            echo htmlspecialchars(t('middleware.admin.error.acceso_denegado'));
            exit;
        }
    }
}