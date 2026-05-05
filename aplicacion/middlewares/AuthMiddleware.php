<?php

class AuthMiddleware
{

// verifica que el usuario esté logueado, si no redirige a login
    public static function verificar(): void {
        if (!isset($_SESSION["usuario"])) {
            if (peticion_ajax()) {
                respuesta_json([
                    'ok' => false,
                    'mensaje' => t('middleware.auth.error.login_requerido'),
                    'redirigir' => url('/login'),
                ], 401);
            }

            flash_set('error', t('middleware.auth.error.login_requerido'));
            header('Location: ' . url('/login'));
            exit;
        }

        try {
            $usuario_acceso = RepositorioUsuarios::buscar_estado_acceso((int) ($_SESSION['usuario']['id'] ?? 0));

            if (!$usuario_acceso || (int) $usuario_acceso['activo'] !== 1) {
                unset($_SESSION['usuario']);

                if (peticion_ajax()) {
                    respuesta_json([
                        'ok' => false,
                        'mensaje' => t('auth.error.usuario_desactivado'),
                        'redirigir' => url('/login'),
                    ], 401);
                }

                flash_set('error', t('auth.error.usuario_desactivado'));
                header('Location: ' . url('/login'));
                exit;
            }

            $_SESSION['usuario']['rol'] = $usuario_acceso['rol'];
            $_SESSION['usuario']['activo'] = (int) $usuario_acceso['activo'];
        } catch (PDOException $e) {
            error_log('Error comprobando estado de usuario en AuthMiddleware: ' . $e->getMessage());

            if (peticion_ajax()) {
                respuesta_json([
                    'ok' => false,
                    'mensaje' => t('seguridad.error.servidor'),
                ], 500);
            }

            http_response_code(500);
            echo escapar(t('seguridad.error.servidor'));
            exit;
        }
    }
}
