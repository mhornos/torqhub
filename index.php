<?php
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

    $accion = $rutas[$metodo_http][$ruta]; 
    list($nombre_controlador, $metodo) = explode("@", $accion);

    $controlador = new $nombre_controlador();
    $controlador->$metodo();

  } else {
      http_response_code(404);
      echo "404 - ruta no encontrada";
    }  


?>
