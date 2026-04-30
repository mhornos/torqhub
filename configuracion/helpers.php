<?php

// generar urls absolutas basadas en la base_url
function url(string $ruta = ""): string {
    return rtrim(BASE_URL, "/") . "/" . ltrim($ruta, "/");
}

// comprobacion web del login
function flash_set(string $clave, string $mensaje): void {
    $_SESSION['flash'][$clave] = $mensaje;
}

function flash_get(string $clave): ?string {
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

// función para formatear fechas en un formato legible
function formatear_fecha(string $fecha): string{
    $timestamp = strtotime($fecha);

    if ($timestamp === false) {
        return $fecha;
    }

    return date('d-m-Y H:i:s', $timestamp);
}


// devuelve los idiomas disponibles en la aplicación
function idiomas_disponibles(): array{
    return ['es', 'ca'];
}

// devuelve el idioma actual guardado en sesión
function idioma_actual(): string{
    $idioma = $_SESSION['idioma'] ?? 'es';

    if (!in_array($idioma, idiomas_disponibles(), true)) {
        $idioma = 'es';
        $_SESSION['idioma'] = $idioma;
    }

    return $idioma;
}

// carga el archivo de traducciones del idioma actual
function traducciones_actuales(): array{
    static $traducciones = null;
    static $idioma_cargado = null;

    $idioma = idioma_actual();

    if ($traducciones !== null && $idioma_cargado === $idioma) {
        return $traducciones;
    }

    $ruta_idioma = __DIR__ . '/idiomas/' . $idioma . '.php';
    $ruta_defecto = __DIR__ . '/idiomas/es.php';

    if (!file_exists($ruta_idioma)) {
        $ruta_idioma = $ruta_defecto;
    }

    $traducciones = require $ruta_idioma;
    $idioma_cargado = $idioma;

    return is_array($traducciones) ? $traducciones : [];
}

// traduce una clave de texto según el idioma actual
function t(string $clave): string{
    $traducciones = traducciones_actuales();

    return $traducciones[$clave] ?? $clave;
}

?>