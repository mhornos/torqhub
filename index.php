<?php
// punto de entrada de la aplicación, maneja el enrutamiento y la ejecución de controladores

require __DIR__ . "/configuracion/config.php";

// configuración de errores según entorno
error_reporting(E_ALL);

if (APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
}

ini_set('log_errors', '1');

// configuración segura de sesión
$es_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

if ($es_https) {
    ini_set('session.cookie_secure', '1');
}

$path_cookie = rtrim(BASE_URL, '/');

if ($path_cookie === '') {
    $path_cookie = '/';
}

session_name('TORQHUBSESSID');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => $path_cookie,
    'domain' => '',
    'secure' => $es_https,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

require __DIR__ . "/configuracion/autoload.php";
require __DIR__ . "/configuracion/helpers.php";

$rutas = require __DIR__ . "/rutas/web.php";

try {
    $metodo_http = $_SERVER["REQUEST_METHOD"] ?? "GET";

    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $base = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
    $ruta = "/" . ltrim(str_replace($base, "", $path), "/");

    if (isset($rutas[$metodo_http][$ruta])) {
        $ruta_config = $rutas[$metodo_http][$ruta];

        // ejecutar middleware si existe
        if (isset($ruta_config["middleware"])) {
            $middleware = $ruta_config["middleware"];
            $middleware::verificar();
        }

        $accion = $ruta_config["accion"];

        [$nombre_controlador, $metodo] = explode("@", $accion);

        $controlador = new $nombre_controlador();
        $controlador->$metodo();

        exit;
    }

    http_response_code(404);

    if (peticion_ajax()) {
        respuesta_json([
            'ok' => false,
            'mensaje' => t('seguridad.error.ruta_no_encontrada'),
        ], 404);
    }

    echo escapar(t('seguridad.error.ruta_no_encontrada'));

} catch (Throwable $e) {
    error_log('Error no controlado en TorqHub: ' . $e->getMessage());

    http_response_code(500);

    if (peticion_ajax()) {
        respuesta_json([
            'ok' => false,
            'mensaje' => t('seguridad.error.servidor'),
        ], 500);
    }

    echo escapar(t('seguridad.error.servidor'));
}