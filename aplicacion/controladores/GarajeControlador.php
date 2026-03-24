<?php

class GarajeControlador extends ControladorBase {

//listas de opciones para los campos del vehículo
    private array $carrocerias_validas = [
        'coche pequeño',
        'sedán',
        'familiar',
        'cabrio',
        'coupé',
        'suv/4x4',
        'monovolumen',
        'furgoneta',
        'otros',
    ];

    private array $tipos_combustible_validos = [
        'gasolina',
        'diesel',
        'electrico',
        'electro/gasolina',
        'electro/diesel',
        'gas natural (CNG)',
        'etanol',
        'hidrogeno',
        'gas licuado (GLP)',
        'otros',
    ];
    
    private array $tipos_cambio_validos = [
        'automatico',
        'manual',
    ];


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
        $carroceria = trim($_POST['carroceria'] ?? '');
        $tipo_combustible = trim($_POST['tipo_combustible'] ?? '');
        $tipo_cambio = trim($_POST['tipo_cambio'] ?? '');
        $potencia_cv_txt = trim($_POST['potencia_cv'] ?? '');
        $cilindrada_cm3_txt = trim($_POST['cilindrada_cm3'] ?? '');

        $any = ($any_txt === '') ? null : (int) $any_txt;
        $vin = ($vin === '') ? null : $vin;
        
        $carroceria = ($carroceria === '') ? null : $carroceria;
        $tipo_combustible = ($tipo_combustible === '') ? null : $tipo_combustible;
        $tipo_cambio = ($tipo_cambio === '') ? null : $tipo_cambio;

        if (!is_null($carroceria) && !in_array($carroceria, $this->carrocerias_validas, true)) {
            flash_set('error', 'la carroceria no es valida');
            $this->redirigir('/garaje/nuevo');
        }

        if (!is_null($tipo_combustible) && !in_array($tipo_combustible, $this->tipos_combustible_validos, true)) {
            flash_set('error', 'el tipo de combustible no es valido');
            $this->redirigir('/garaje/nuevo');
        }

        if (!is_null($tipo_cambio) && !in_array($tipo_cambio, $this->tipos_cambio_validos, true)) {
            flash_set('error', 'el tipo de cambio no es valido');
            $this->redirigir('/garaje/nuevo');
        }

        if ($potencia_cv_txt === '') {
            $potencia_cv = null;
        } elseif (ctype_digit($potencia_cv_txt) && (int) $potencia_cv_txt >= 0) {
            $potencia_cv = (int) $potencia_cv_txt;
        } else {
            flash_set('error', 'la potencia en cv debe ser un numero entero positivo');
            $this->redirigir('/garaje/nuevo');
        }

        if ($cilindrada_cm3_txt === '') {
            $cilindrada_cm3 = null;
        } elseif (ctype_digit($cilindrada_cm3_txt) && (int) $cilindrada_cm3_txt >= 0) {
            $cilindrada_cm3 = (int) $cilindrada_cm3_txt;
        } else {
            flash_set('error', 'la cilindrada debe ser un numero entero positivo');
            $this->redirigir('/garaje/nuevo');
        }


        if ($marca === '' || $modelo === '') {
            flash_set('error', 'marca y modelo son obligatorios');
            $this->redirigir('/garaje/nuevo');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        try {
            RepositorioVehiculos::crear($usuario_id, $marca, $modelo, $any, $vin, $carroceria, $tipo_combustible, $tipo_cambio, $potencia_cv, $cilindrada_cm3);
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
        $carroceria = trim($_POST['carroceria'] ?? '');
        $tipo_combustible = trim($_POST['tipo_combustible'] ?? '');
        $tipo_cambio = trim($_POST['tipo_cambio'] ?? '');
        $potencia_cv_txt = trim($_POST['potencia_cv'] ?? '');
        $cilindrada_cm3_txt = trim($_POST['cilindrada_cm3'] ?? '');

        $any = ($any_txt === '') ? null : (int) $any_txt;
        $vin = ($vin === '') ? null : $vin;
        $carroceria = ($carroceria === '') ? null : $carroceria;
        $tipo_combustible = ($tipo_combustible === '') ? null : $tipo_combustible;
        $tipo_cambio = ($tipo_cambio === '') ? null : $tipo_cambio;
        $potencia_cv = ($potencia_cv_txt === '') ? null : (int) $potencia_cv_txt;
        $cilindrada_cm3 = ($cilindrada_cm3_txt === '') ? null : (int) $cilindrada_cm3_txt;

        if ($vehiculo_id <= 0) {
            flash_set('error', 'vehiculo no valido');
            $this->redirigir('/garaje');
        }

        if ($marca === '' || $modelo === '') {
            flash_set('error', 'marca y modelo son obligatorios');
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }

        $carroceria = ($carroceria === '') ? null : $carroceria;
        $tipo_combustible = ($tipo_combustible === '') ? null : $tipo_combustible;
        $tipo_cambio = ($tipo_cambio === '') ? null : $tipo_cambio;
        
        if (!is_null($carroceria) && !in_array($carroceria, $this->carrocerias_validas, true)) {
            flash_set('error', 'la carroceria no es valida');
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }
        
        if (!is_null($tipo_combustible) && !in_array($tipo_combustible, $this->tipos_combustible_validos, true)) {
            flash_set('error', 'el tipo de combustible no es valido');
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }
        
        if (!is_null($tipo_cambio) && !in_array($tipo_cambio, $this->tipos_cambio_validos, true)) {
            flash_set('error', 'el tipo de cambio no es valido');
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }
        
        if ($potencia_cv_txt === '') {
            $potencia_cv = null;
        } elseif (ctype_digit($potencia_cv_txt) && (int) $potencia_cv_txt >= 0) {
            $potencia_cv = (int) $potencia_cv_txt;
        } else {
            flash_set('error', 'la potencia en cv debe ser un numero entero positivo');
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }
        
        if ($cilindrada_cm3_txt === '') {
            $cilindrada_cm3 = null;
        } elseif (ctype_digit($cilindrada_cm3_txt) && (int) $cilindrada_cm3_txt >= 0) {
            $cilindrada_cm3 = (int) $cilindrada_cm3_txt;
        } else {
            flash_set('error', 'la cilindrada debe ser un numero entero positivo');
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }

        try {
            $actualizado = RepositorioVehiculos::actualizar($vehiculo_id, $usuario_id, $marca, $modelo, $any, $vin, $carroceria, $tipo_combustible, $tipo_cambio, $potencia_cv, $cilindrada_cm3);
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


//muestra el detalle de un vehículo, verificando que pertenece al usuario logueado
    public function ver(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['id'] ?? 0);
    
        if ($vehiculo_id <= 0) {
            flash_set('error', 'vehiculo no valido');
            $this->redirigir('/garaje');
        }
    
        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);
    
        if (!$vehiculo) {
            flash_set('error', 'vehiculo no encontrado');
            $this->redirigir('/garaje');
        }
    
        $mantenimientos = RepositorioMantenimientos::listar_por_vehiculo($vehiculo_id);

        $this->render('garaje/ver', [
            'vehiculo' => $vehiculo,
            'mantenimientos' => $mantenimientos,
        ]);
    }


//procesa el alta de un nuevo mantenimiento para un vehículo del usuario
    public function mantenimiento_nuevo_post(): void {
        csrf_verificar();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_POST['vehiculo_id'] ?? 0);

        $fecha = trim($_POST['fecha'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $kilometros_txt = trim($_POST['kilometros'] ?? '');
        $coste_txt = trim($_POST['coste'] ?? '');

        $_SESSION['mantenimiento_form'] = [
            'fecha' => $fecha,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'kilometros' => $kilometros_txt,
            'coste' => $coste_txt,
        ];

        if ($vehiculo_id <= 0) {
            flash_set('error', 'vehiculo no valido');
            $this->redirigir('/garaje');
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', 'vehiculo no encontrado o sin permisos');
            $this->redirigir('/garaje');
        }

        if ($fecha === '' || $tipo === '') {
            flash_set('error', 'la fecha y el tipo son obligatorios');
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        $fecha_objeto = DateTime::createFromFormat('Y-m-d', $fecha);
        $fecha_valida = $fecha_objeto && $fecha_objeto->format('Y-m-d') === $fecha;

        if (!$fecha_valida) {
            flash_set('error', 'la fecha no es valida');
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        if (mb_strlen($tipo) > 100) {
            flash_set('error', 'el tipo no puede superar los 100 caracteres');
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        $descripcion = ($descripcion === '') ? null : $descripcion;

        if ($kilometros_txt === '') {
            $kilometros = null;
        } elseif (ctype_digit($kilometros_txt)) {
            $kilometros = (int) $kilometros_txt;
        } else {
            flash_set('error', 'los kilometros deben ser un numero entero positivo');
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        if ($coste_txt === '') {
            $coste = null;
        } else {
            $coste_normalizado = str_replace(',', '.', $coste_txt);

            if (!is_numeric($coste_normalizado) || (float) $coste_normalizado < 0) {
                flash_set('error', 'el coste debe ser un numero valido mayor o igual que cero');
                $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
            }

            $coste = (float) $coste_normalizado;
        }

        try {
            RepositorioMantenimientos::crear(
                $vehiculo_id,
                $fecha,
                $tipo,
                $descripcion,
                $kilometros,
                $coste
            );
        } catch (PDOException $e) {
            flash_set('error', 'no se pudo guardar el mantenimiento');
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        unset($_SESSION['mantenimiento_form']);
        
        flash_set('ok', 'mantenimiento añadido correctamente');
        $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
    }


//muestra el formulario para crear un mantenimiento de un vehículo del usuario
    public function mantenimiento_nuevo(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['vehiculo_id'] ?? 0);

        if ($vehiculo_id <= 0) {
            flash_set('error', 'vehiculo no valido');
            $this->redirigir('/garaje');
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', 'vehiculo no encontrado o sin permisos');
            $this->redirigir('/garaje');
        }

        $datos_formulario = $_SESSION['mantenimiento_form'] ?? [];
        unset($_SESSION['mantenimiento_form']);

        $this->render('garaje/mantenimientos/nuevo', [
            'vehiculo' => $vehiculo,
            'datos_formulario' => $datos_formulario,
        ]);
    }


//muestra el formulario para editar un mantenimiento del usuario
    public function mantenimiento_editar(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $mantenimiento_id = (int) ($_GET['id'] ?? 0);

        if ($mantenimiento_id <= 0) {
            flash_set('error', 'mantenimiento no valido');
            $this->redirigir('/garaje');
        }

        $mantenimiento = RepositorioMantenimientos::buscar_por_id_y_usuario($mantenimiento_id, $usuario_id);

        if (!$mantenimiento) {
            flash_set('error', 'mantenimiento no encontrado o sin permisos');
            $this->redirigir('/garaje');
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario((int) $mantenimiento['vehiculo_id'], $usuario_id);

        if (!$vehiculo) {
            flash_set('error', 'vehiculo no encontrado o sin permisos');
            $this->redirigir('/garaje');
        }

        $datos_formulario = $_SESSION['mantenimiento_editar_form'] ?? [];
        unset($_SESSION['mantenimiento_editar_form']);

        $this->render('garaje/mantenimientos/editar', [
            'vehiculo' => $vehiculo,
            'mantenimiento' => $mantenimiento,
            'datos_formulario' => $datos_formulario,
        ]);
    }


//procesa la edicion de un mantenimiento del usuario
    public function mantenimiento_editar_post(): void {
        csrf_verificar();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $mantenimiento_id = (int) ($_POST['mantenimiento_id'] ?? 0);

        if ($mantenimiento_id <= 0) {
            flash_set('error', 'mantenimiento no valido');
            $this->redirigir('/garaje');
        }

        $mantenimiento = RepositorioMantenimientos::buscar_por_id_y_usuario($mantenimiento_id, $usuario_id);

        if (!$mantenimiento) {
            flash_set('error', 'mantenimiento no encontrado o sin permisos');
            $this->redirigir('/garaje');
        }

        $vehiculo_id = (int) $mantenimiento['vehiculo_id'];

        $fecha = trim($_POST['fecha'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $kilometros_txt = trim($_POST['kilometros'] ?? '');
        $coste_txt = trim($_POST['coste'] ?? '');

        $_SESSION['mantenimiento_editar_form'] = [
            'fecha' => $fecha,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'kilometros' => $kilometros_txt,
            'coste' => $coste_txt,
        ];

        if ($fecha === '' || $tipo === '') {
            flash_set('error', 'la fecha y el tipo son obligatorios');
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        $fecha_objeto = DateTime::createFromFormat('Y-m-d', $fecha);
        $fecha_valida = $fecha_objeto && $fecha_objeto->format('Y-m-d') === $fecha;

        if (!$fecha_valida) {
            flash_set('error', 'la fecha no es valida');
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        if (mb_strlen($tipo) > 100) {
            flash_set('error', 'el tipo no puede superar los 100 caracteres');
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        $descripcion = ($descripcion === '') ? null : $descripcion;

        if ($kilometros_txt === '') {
            $kilometros = null;
        } elseif (ctype_digit($kilometros_txt)) {
            $kilometros = (int) $kilometros_txt;
        } else {
            flash_set('error', 'los kilometros deben ser un numero entero positivo');
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        if ($coste_txt === '') {
            $coste = null;
        } else {
            $coste_normalizado = str_replace(',', '.', $coste_txt);

            if (!is_numeric($coste_normalizado) || (float) $coste_normalizado < 0) {
                flash_set('error', 'el coste debe ser un numero valido mayor o igual que cero');
                $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
            }

            $coste = (float) $coste_normalizado;
        }

        try {
            RepositorioMantenimientos::actualizar(
                $mantenimiento_id,
                $vehiculo_id,
                $fecha,
                $tipo,
                $descripcion,
                $kilometros,
                $coste
            );
        } catch (PDOException $e) {
            flash_set('error', 'no se pudo actualizar el mantenimiento');
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        unset($_SESSION['mantenimiento_editar_form']);

        flash_set('ok', 'mantenimiento actualizado correctamente');
        $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
    }

    
//elimina un mantenimiento del usuario
    public function mantenimiento_eliminar_post(): void {
        csrf_verificar();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $mantenimiento_id = (int) ($_POST['mantenimiento_id'] ?? 0);

        if ($mantenimiento_id <= 0) {
            flash_set('error', 'mantenimiento no valido');
            $this->redirigir('/garaje');
        }

        $mantenimiento = RepositorioMantenimientos::buscar_por_id_y_usuario($mantenimiento_id, $usuario_id);

        if (!$mantenimiento) {
            flash_set('error', 'mantenimiento no encontrado o sin permisos');
            $this->redirigir('/garaje');
        }

        $vehiculo_id = (int) $mantenimiento['vehiculo_id'];

        try {
            RepositorioMantenimientos::eliminar($mantenimiento_id, $vehiculo_id);
        } catch (PDOException $e) {
            flash_set('error', 'no se pudo eliminar el mantenimiento');
            $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
        }

        flash_set('ok', 'mantenimiento eliminado correctamente');
        $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
    }
}

?>