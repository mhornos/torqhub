<?php
//punto de entrada de la aplicación, maneja el enrutamiento y la ejecución de controladores

//seguridad para cookies de sesión
$es_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $es_https,
    'httponly' => true,
    'samesite' => 'Lax',
]);

session_start();

require __DIR__ . "/configuracion/config.php";
require __DIR__ . "/configuracion/autoload.php";
require __DIR__ . "/configuracion/helpers.php";


$rutas = require __DIR__ . "/rutas/web.php";

$metodo_http = $_SERVER["REQUEST_METHOD"] ?? "GET";

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER["SCRIPT_NAME"]), "/");
$ruta = "/" . ltrim(str_replace($base, "", $path), "/");


if (isset($rutas[$metodo_http][$ruta])) {

    $ruta_config = $rutas[$metodo_http][$ruta];

    //ejecutar middleware si existe
    if (isset($ruta_config["middleware"])) {
        $middleware = $ruta_config["middleware"];
        $middleware::verificar();
    }

    $accion = $ruta_config["accion"]; 

    list($nombre_controlador, $metodo) = explode("@", $accion);

    $controlador = new $nombre_controlador();
    $controlador->$metodo();

  } else {
      http_response_code(404);
      echo "404 - ruta no encontrada";
    }  


?>
