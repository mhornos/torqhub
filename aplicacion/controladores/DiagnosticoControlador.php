<?php

//controlador para manejar el diagnostico de problemas en el vehiculo basandose en los sintomas descritos por el usuario
class DiagnosticoControlador extends ControladorBase
{

// muestra el formulario para ingresar los sintomas del vehiculo
    public function index(): void {
        $this->render('diagnostico/index', [
            'sintomas' => '',
            'resultados' => [],
        ]);
    }


/* procesa el formulario, obtiene los sintomas ingresados por el usuario, utiliza el 
motor de diagnostico para analizar los sintomas y muestra los resultados al usuario */
    public function analizar(): void {
        csrf_verificar();

        $sintomas = trim($_POST['sintomas'] ?? '');

        if ($sintomas === '') {
            flash_set('error', 'debes escribir los sintomas del vehiculo');
            $this->redirigir('/diagnostico');
        }

        $motor = new MotorDiagnosticoIA();
        $resultados = $motor->diagnosticar($sintomas);

        $this->render('diagnostico/index', [
            'sintomas' => $sintomas,
            'resultados' => $resultados,
        ]);
    }
}