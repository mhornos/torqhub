<?php
// autoload para cargar clases automáticamente desde las carpetas de controladores, modelos, servicios y middlewares

spl_autoload_register(function (string $nombre_clase): void{

    $carpetas = [
        __DIR__ . "/../aplicacion/controladores/", 
        __DIR__ . "/../aplicacion/modelos/",
        __DIR__ . "/../aplicacion/servicios/",
        __DIR__ . "/../aplicacion/middlewares/",
    ];

    foreach ($carpetas as $carpeta){
        $ruta = $carpeta . $nombre_clase . ".php";

        if (file_exists($ruta)){
            require $ruta;
            return;
        }
    }
});

?>