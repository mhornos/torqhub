<?php

function url(string $ruta = ""): string {
    return rtrim(BASE_URL, "/") . "/" . ltrim($ruta, "/");
}


// comprobacion web del login
function flash_set(string $clave, string $mensaje): void
{
    $_SESSION['flash'][$clave] = $mensaje;
}

function flash_get(string $clave): ?string
{
    if (!isset($_SESSION['flash'][$clave])) {
        return null;
    }

    $mensaje = $_SESSION['flash'][$clave];
    unset($_SESSION['flash'][$clave]);

    return $mensaje;
}


?>