<?php
//recibe los síntomas del vehículo, los procesa con el motor de diagnóstico y muestra los resultados al usuario.
class DiagnosticoControlador extends ControladorBase
{

// muestra el formulario de diagnóstico y los mensajes anteriores
    public function index(): void {
        if (!isset($_SESSION['diagnostico_mensajes'])) {
            $_SESSION['diagnostico_mensajes'] = [];
        }

        $this->render('diagnostico/index', [
            'mensajes' => $_SESSION['diagnostico_mensajes'],
        ]);
    }

// procesa los síntomas enviados por el usuario, obtiene los resultados del motor de diagnóstico y los guarda en la sesión para mostrarlos luego
    public function analizar(): void {
        csrf_verificar();

        $sintomas = trim($_POST['sintomas'] ?? '');

        if ($sintomas === '') {
            flash_set('error', t('diagnostico.error.sintomas_obligatorios'));
            $this->redirigir('/diagnostico');
        }

        if (!isset($_SESSION['diagnostico_mensajes'])) {
            $_SESSION['diagnostico_mensajes'] = [];
        }

        $_SESSION['diagnostico_mensajes'][] = [
            'tipo' => 'usuario',
            'texto' => $sintomas,
        ];

        $motor = new MotorDiagnosticoIA();
        $resultados = $motor->diagnosticar($sintomas);

        $_SESSION['diagnostico_mensajes'][] = [
            'tipo' => 'ia',
            'resultados' => $resultados,
        ];

        $this->redirigir('/diagnostico');
    }

// reinicia el diagnóstico borrando los mensajes de la sesión
    public function reiniciar(): void {
        csrf_verificar();
    
        unset($_SESSION['diagnostico_mensajes']);
    
        $this->redirigir('/diagnostico');
    }

// versión ajax del método analizar para procesar los síntomas sin recargar la página
    public function ajaxAnalizar(): void {
    header('Content-Type: application/json; charset=utf-8');

    csrf_verificar();

    $sintomas = trim($_POST['sintomas'] ?? '');

    if ($sintomas === '') {
        http_response_code(422);

        echo json_encode([
            'ok' => false,
            'mensaje' => t('diagnostico.error.sintomas_obligatorios')
        ], JSON_UNESCAPED_UNICODE);

        return;
    }

    if (!isset($_SESSION['diagnostico_mensajes'])) {
        $_SESSION['diagnostico_mensajes'] = [];
    }

    $motor = new MotorDiagnosticoIA();
    $resultados = $motor->diagnosticar($sintomas);

    $_SESSION['diagnostico_mensajes'][] = [
        'tipo' => 'usuario',
        'texto' => $sintomas,
    ];

    $_SESSION['diagnostico_mensajes'][] = [
        'tipo' => 'ia',
        'resultados' => $resultados,
    ];

    echo json_encode([
        'ok' => true,
        'mensaje_usuario' => $sintomas,
        'resultados' => $resultados
    ], JSON_UNESCAPED_UNICODE);
}
}