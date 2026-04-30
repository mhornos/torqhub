<?php

class IdiomaControlador extends ControladorBase
{
    
// cambia el idioma elegido por el usuario y vuelve a la página anterior
    public function cambiar(): void {
        csrf_verificar();

        $idioma = $_POST['idioma'] ?? 'es';

        if (!in_array($idioma, idiomas_disponibles(), true)) {
            $idioma = 'es';
        }

        $_SESSION['idioma'] = $idioma;

        $this->redirigir($this->obtener_ruta_retorno());
    }

// evita redirecciones externas y convierte la url actual en una ruta interna
    private function obtener_ruta_retorno(): string {
        $volver = $_POST['volver'] ?? '/';

        $ruta = parse_url($volver, PHP_URL_PATH) ?: '/';
        $query = parse_url($volver, PHP_URL_QUERY);
        $base = rtrim(BASE_URL, '/');

        if ($base !== '' && str_starts_with($ruta, $base)) {
            $ruta = substr($ruta, strlen($base));
        }

        $ruta = '/' . ltrim($ruta, '/');

        if ($ruta === '/idioma') {
            $ruta = '/';
        }

        if ($query) {
            $ruta .= '?' . $query;
        }

        return $ruta;
    }
}

?>