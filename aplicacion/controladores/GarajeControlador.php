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
        $this->render('garaje/nuevo', [
            'scripts' => [
                '/public/js/garaje/formulario.js',
                '/public/js/garaje/vin.js',
            ],
        ]);
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
        $archivos_imagenes = $this->normalizar_archivos_multiples_vehiculo($_FILES['imagenes'] ?? null);

        $any = ($any_txt === '') ? null : (int) $any_txt;
        $vin = ($vin === '') ? null : $vin;
        
        $carroceria = ($carroceria === '') ? null : $carroceria;
        $tipo_combustible = ($tipo_combustible === '') ? null : $tipo_combustible;
        $tipo_cambio = ($tipo_cambio === '') ? null : $tipo_cambio;

        if (!is_null($carroceria) && !in_array($carroceria, $this->carrocerias_validas, true)) {
            flash_set('error', t('garaje.vehiculo.error.carroceria_no_valida'));
            $this->redirigir('/garaje/nuevo');
        }

        if (!is_null($tipo_combustible) && !in_array($tipo_combustible, $this->tipos_combustible_validos, true)) {
            flash_set('error', t('garaje.vehiculo.error.combustible_no_valido'));
            $this->redirigir('/garaje/nuevo');
        }

        if (!is_null($tipo_cambio) && !in_array($tipo_cambio, $this->tipos_cambio_validos, true)) {
            flash_set('error', t('garaje.vehiculo.error.cambio_no_valido'));
            $this->redirigir('/garaje/nuevo');
        }

        if ($potencia_cv_txt === '') {
            $potencia_cv = null;
        } elseif (ctype_digit($potencia_cv_txt) && (int) $potencia_cv_txt >= 0) {
            $potencia_cv = (int) $potencia_cv_txt;
        } else {
            flash_set('error', t('garaje.vehiculo.error.potencia_entero'));
            $this->redirigir('/garaje/nuevo');
        }

        if ($cilindrada_cm3_txt === '') {
            $cilindrada_cm3 = null;
        } elseif (ctype_digit($cilindrada_cm3_txt) && (int) $cilindrada_cm3_txt >= 0) {
            $cilindrada_cm3 = (int) $cilindrada_cm3_txt;
        } else {
            flash_set('error', t('garaje.vehiculo.error.cilindrada_entero'));
            $this->redirigir('/garaje/nuevo');
        }


        if ($marca === '' || $modelo === '' || $any === null) {
            flash_set('error', t('garaje.vehiculo.error.marca_modelo_any_obligatorios'));
            $this->redirigir('/garaje/nuevo');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        $imagenes_guardadas = [];

        try {
            $imagenes_guardadas = $this->guardar_imagenes_vehiculo($archivos_imagenes);
        } catch (RuntimeException $e) {
            flash_set('error', $e->getMessage());
            $this->redirigir('/garaje/nuevo');
        }

        $imagen = $imagenes_guardadas[0] ?? null;

        try {
            $vehiculo_id = RepositorioVehiculos::crear($usuario_id, $marca, $modelo, $any, $vin, $carroceria, $tipo_combustible, $tipo_cambio, $potencia_cv, $cilindrada_cm3, $imagen);

            if (!empty($imagenes_guardadas)) {
                RepositorioVehiculoImagenes::insertar_varias($vehiculo_id, $imagenes_guardadas);
            }
        } catch (PDOException $e) {
            foreach ($imagenes_guardadas as $imagen_guardada) {
                $this->eliminar_archivo_imagen_vehiculo($imagen_guardada);
            }

            flash_set('error', t('garaje.vehiculo.error.guardar'));
            $this->redirigir('/garaje/nuevo');
        }

        flash_set('ok', t('garaje.vehiculo.ok.creado'));
        $this->redirigir('/garaje');
    }


    //muestra la confirmación para eliminar un vehículo, verifica que el vehículo existe y pertenece al usuario
    public function eliminar(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['id'] ?? 0);

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', t('garaje.vehiculo.error.no_encontrado'));
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
            flash_set('error', t('garaje.vehiculo.error.no_valido'));
            $this->redirigir('/garaje');
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);
        $imagenes_vehiculo = RepositorioVehiculoImagenes::listar_por_vehiculo_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', t('garaje.vehiculo.error.no_encontrado_permisos'));
            $this->redirigir('/garaje');
        }

        try {
            $eliminado = RepositorioVehiculos::eliminar($vehiculo_id, $usuario_id);
        } catch (PDOException $e) {
            flash_set('error', t('garaje.vehiculo.error.eliminar'));
            $this->redirigir('/garaje');
        }

        if ($eliminado) {
            $imagenes_a_eliminar = [];

            if (!empty($vehiculo['imagen'])) {
                $imagenes_a_eliminar[] = $vehiculo['imagen'];
            }

            foreach ($imagenes_vehiculo as $imagen_vehiculo) {
                if (!empty($imagen_vehiculo['nombre_archivo'])) {
                    $imagenes_a_eliminar[] = $imagen_vehiculo['nombre_archivo'];
                }
            }

            foreach (array_unique($imagenes_a_eliminar) as $nombre_archivo) {
                $this->eliminar_archivo_imagen_vehiculo($nombre_archivo);
            }
        }

        if (!$eliminado) {
            flash_set('error', t('garaje.vehiculo.error.no_encontrado_permisos'));
            $this->redirigir('/garaje');
        }
        flash_set('ok', t('garaje.vehiculo.ok.eliminado'));
        $this->redirigir('/garaje');
    }


//muestra el formulario para editar un vehículo, verifica que el vehículo existe y pertenece al usuario
    public function editar(): void{
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['id'] ?? 0);

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', t('garaje.vehiculo.error.no_encontrado'));
            $this->redirigir('/garaje');
        }

        $this->render('garaje/editar', [
            'vehiculo' => $vehiculo,
            'scripts' => [
                '/public/js/garaje/formulario.js',
                '/public/js/garaje/vin.js',
            ],
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
        $archivos_imagenes = $this->normalizar_archivos_multiples_vehiculo($_FILES['imagenes'] ?? null);

        if ($vehiculo_id <= 0) {
            flash_set('error', t('garaje.vehiculo.error.no_valido'));
            $this->redirigir('/garaje');
        }

        $vehiculo_actual = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo_actual) {
            flash_set('error', t('garaje.vehiculo.error.no_encontrado'));
            $this->redirigir('/garaje');
        }

        if ($marca === '' || $modelo === '') {
            flash_set('error', t('garaje.vehiculo.error.marca_modelo_obligatorios'));
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }

        $carroceria = ($carroceria === '') ? null : $carroceria;
        $tipo_combustible = ($tipo_combustible === '') ? null : $tipo_combustible;
        $tipo_cambio = ($tipo_cambio === '') ? null : $tipo_cambio;
        
        if (!is_null($carroceria) && !in_array($carroceria, $this->carrocerias_validas, true)) {
            flash_set('error', t('garaje.vehiculo.error.carroceria_no_valida'));
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }
        
        if (!is_null($tipo_combustible) && !in_array($tipo_combustible, $this->tipos_combustible_validos, true)) {
            flash_set('error', t('garaje.vehiculo.error.combustible_no_valido'));
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }
        
        if (!is_null($tipo_cambio) && !in_array($tipo_cambio, $this->tipos_cambio_validos, true)) {
            flash_set('error', t('garaje.vehiculo.error.cambio_no_valido'));
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }
        
        if ($potencia_cv_txt === '') {
            $potencia_cv = null;
        } elseif (ctype_digit($potencia_cv_txt) && (int) $potencia_cv_txt >= 0) {
            $potencia_cv = (int) $potencia_cv_txt;
        } else {
            flash_set('error', t('garaje.vehiculo.error.potencia_entero'));
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }
        
        if ($cilindrada_cm3_txt === '') {
            $cilindrada_cm3 = null;
        } elseif (ctype_digit($cilindrada_cm3_txt) && (int) $cilindrada_cm3_txt >= 0) {
            $cilindrada_cm3 = (int) $cilindrada_cm3_txt;
        } else {
            flash_set('error', t('garaje.vehiculo.error.cilindrada_entero'));
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }

        $imagen = $vehiculo_actual['imagen'] ?? null;
        $imagenes_guardadas = [];

        try {
            $imagenes_guardadas = $this->guardar_imagenes_vehiculo($archivos_imagenes);
        } catch (RuntimeException $e) {
            flash_set('error', $e->getMessage());
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }

        if (empty($imagen) && !empty($imagenes_guardadas)) {
            $imagen = $imagenes_guardadas[0];
        }

        try {
            $actualizado = RepositorioVehiculos::actualizar($vehiculo_id, $usuario_id, $marca, $modelo, $any, $vin, $carroceria, $tipo_combustible, $tipo_cambio, $potencia_cv, $cilindrada_cm3, $imagen);
        } catch (PDOException $e) {
            flash_set('error', t('garaje.vehiculo.error.actualizar'));
            $this->redirigir('/garaje/editar?id=' . $vehiculo_id);
        }

        if (!$actualizado) {
            flash_set('error', t('garaje.vehiculo.error.no_encontrado_permisos'));
            $this->redirigir('/garaje');
        }

        try {
            RepositorioVehiculoImagenes::insertar_varias($vehiculo_id, $imagenes_guardadas);
        } catch (PDOException $e) {
            error_log('Error guardando imágenes adicionales del vehículo: ' . $e->getMessage());
        }

        flash_set('ok', t('garaje.vehiculo.ok.actualizado'));
        $this->redirigir('/garaje');
    }


//muestra el detalle de un vehículo, verificando que pertenece al usuario logueado
    public function ver(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['id'] ?? 0);

        if ($vehiculo_id <= 0) {
            flash_set('error', t('garaje.vehiculo.error.no_valido'));
            $this->redirigir('/garaje');
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', t('garaje.vehiculo.error.no_encontrado'));
            $this->redirigir('/garaje');
        }

        try {
            $filtros = $this->obtener_filtros_mantenimiento();
        } catch (InvalidArgumentException $e) {
            flash_set('error', $e->getMessage());
            $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
        }

        //obtener el total de mantenimientos filtrados para calcular la paginación
        $pagina_actual = $this->obtener_pagina_historial();
        $por_pagina = 5;
        $total_mantenimientos_filtrados = RepositorioMantenimientos::contar_filtrados_por_vehiculo($vehiculo_id, $filtros);
        $total_paginas = max(1, (int) ceil($total_mantenimientos_filtrados / $por_pagina));

        if ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }

        $offset = ($pagina_actual - 1) * $por_pagina;

        $mantenimientos = RepositorioMantenimientos::filtrar_por_vehiculo($vehiculo_id, $filtros, $por_pagina, $offset);
        $tipos_mantenimiento = RepositorioMantenimientos::listar_tipos_por_vehiculo($vehiculo_id);
        $resumen_mantenimientos = RepositorioMantenimientos::obtener_resumen_filtrado_por_vehiculo($vehiculo_id, $filtros);
        $estadisticas_vehiculo = RepositorioMantenimientos::obtener_estadisticas_rapidas_por_vehiculo($vehiculo_id);

        $imagenes_vehiculo = RepositorioVehiculoImagenes::listar_por_vehiculo_y_usuario($vehiculo_id, $usuario_id);

        if (empty($imagenes_vehiculo) && !empty($vehiculo['imagen'])) {
            $imagenes_vehiculo = [
                [
                    'id' => 0,
                    'vehiculo_id' => $vehiculo_id,
                    'nombre_archivo' => $vehiculo['imagen'],
                    'texto_alt' => t('garaje.detalle.alt_imagen') . ' ' . $vehiculo['marca'] . ' ' . $vehiculo['modelo'],
                    'principal' => 1,
                    'orden' => 1,
                ],
            ];
        }

        $this->render('garaje/ver', [
            'vehiculo' => $vehiculo,
            'mantenimientos' => $mantenimientos,
            'tipos_mantenimiento' => $tipos_mantenimiento,
            'filtros' => $filtros,
            'resumen_mantenimientos' => $resumen_mantenimientos,
            'estadisticas_vehiculo' => $estadisticas_vehiculo,
            'pagina_actual' => $pagina_actual,
            'por_pagina' => $por_pagina,
            'total_paginas' => $total_paginas,
            'total_mantenimientos_filtrados' => $total_mantenimientos_filtrados,
            'imagenes_vehiculo' => $imagenes_vehiculo,
        ]);
    }

//exporta el historial de mantenimiento filtrado a un archivo csv, verificando que el vehículo pertenece al usuario logueado
    public function mantenimientos_exportar_csv(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['vehiculo_id'] ?? $_GET['id'] ?? 0);

        if ($vehiculo_id <= 0) {
            flash_set('error', t('garaje.vehiculo.error.no_valido'));
            $this->redirigir('/garaje');
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', t('garaje.vehiculo.error.no_encontrado'));
            $this->redirigir('/garaje');
        }

        try {
            $filtros = $this->obtener_filtros_mantenimiento();
        } catch (InvalidArgumentException $e) {
            flash_set('error', $e->getMessage());
            $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
        }

        $mantenimientos = RepositorioMantenimientos::filtrar_por_vehiculo($vehiculo_id, $filtros);

        $nombre_archivo = 'historial_' 
            . preg_replace('/[^a-zA-Z0-9_-]/', '_', $vehiculo['marca']) . '_'
            . preg_replace('/[^a-zA-Z0-9_-]/', '_', $vehiculo['modelo']) . '_'
            . date('Y-m-d') 
            . '.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');

        $salida = fopen('php://output', 'w');

        if ($salida === false) {
            exit;
        }

        fprintf($salida, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($salida, [
            'fecha',
            'tipo',
            'descripcion',
            'kilometros',
            'coste',
            'fecha_creacion',
        ], ';');

        foreach ($mantenimientos as $mantenimiento) {
            fputcsv($salida, [
                $mantenimiento['fecha'] ?? '',
                $mantenimiento['tipo'] ?? '',
                $mantenimiento['descripcion'] ?? '',
                $mantenimiento['kilometros'] ?? '',
                $mantenimiento['coste'] ?? '',
                $mantenimiento['fecha_creacion'] ?? '',
            ], ';');
        }

        fclose($salida);
        exit;
    }


//consulta un vin mediante ajax y devuelve datos preparados para autocompletar el formulario
    public function consultar_vin(): void {
        csrf_verificar();

        $vin = trim($_POST['vin'] ?? '');

        if ($vin === '') {
            respuesta_json([
                'ok' => false,
                'mensaje' => t('garaje.vin.error.obligatorio'),
            ], 422);
        }

        try {
            $servicio_vin = new ServicioVin();
            $resultado = $servicio_vin->consultar($vin);

            respuesta_json([
                'ok' => true,
                'mensaje' => $resultado['origen'] === 'cache'
                    ? t('garaje.vin.ok.cache')
                    : t('garaje.vin.ok.api'),
                'origen' => $resultado['origen'],
                'vin' => $resultado['vin'],
                'campos_torqhub' => $resultado['campos_torqhub'],
            ]);
        } catch (InvalidArgumentException $e) {
            respuesta_json([
                'ok' => false,
                'mensaje' => $e->getMessage(),
            ], 422);
        } catch (RuntimeException $e) {
            error_log('Error consultando VIN: ' . $e->getMessage());

            respuesta_json([
                'ok' => false,
                'mensaje' => t('garaje.vin.error.consulta_api'),
            ], 502);
        } catch (PDOException $e) {
            error_log('Error de caché VIN: ' . $e->getMessage());

            respuesta_json([
                'ok' => false,
                'mensaje' => t('garaje.vin.error.cache'),
            ], 500);
        } catch (Throwable $e) {
            error_log('Error inesperado en consulta VIN: ' . $e->getMessage());

            respuesta_json([
                'ok' => false,
                'mensaje' => t('garaje.vin.error.consulta_api'),
            ], 500);
        }
    }


//recoge y valida los filtros del historial de mantenimiento enviados por get
    private function obtener_filtros_mantenimiento(): array
    {
        $tipo = trim($_GET['tipo'] ?? '');
        $fecha_desde = trim($_GET['fecha_desde'] ?? '');
        $fecha_hasta = trim($_GET['fecha_hasta'] ?? '');
        $kilometros_min = trim($_GET['kilometros_min'] ?? '');
        $kilometros_max = trim($_GET['kilometros_max'] ?? '');
        $coste_min = trim($_GET['coste_min'] ?? '');
        $coste_max = trim($_GET['coste_max'] ?? '');

        $orden_campo = trim($_GET['orden_campo'] ?? 'fecha');
        $orden_direccion = trim($_GET['orden_direccion'] ?? 'desc');

        if ($fecha_desde !== '') {
            $fecha_desde_objeto = DateTime::createFromFormat('Y-m-d', $fecha_desde);
            $fecha_desde_valida = $fecha_desde_objeto && $fecha_desde_objeto->format('Y-m-d') === $fecha_desde;

            if (!$fecha_desde_valida) {
                throw new InvalidArgumentException(t('garaje.filtros.error.fecha_desde_no_valida'));
            }
        }

        if ($fecha_hasta !== '') {
            $fecha_hasta_objeto = DateTime::createFromFormat('Y-m-d', $fecha_hasta);
            $fecha_hasta_valida = $fecha_hasta_objeto && $fecha_hasta_objeto->format('Y-m-d') === $fecha_hasta;

            if (!$fecha_hasta_valida) {
                throw new InvalidArgumentException(t('garaje.filtros.error.fecha_hasta_no_valida'));
            }
        }

        if ($fecha_desde !== '' && $fecha_hasta !== '' && $fecha_desde > $fecha_hasta) {
            throw new InvalidArgumentException(t('garaje.filtros.error.fecha_desde_mayor'));
        }

        if ($kilometros_min !== '' && !ctype_digit($kilometros_min)) {
            throw new InvalidArgumentException(t('garaje.filtros.error.km_minimos'));
        }

        if ($kilometros_max !== '' && !ctype_digit($kilometros_max)) {
            throw new InvalidArgumentException(t('garaje.filtros.error.km_maximos'));
        }

        if ($kilometros_min !== '' && $kilometros_max !== '' && (int) $kilometros_min > (int) $kilometros_max) {
            throw new InvalidArgumentException(t('garaje.filtros.error.km_minimos_mayores'));
        }

        if ($coste_min !== '') {
            $coste_min_normalizado = str_replace(',', '.', $coste_min);

            if (!is_numeric($coste_min_normalizado) || (float) $coste_min_normalizado < 0) {
                throw new InvalidArgumentException(t('garaje.filtros.error.coste_minimo'));
            }

            $coste_min = $coste_min_normalizado;
        }

        if ($coste_max !== '') {
            $coste_max_normalizado = str_replace(',', '.', $coste_max);

            if (!is_numeric($coste_max_normalizado) || (float) $coste_max_normalizado < 0) {
                throw new InvalidArgumentException(t('garaje.filtros.error.coste_maximo'));
            }

            $coste_max = $coste_max_normalizado;
        }

        if ($coste_min !== '' && $coste_max !== '' && (float) $coste_min > (float) $coste_max) {
            throw new InvalidArgumentException(t('garaje.filtros.error.coste_minimo_mayor'));
        }

        $campos_orden_validos = ['fecha', 'kilometros', 'coste'];
        $direcciones_validas = ['asc', 'desc'];

        if (!in_array($orden_campo, $campos_orden_validos, true)) {
            throw new InvalidArgumentException(t('garaje.filtros.error.orden_campo'));
        }

        if (!in_array($orden_direccion, $direcciones_validas, true)) {
            throw new InvalidArgumentException(t('garaje.filtros.error.orden_direccion'));
        }

        return [
            'tipo' => $tipo,
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta,
            'kilometros_min' => $kilometros_min,
            'kilometros_max' => $kilometros_max,
            'coste_min' => $coste_min,
            'coste_max' => $coste_max,
            'orden_campo' => $orden_campo,
            'orden_direccion' => $orden_direccion,
        ];
    }


//obtiene y valida la página actual del historial
    private function obtener_pagina_historial(): int
    {
        $pagina = (int) ($_GET['pagina'] ?? 1);

        if ($pagina < 1) {
            $pagina = 1;
        }

        return $pagina;
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
            flash_set('error', t('garaje.mantenimiento.error.vehiculo_no_valido'));
            $this->redirigir('/garaje');
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', t('garaje.mantenimiento.error.vehiculo_no_encontrado'));
            $this->redirigir('/garaje');
        }

        if ($fecha === '' || $tipo === '') {
            flash_set('error', t('garaje.mantenimiento.error.fecha_tipo_obligatorios'));
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        $fecha_objeto = DateTime::createFromFormat('Y-m-d', $fecha);
        $fecha_valida = $fecha_objeto && $fecha_objeto->format('Y-m-d') === $fecha;

        if (!$fecha_valida) {
            flash_set('error', t('garaje.mantenimiento.error.fecha_no_valida'));
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        if (mb_strlen($tipo) > 100) {
            flash_set('error', t('garaje.mantenimiento.error.tipo_maximo'));
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        $descripcion = ($descripcion === '') ? null : $descripcion;

        if ($kilometros_txt === '') {
            $kilometros = null;
        } elseif (ctype_digit($kilometros_txt)) {
            $kilometros = (int) $kilometros_txt;
        } else {
            flash_set('error', t('garaje.mantenimiento.error.kilometros_entero'));
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        if ($coste_txt === '') {
            $coste = null;
        } else {
            $coste_normalizado = str_replace(',', '.', $coste_txt);

            if (!is_numeric($coste_normalizado) || (float) $coste_normalizado < 0) {
                flash_set('error', t('garaje.mantenimiento.error.coste_valido'));
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
            flash_set('error', t('garaje.mantenimiento.error.guardar'));
            $this->redirigir('/garaje/mantenimientos/nuevo?vehiculo_id=' . $vehiculo_id);
        }

        unset($_SESSION['mantenimiento_form']);
        
        flash_set('ok', t('garaje.mantenimiento.ok.guardar'));
        $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
    }


//muestra el formulario para crear un mantenimiento de un vehículo del usuario
    public function mantenimiento_nuevo(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['vehiculo_id'] ?? 0);

        if ($vehiculo_id <= 0) {
            flash_set('error', t('garaje.mantenimiento.error.vehiculo_no_valido'));
            $this->redirigir('/garaje');
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            flash_set('error', t('garaje.mantenimiento.error.vehiculo_no_encontrado'));
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
            flash_set('error', t('garaje.mantenimiento.error.mantenimiento_no_valido'));
            $this->redirigir('/garaje');
        }

        $mantenimiento = RepositorioMantenimientos::buscar_por_id_y_usuario($mantenimiento_id, $usuario_id);

        if (!$mantenimiento) {
            flash_set('error', t('garaje.mantenimiento.error.mantenimiento_no_encontrado'));
            $this->redirigir('/garaje');
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario((int) $mantenimiento['vehiculo_id'], $usuario_id);

        if (!$vehiculo) {
            flash_set('error', t('garaje.mantenimiento.error.vehiculo_no_encontrado'));
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
            flash_set('error', t('garaje.mantenimiento.error.mantenimiento_no_valido'));
            $this->redirigir('/garaje');
        }

        $mantenimiento = RepositorioMantenimientos::buscar_por_id_y_usuario($mantenimiento_id, $usuario_id);

        if (!$mantenimiento) {
            flash_set('error', t('garaje.mantenimiento.error.mantenimiento_no_encontrado'));
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
            flash_set('error', t('garaje.mantenimiento.error.fecha_tipo_obligatorios'));
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        $fecha_objeto = DateTime::createFromFormat('Y-m-d', $fecha);
        $fecha_valida = $fecha_objeto && $fecha_objeto->format('Y-m-d') === $fecha;

        if (!$fecha_valida) {
            flash_set('error', t('garaje.mantenimiento.error.fecha_no_valida'));
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        if (mb_strlen($tipo) > 100) {
            flash_set('error', t('garaje.mantenimiento.error.tipo_maximo'));
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        $descripcion = ($descripcion === '') ? null : $descripcion;

        if ($kilometros_txt === '') {
            $kilometros = null;
        } elseif (ctype_digit($kilometros_txt)) {
            $kilometros = (int) $kilometros_txt;
        } else {
            flash_set('error', t('garaje.mantenimiento.error.kilometros_entero'));
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        if ($coste_txt === '') {
            $coste = null;
        } else {
            $coste_normalizado = str_replace(',', '.', $coste_txt);

            if (!is_numeric($coste_normalizado) || (float) $coste_normalizado < 0) {
                flash_set('error', t('garaje.mantenimiento.error.coste_valido'));
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
            flash_set('error', t('garaje.mantenimiento.error.actualizar'));
            $this->redirigir('/garaje/mantenimientos/editar?id=' . $mantenimiento_id);
        }

        unset($_SESSION['mantenimiento_editar_form']);

        flash_set('ok', t('garaje.mantenimiento.ok.actualizar'));
        $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
    }

    
//elimina un mantenimiento del usuario
    public function mantenimiento_eliminar_post(): void {
        csrf_verificar();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $mantenimiento_id = (int) ($_POST['mantenimiento_id'] ?? 0);

        if ($mantenimiento_id <= 0) {
            flash_set('error', t('garaje.mantenimiento.error.mantenimiento_no_valido'));
            $this->redirigir('/garaje');
        }

        $mantenimiento = RepositorioMantenimientos::buscar_por_id_y_usuario($mantenimiento_id, $usuario_id);

        if (!$mantenimiento) {
            flash_set('error', t('garaje.mantenimiento.error.mantenimiento_no_encontrado'));
            $this->redirigir('/garaje');
        }

        $vehiculo_id = (int) $mantenimiento['vehiculo_id'];

        try {
            RepositorioMantenimientos::eliminar($mantenimiento_id, $vehiculo_id);
        } catch (PDOException $e) {
            flash_set('error', t('garaje.mantenimiento.error.eliminar'));
            $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
        }

        flash_set('ok', t('garaje.mantenimiento.ok.eliminar'));
        $this->redirigir('/garaje/ver?id=' . $vehiculo_id);
    }


//devuelve por ajax solo la tabla filtrada de mantenimientos
    public function mantenimientos_filtrar(): void {
        $usuario_id = (int) $_SESSION['usuario']['id'];
        $vehiculo_id = (int) ($_GET['vehiculo_id'] ?? 0);

        if ($vehiculo_id <= 0) {
            http_response_code(400);
            echo '<p>' . htmlspecialchars(t('garaje.vehiculo.error.no_valido')) . '</p>';
            return;
        }

        $vehiculo = RepositorioVehiculos::buscar_por_id_y_usuario($vehiculo_id, $usuario_id);

        if (!$vehiculo) {
            http_response_code(403);
            echo '<p>' . htmlspecialchars(t('garaje.vehiculo.error.no_encontrado_permisos')) . '</p>';
            return;
        }

        try {
            $filtros = $this->obtener_filtros_mantenimiento();
        } catch (InvalidArgumentException $e) {
            http_response_code(400);
            echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
            return;
        }

        //obtener el total de mantenimientos filtrados para calcular la paginación
        $pagina_actual = $this->obtener_pagina_historial();
        $por_pagina = 5;
        $total_mantenimientos_filtrados = RepositorioMantenimientos::contar_filtrados_por_vehiculo($vehiculo_id, $filtros);
        $total_paginas = max(1, (int) ceil($total_mantenimientos_filtrados / $por_pagina));

        if ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
        }

        $offset = ($pagina_actual - 1) * $por_pagina;

        $mantenimientos = RepositorioMantenimientos::filtrar_por_vehiculo($vehiculo_id, $filtros, $por_pagina, $offset);
        $resumen_mantenimientos = RepositorioMantenimientos::obtener_resumen_filtrado_por_vehiculo($vehiculo_id, $filtros);

        $ruta_resumen = __DIR__ . '/../vistas/garaje/mantenimientos/resumen.php';
        $ruta_tabla = __DIR__ . '/../vistas/garaje/mantenimientos/tabla.php';
        $ruta_paginacion = __DIR__ . '/../vistas/garaje/mantenimientos/paginacion.php';

        $datos_parcial_mantenimientos = [
            'mantenimientos' => $mantenimientos,
            'resumen_mantenimientos' => $resumen_mantenimientos,
            'pagina_actual' => $pagina_actual,
            'por_pagina' => $por_pagina,
            'total_paginas' => $total_paginas,
            'total_mantenimientos_filtrados' => $total_mantenimientos_filtrados,
        ];

        extract($datos_parcial_mantenimientos);

        require $ruta_resumen;
        require $ruta_paginacion;
        require $ruta_tabla;
    }

//convierte el input multiple de imagenes en un array normal de archivos
    private function normalizar_archivos_multiples_vehiculo(?array $campo_archivos): array {
        if (
            empty($campo_archivos)
            || !isset($campo_archivos['name'])
            || !is_array($campo_archivos['name'])
        ) {
            return [];
        }

        $archivos = [];
    
        foreach ($campo_archivos['name'] as $indice => $nombre) {
            $error = $campo_archivos['error'][$indice] ?? UPLOAD_ERR_NO_FILE;

            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $archivos[] = [
                'name' => $nombre,
                'type' => $campo_archivos['type'][$indice] ?? '',
                'tmp_name' => $campo_archivos['tmp_name'][$indice] ?? '',
                'error' => $error,
                'size' => $campo_archivos['size'][$indice] ?? 0,
            ];
        }

        return $archivos;
    }

//guarda varias imágenes y limpia las ya subidas si una falla
    private function guardar_imagenes_vehiculo(array $archivos): array {
        if (empty($archivos)) {
            return [];
        }

        $limite_imagenes = 8;

        if (count($archivos) > $limite_imagenes) {
            throw new RuntimeException(t('garaje.vehiculo.imagen.error.limite'));
        }

        $imagenes_guardadas = [];

        try {
            foreach ($archivos as $archivo) {
                $imagenes_guardadas[] = $this->guardar_imagen_vehiculo($archivo);
            }
        } catch (RuntimeException $e) {
            foreach ($imagenes_guardadas as $imagen_guardada) {
                $this->eliminar_archivo_imagen_vehiculo($imagen_guardada);
            }

            throw $e;
        }

        return $imagenes_guardadas;
    }

//funciones auxiliares para guardar y eliminar imagenes de vehiculos
    private function guardar_imagen_vehiculo(array $archivo): string {
        if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException(t('garaje.vehiculo.imagen.error.subir'));
        }

        if (!isset($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            throw new RuntimeException(t('garaje.vehiculo.imagen.error.archivo_no_valido'));
        }

        $tamanyo_maximo = 3 * 1024 * 1024;

        if (($archivo['size'] ?? 0) > $tamanyo_maximo) {
            throw new RuntimeException(t('garaje.vehiculo.imagen.error.tamanyo'));
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($archivo['tmp_name']);

        $extensiones_permitidas = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($extensiones_permitidas[$mime_type])) {
            throw new RuntimeException(t('garaje.vehiculo.imagen.error.formato'));
        }

        $directorio = dirname(__DIR__, 2) . '/public/uploads/vehiculos';

        if (!is_dir($directorio) && !mkdir($directorio, 0775, true)) {
            throw new RuntimeException(t('garaje.vehiculo.imagen.error.crear_directorio'));
        }

        $nombre_archivo = 'vehiculo_' . bin2hex(random_bytes(16)) . '.' . $extensiones_permitidas[$mime_type];
        $ruta_destino = $directorio . '/' . $nombre_archivo;

        if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            throw new RuntimeException(t('garaje.vehiculo.imagen.error.guardar'));
        }

        return $nombre_archivo;
    }

//elimina un archivo de imagen de vehículo del servidor
    private function eliminar_archivo_imagen_vehiculo(?string $nombre_archivo): void {
        if (empty($nombre_archivo)) {
            return;
        }

        $ruta = dirname(__DIR__, 2) . '/public/uploads/vehiculos/' . $nombre_archivo;

        if (is_file($ruta)) {
            @unlink($ruta);
        }
    }
}

?>