<?php

class GarajeControlador extends ControladorBase {
    public function index(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];

        $vehiculos = RepositorioVehiculos::listar_por_usuario($usuario_id);

        $this->render('garaje/index', [
            'vehiculos' => $vehiculos
        ]);
    }

    public function nuevo(): void {
        $this->render('garaje/nuevo');
    }

    public function nuevo_post(): void {
        csrf_verificar();

        $marca = trim($_POST['marca'] ?? '');
        $modelo = trim($_POST['modelo'] ?? '');
        $any_txt = trim($_POST['any'] ?? '');
        $vin = trim($_POST['vin'] ?? '');

        $any = ($any_txt === '') ? null : (int) $any_txt;
        $vin = ($vin === '') ? null : $vin;

        if ($marca === '' || $modelo === '') {
            flash_set('error', 'marca y modelo son obligatorios');
            $this->redirigir('/garaje/nuevo');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        try {
            RepositorioVehiculos::crear($usuario_id, $marca, $modelo, $any, $vin);
        } catch (PDOException $e) {
    http_response_code(500);
    echo "error pdo al guardar vehiculo: " . htmlspecialchars($e->getMessage());
    exit;
        }

        flash_set('ok', 'vehiculo añadido');
        $this->redirigir('/garaje');
    }
}

?>