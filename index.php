<?php

require __DIR__ . '/configuracion/config.php';
require __DIR__ . '/aplicacion/servicios/Vista.php';

$rutas = require __DIR__ . '/rutas/web.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

$ruta = '/' . ltrim(str_replace($base, '', $path), '/');

if (isset($rutas[$ruta])) {
  $accion = $rutas[$ruta]; 

list($nombre_controlador, $metodo) = explode('@', $accion);

require __DIR__ . '/aplicacion/controladores/' . $nombre_controlador . '.php';

$controlador = new $nombre_controlador();
$controlador->$metodo();
} else {
  http_response_code(404);
  echo "404 - ruta no encontrada";
}

?>