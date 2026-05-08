<?php

// generar urls absolutas basadas en la base_url
function url(string $ruta = ""): string{
    return rtrim(BASE_URL, "/") . "/" . ltrim($ruta, "/");
}

// comprobacion web del login
function flash_set(string $clave, string $mensaje): void{
    $_SESSION['flash'][$clave] = $mensaje;
}

function flash_get(string $clave): ?string{
    if (!isset($_SESSION['flash'][$clave])) {
        return null;
    }

    $mensaje = $_SESSION['flash'][$clave];
    unset($_SESSION['flash'][$clave]);

    return $mensaje;
}

// FUNCIONES TOKEN CSRF

// detecta si la petición espera una respuesta ajax/json
function peticion_ajax(): bool{
    $cabecera_ajax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
    $cabecera_accept = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');

    return $cabecera_ajax === 'xmlhttprequest' || str_contains($cabecera_accept, 'application/json');
}

// devuelve una respuesta json limpia y detiene la ejecución
function respuesta_json(array $datos, int $codigo_http = 200): void{
    http_response_code($codigo_http);
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

// escapa texto para salida html segura
function escapar(mixed $valor): string{
    return htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// genera una url segura para archivos dentro de /public
function url_publica_segura(?string $ruta_relativa): string{
    $ruta = trim((string) $ruta_relativa);
    $ruta = str_replace('\\', '/', $ruta);
    $ruta = str_replace("\0", '', $ruta);

    $partes = explode('/', $ruta);

    $partes_seguras = [];

    foreach ($partes as $parte) {
        if ($parte === '' || $parte === '.' || $parte === '..') {
            continue;
        }

        $partes_seguras[] = rawurlencode($parte);
    }

    return url('/public/' . implode('/', $partes_seguras));
}

// genera un token csrf y lo almacena en la sesión si no existe
function csrf_token(): string{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); //token seguro
    }
    return $_SESSION['csrf_token'];
}

// devuelve un campo oculto con el token csrf para incluir en los formularios
function csrf_campo(): string{
    $token = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

// verifica el token csrf enviado en el formulario contra el token almacenado en la sesión
function csrf_verificar(): void{
    $token_sesion = $_SESSION['csrf_token'] ?? '';
    $token_form = $_POST['csrf_token'] ?? '';

    if ($token_sesion === '' || $token_form === '' || !hash_equals($token_sesion, $token_form)) {
        mostrar_error_http(
            403,
            t('error.403.titulo'),
            t('seguridad.error.csrf'),
            t('error.403.detalle'),
            t('error.boton.inicio'),
            url('/')
        );
    }
}

// función para formatear fechas en un formato legible
function formatear_fecha(string $fecha): string{
    $timestamp = strtotime($fecha);

    if ($timestamp === false) {
        return $fecha;
    }

    return date('d/m/Y H:i:s', $timestamp);
}

// FUNCIONES PARA IDIOMAS

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

// genera una url de retorno segura basada en la cabecera Referer, evitando redirecciones abiertas
function url_volver_segura(string $fallback): string{
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    if ($referer === '') {
        return $fallback;
    }

    $host_actual = $_SERVER['HTTP_HOST'] ?? '';
    $host_referer = parse_url($referer, PHP_URL_HOST);

    if ($host_referer !== null && $host_referer !== $host_actual) {
        return $fallback;
    }

    $ruta = parse_url($referer, PHP_URL_PATH) ?: $fallback;
    $query = parse_url($referer, PHP_URL_QUERY);

    return $ruta . ($query ? '?' . $query : '');
}

// renderiza una página de error html manteniendo respuestas json limpias para ajax
function mostrar_error_http( int $codigo_http, string $titulo, string $mensaje, string $detalle = '', ?string $texto_boton_principal = null, ?string $url_boton_principal = null):void {
    http_response_code($codigo_http);

    if (peticion_ajax()) {
        respuesta_json([
            'ok' => false,
            'mensaje' => $mensaje,
        ], $codigo_http);
    }

    Vista::render('errores/http', [
        'codigo_error' => $codigo_http,
        'titulo_error' => $titulo,
        'mensaje_error' => $mensaje,
        'detalle_error' => $detalle !== '' ? $detalle : t('error.descripcion.generica'),
        'texto_boton_principal' => $texto_boton_principal ?? t('error.boton.inicio'),
        'url_boton_principal' => $url_boton_principal ?? url('/'),
    ]);

    exit;
}
