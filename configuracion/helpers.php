<?php

// generar urls absolutas basadas en la base_url
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

// funciones para el token csrf

// genera un token csrf y lo almacena en la sesión si no existe
function csrf_token(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); //token seguro
    }
    return $_SESSION['csrf_token'];
}

// devuelve un campo oculto con el token csrf para incluir en los formularios
function csrf_campo(): string {
    $token = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

// verifica el token csrf enviado en el formulario contra el token almacenado en la sesión
function csrf_verificar(): void {
    $token_sesion = $_SESSION['csrf_token'] ?? '';
    $token_form = $_POST['csrf_token'] ?? '';

    if ($token_sesion === '' || $token_form === '' || !hash_equals($token_sesion, $token_form)) { 
        http_response_code(403);
        echo "403 - csrf invalido";
        exit;
    }
}


?>