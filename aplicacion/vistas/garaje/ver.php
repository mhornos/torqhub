<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Detalle del vehículo</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php
    $estadisticas_vehiculo = $estadisticas_vehiculo ?? [
        'total_mantenimientos' => 0,
        'total_gastado' => 0,
        'ultima_fecha' => null,
        'ultimo_tipo' => null,
    ];
    ?>

    <section class="detalle-vehiculo-cabecera">
        <div class="detalle-vehiculo-bloque detalle-vehiculo-bloque-imagen">
            <?php if (!empty($vehiculo['imagen'])): ?>
                <img
                    class="detalle-vehiculo-imagen-real"
                    src="<?= url('/public/uploads/vehiculos/' . rawurlencode($vehiculo['imagen'])) ?>"
                    alt="imagen del vehiculo <?= htmlspecialchars($vehiculo['marca'] . ' ' . $vehiculo['modelo']) ?>"
                >
            <?php else: ?>
                <div class="detalle-vehiculo-placeholder-imagen">
                    <span class="detalle-vehiculo-placeholder-texto">Imagen no disponible</span>
                    <small class="detalle-vehiculo-placeholder-ayuda">Todavía no se ha subido una foto para este vehiculo</small>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="detalle-vehiculo-columna-principal">
            <div class="detalle-vehiculo-bloque detalle-vehiculo-bloque-principal">
                <h2>
                    <?= htmlspecialchars($vehiculo['marca']) ?>
                    <?= htmlspecialchars($vehiculo['modelo']) ?>
                </h2>
        
                <p class="detalle-vehiculo-subtitulo">Vehiculo registrado en tu garaje</p>
        
                <div class="detalle-vehiculo-acciones">
                    <button type="button" onclick="location.href='<?= url('/garaje') ?>'">Volver al garaje</button>
                    <button type="button" onclick="location.href='<?= url('/garaje/editar?id=' . (int) $vehiculo['id']) ?>'">Editar</button>
                    <button type="button" onclick="location.href='<?= url('/garaje/eliminar?id=' . (int) $vehiculo['id']) ?>'">Eliminar</button>
                </div>
            </div>
        
            <div class="detalle-vehiculo-bloque detalle-vehiculo-bloque-estadisticas">
                <h3>Estadísticas rápidas</h3>
        
                <div class="estadisticas-vehiculo-grid">
                    <div class="estadistica-vehiculo-card">
                        <span class="estadistica-vehiculo-label">Total gastado</span>
                        <strong><?= number_format((float) $estadisticas_vehiculo['total_gastado'], 2, ',', '.') ?> €</strong>
                    </div>
        
                    <div class="estadistica-vehiculo-card">
                        <span class="estadistica-vehiculo-label">Mantenimientos</span>
                        <strong><?= (int) $estadisticas_vehiculo['total_mantenimientos'] ?></strong>
                    </div>
        
                    <div class="estadistica-vehiculo-card">
                        <span class="estadistica-vehiculo-label">Último mantenimiento</span>
                        <strong>
                            <?php if (!empty($estadisticas_vehiculo['ultima_fecha'])): ?>
                                <?= htmlspecialchars($estadisticas_vehiculo['ultima_fecha']) ?>
                            <?php else: ?>
                                Sin registros
                            <?php endif; ?>
                        </strong>
                            
                        <?php if (!empty($estadisticas_vehiculo['ultimo_tipo'])): ?>
                            <span class="estadistica-vehiculo-extra">
                                <?= htmlspecialchars($estadisticas_vehiculo['ultimo_tipo']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="detalle-vehiculo-ficha">
        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">Marca</span>
            <strong><?= htmlspecialchars($vehiculo['marca']) ?></strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">Modelo</span>
            <strong><?= htmlspecialchars($vehiculo['modelo']) ?></strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">Año</span>
            <strong>
                <?php if (!empty($vehiculo['any'])): ?>
                    <?= (int) $vehiculo['any'] ?>
                <?php else: ?>
                    (No indicado)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">VIN</span>
            <strong>
                <?php if (!empty($vehiculo['vin'])): ?>
                    <?= htmlspecialchars($vehiculo['vin']) ?>
                <?php else: ?>
                    (No indicado)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">Carrocería</span>
            <strong>
                <?php if (!empty($vehiculo['carroceria'])): ?>
                    <?= htmlspecialchars($vehiculo['carroceria']) ?>
                <?php else: ?>
                    (No indicado)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">Tipo de combustible</span>
            <strong>
                <?php if (!empty($vehiculo['tipo_combustible'])): ?>
                    <?= htmlspecialchars($vehiculo['tipo_combustible']) ?>
                <?php else: ?>
                    (No indicado)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">Tipo de cambio</span>
            <strong>
                <?php if (!empty($vehiculo['tipo_cambio'])): ?>
                    <?= htmlspecialchars($vehiculo['tipo_cambio']) ?>
                <?php else: ?>
                    (No indicado)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">Potencia</span>
            <strong>
                <?php if (!is_null($vehiculo['potencia_cv'])): ?>
                    <?= (int) $vehiculo['potencia_cv'] ?> cv
                <?php else: ?>
                    (No indicado)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">Cilindrada</span>
            <strong>
                <?php if (!is_null($vehiculo['cilindrada_cm3'])): ?>
                    <?= (int) $vehiculo['cilindrada_cm3'] ?> cc
                <?php else: ?>
                    (No indicado)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label">Fecha de alta</span>
            <strong><?= htmlspecialchars($vehiculo['fecha_creacion']) ?></strong>
        </div>
    </section>

    <hr>

    <h1>Historial de mantenimiento</h1>

    <p>
    <a href="<?= url('/garaje/mantenimientos/nuevo?vehiculo_id=' . (int) $vehiculo['id']) ?>">
        añadir mantenimiento
    </a>

    |

    <a href="<?= url('/garaje/mantenimientos/exportar-csv?vehiculo_id=' . (int) $vehiculo['id']
        . '&tipo=' . urlencode($filtros['tipo'] ?? '')
        . '&fecha_desde=' . urlencode($filtros['fecha_desde'] ?? '')
        . '&fecha_hasta=' . urlencode($filtros['fecha_hasta'] ?? '')
        . '&kilometros_min=' . urlencode($filtros['kilometros_min'] ?? '')
        . '&kilometros_max=' . urlencode($filtros['kilometros_max'] ?? '')
        . '&coste_min=' . urlencode($filtros['coste_min'] ?? '')
        . '&coste_max=' . urlencode($filtros['coste_max'] ?? '')
        . '&orden_campo=' . urlencode($filtros['orden_campo'] ?? 'fecha')
        . '&orden_direccion=' . urlencode($filtros['orden_direccion'] ?? 'desc')) ?>">
        exportar csv
    </a>
</p>

    <!-- formulario de filtros de mantenimientos -->
    <?php $filtros = $filtros ?? []; ?>
    <?php $tipos_mantenimiento = $tipos_mantenimiento ?? []; ?>

    <form
    id="form-filtros-mantenimientos"
    class="form-filtros-mantenimientos"
    action="<?= url('/garaje/ver') ?>"
    method="GET"
    data-url-ajax="<?= url('/garaje/mantenimientos/filtrar') ?>">

        <input type="hidden" name="id" value="<?= (int) $vehiculo['id'] ?>">
        <input type="hidden" name="vehiculo_id" value="<?= (int) $vehiculo['id'] ?>">
        <input type="hidden" name="pagina" id="pagina-historial" value="<?= (int) ($pagina_actual ?? 1) ?>">

        <div class="fila-filtros">
            <div class="campo-filtro">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo">
                    <option value="">Todos</option>

                    <?php foreach ($tipos_mantenimiento as $tipo_mantenimiento): ?>
                        <option
                            value="<?= htmlspecialchars($tipo_mantenimiento) ?>"
                            <?= (($filtros['tipo'] ?? '') === $tipo_mantenimiento) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($tipo_mantenimiento) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo-filtro">
                <label for="fecha_desde">Fecha desde</label>
                <input
                    type="date"
                    id="fecha_desde"
                    name="fecha_desde"
                    value="<?= htmlspecialchars($filtros['fecha_desde'] ?? '') ?>"
                >
            </div>

            <div class="campo-filtro">
                <label for="fecha_hasta">Fecha hasta</label>
                <input
                    type="date"
                    id="fecha_hasta"
                    name="fecha_hasta"
                    value="<?= htmlspecialchars($filtros['fecha_hasta'] ?? '') ?>"
                >
            </div>
        <!-- </div> -->

        <!-- <div class="fila-filtros"> -->
            <div class="campo-filtro">
                <label for="kilometros_min">Km mínimos</label>
                <input
                    type="number"
                    id="kilometros_min"
                    name="kilometros_min"
                    min="0"
                    step="1"
                    value="<?= htmlspecialchars($filtros['kilometros_min'] ?? '') ?>"
                >
            </div>

            <div class="campo-filtro">
                <label for="kilometros_max">Km máximos</label>
                <input
                    type="number"
                    id="kilometros_max"
                    name="kilometros_max"
                    min="0"
                    step="1"
                    value="<?= htmlspecialchars($filtros['kilometros_max'] ?? '') ?>"
                >
            </div>

            <div class="campo-filtro">
                <label for="coste_min">Coste mínimo</label>
                <input
                    type="number"
                    id="coste_min"
                    name="coste_min"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($filtros['coste_min'] ?? '') ?>"
                >
            </div>

            <div class="campo-filtro">
                <label for="coste_max">Coste máximo</label>
                <input
                    type="number"
                    id="coste_max"
                    name="coste_max"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($filtros['coste_max'] ?? '') ?>"
                >
            </div>
        </div>

                <div class="fila-filtros">
            <div class="campo-filtro">
                <label for="orden_campo">Ordenar por</label>
                <select id="orden_campo" name="orden_campo">
                    <option value="fecha" <?= (($filtros['orden_campo'] ?? 'fecha') === 'fecha') ? 'selected' : '' ?>>
                        Fecha
                    </option>
                    <option value="kilometros" <?= (($filtros['orden_campo'] ?? '') === 'kilometros') ? 'selected' : '' ?>>
                        Kilómetros
                    </option>
                    <option value="coste" <?= (($filtros['orden_campo'] ?? '') === 'coste') ? 'selected' : '' ?>>
                        Coste
                    </option>
                </select>
            </div>

            <div class="campo-filtro">
                <label for="orden_direccion">Dirección</label>
                <select id="orden_direccion" name="orden_direccion">
                    <option value="desc" <?= (($filtros['orden_direccion'] ?? 'desc') === 'desc') ? 'selected' : '' ?>>
                        Descendente
                    </option>
                    <option value="asc" <?= (($filtros['orden_direccion'] ?? '') === 'asc') ? 'selected' : '' ?>>
                        Ascendente
                    </option>
                </select>
            </div>
        </div>

        <div class="acciones-filtros">
            <button type="submit">Filtrar</button>
            <button type="button" id="btn-limpiar-filtros">Limpiar</button>
        </div>
    </form>

    <div id="contenedor-historial-mantenimientos">
        <?php require __DIR__ . '/mantenimientos/resumen.php'; ?>
        <?php require __DIR__ . '/mantenimientos/paginacion.php'; ?>
        <?php require __DIR__ . '/mantenimientos/tabla.php'; ?>
    </div>

    <script src="<?= url('/public/js/garaje-ver.js') ?>"></script>
    
</body>
</html>