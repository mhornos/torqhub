<?php

class GarajeControlador extends ControladorBase {

    //muestra la lista de vehículos del usuario logueado
    public function index(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];

        $vehiculos = RepositorioVehiculos::listar_por_usuario($usuario_id);

        $this->render('garaje/index', [
            'vehiculos' => $vehiculos
        ]);
    }

    //muestra el formulario para añadir un nuevo vehículo
    public function nuevo(): void {
        $this->render('garaje/nuevo');
    }

    //procesa el formulario para añadir un nuevo vehículo, valida los datos y redirige al garaje
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

    //muestra la confirmación para eliminar un vehículo, verifica que el vehículo existe y pertenece al usuario
    public function eliminar(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['id'] ?? 0);

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', 'vehiculo no encontrado');
            $this->redirigir('/garaje');
        }

        $this->render('garaje/eliminar', [
            'vehiculo' => $vehiculo,
        ]);
    }

    //procesa la eliminación de un vehículo, verifica que el vehículo existe y pertenece al usuario, luego lo elimina y redirige al garaje
    public function eliminar_post(): void {
        csrf_verificar();
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_POST['id'] ?? 0);
        if ($vehiculo_id <= 0) {
            flash_set('error', 'vehiculo no valido');
            $this->redirigir('/garaje');
        }
        try {
            $eliminado = RepositorioVehiculos::eliminar($vehiculo_id, $usuario_id);
        } catch (PDOException $e) {
            flash_set('error', 'no se pudo eliminar el vehiculo');
            $this->redirigir('/garaje');
        }
        if (!$eliminado) {
            flash_set('error', 'vehiculo no encontrado o sin permisos');
            $this->redirigir('/garaje');
        }
        flash_set('ok', 'vehiculo eliminado correctamente');
        $this->redirigir('/garaje');
    }

    //muestra el formulario para editar un vehículo, verifica que el vehículo existe y pertenece al usuario
    public function editar(): void{
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['id'] ?? 0);

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', 'vehiculo no encontrado');
            $this->redirigir('/garaje');
        }

        $this->render('garaje/editar', [
            'vehiculo' => $vehiculo,
        ]);
    }

    //procesa la edición de un vehículo, verifica que el vehículo existe y pertenece al usuario, luego lo actualiza y redirige al garaje
    public function editar_post(): void {
        csrf_verificar();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_POST['id'] ?? 0);

        $marca = trim($_POST['marca'] ?? '');
        $modelo = trim($_POST['modelo'] ?? '');
        $any_txt = trim($_POST['any'] ?? '');
        $vin = trim($_POST['vin'] ?? '');

        $any = ($any_txt === '') ? null : (int) $any_txt;
        $vin = ($vin === '') ? null : $vin;

        if ($vehiculo_id <= 0) {
            flash_set('error', 'vehiculo no valido');
            $this->redirigir('/garaje');
        }

        if ($marca === '' || $modelo === '') {
            flash_set('error', 'marca y modelo son obligatorios');
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }

        try {
            $actualizado = RepositorioVehiculos::actualizar($vehiculo_id, $usuario_id, $marca, $modelo, $any, $vin);
        } catch (PDOException $e) {
            flash_set('error', 'no se pudo actualizar el vehiculo');
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }

        if (!$actualizado) {
            flash_set('error', 'vehiculo no encontrado o sin permisos');
            $this->redirigir('/garaje');
        }

        flash_set('ok', 'vehiculo actualizado correctamente');
        $this->redirigir('/garaje');
    }
}

?>