<?php
if (!isset($vehiculo) || !is_array($vehiculo)) {
    flash_set('error', t('garaje.detalle.error.cargar'));
    header('Location: ' . url('/garaje'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('garaje.detalle.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <h1><?= htmlspecialchars(t('garaje.detalle.titulo')) ?></h1>

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
                    alt="<?= htmlspecialchars(t('garaje.detalle.alt_imagen') . ' ' . $vehiculo['marca'] . ' ' . $vehiculo['modelo']) ?>">
            <?php else: ?>
                <div class="detalle-vehiculo-placeholder-imagen">
                    <span class="detalle-vehiculo-placeholder-texto">
                        <?= htmlspecialchars(t('garaje.detalle.imagen_no_disponible')) ?>
                    </span>

                    <small class="detalle-vehiculo-placeholder-ayuda">
                        <?= htmlspecialchars(t('garaje.detalle.imagen_ayuda')) ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>

        <div class="detalle-vehiculo-columna-principal">
            <div class="detalle-vehiculo-bloque detalle-vehiculo-bloque-principal">
                <h2>
                    <?= htmlspecialchars($vehiculo['marca']) ?>
                    <?= htmlspecialchars($vehiculo['modelo']) ?>
                </h2>

                <p class="detalle-vehiculo-subtitulo">
                    <?= htmlspecialchars(t('garaje.detalle.subtitulo')) ?>
                </p>

                <div class="detalle-vehiculo-acciones">
                    <a href="<?= url('/garaje') ?>">
                        <?= htmlspecialchars(t('garaje.detalle.volver_garaje')) ?>
                    </a>

                    <a href="<?= url('/garaje/editar?id=' . (int) $vehiculo['id']) ?>">
                        <?= htmlspecialchars(t('garaje.detalle.editar')) ?>
                    </a>

                    <a href="<?= url('/garaje/eliminar?id=' . (int) $vehiculo['id']) ?>">
                        <?= htmlspecialchars(t('garaje.detalle.eliminar')) ?>
                    </a>
                </div>
            </div>

            <div class="detalle-vehiculo-bloque detalle-vehiculo-bloque-estadisticas">
                <h3><?= htmlspecialchars(t('garaje.detalle.estadisticas')) ?></h3>

                <div class="estadisticas-vehiculo-grid">
                    <div class="estadistica-vehiculo-card">
                        <span class="estadistica-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.total_gastado')) ?></span>
                        <strong><?= number_format((float) $estadisticas_vehiculo['total_gastado'], 2, ',', '.') ?> €</strong>
                    </div>

                    <div class="estadistica-vehiculo-card">
                        <span class="estadistica-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.mantenimientos')) ?></span>
                        <strong><?= (int) $estadisticas_vehiculo['total_mantenimientos'] ?></strong>
                    </div>

                    <div class="estadistica-vehiculo-card">
                        <span class="estadistica-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.ultimo_mantenimiento')) ?></span>
                        <strong>
                            <?php if (!empty($estadisticas_vehiculo['ultima_fecha'])): ?>
                                <?= htmlspecialchars($estadisticas_vehiculo['ultima_fecha']) ?>
                            <?php else: ?>
                                <?= htmlspecialchars(t('garaje.detalle.sin_registros')) ?>
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
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.marca')) ?></span>
            <strong><?= htmlspecialchars($vehiculo['marca']) ?></strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.modelo')) ?></span>
            <strong><?= htmlspecialchars($vehiculo['modelo']) ?></strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.any')) ?></span>
            <strong>
                <?php if (!empty($vehiculo['any'])): ?>
                    <?= (int) $vehiculo['any'] ?>
                <?php else: ?>
                    (<?= htmlspecialchars(t('garaje.detalle.no_indicado')) ?>)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.vin')) ?></span>
            <strong>
                <?php if (!empty($vehiculo['vin'])): ?>
                    <?= htmlspecialchars($vehiculo['vin']) ?>
                <?php else: ?>
                    (<?= htmlspecialchars(t('garaje.detalle.no_indicado')) ?>)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.carroceria')) ?></span>
            <strong>
                <?php if (!empty($vehiculo['carroceria'])): ?>
                    <?= htmlspecialchars($vehiculo['carroceria']) ?>
                <?php else: ?>
                    (<?= htmlspecialchars(t('garaje.detalle.no_indicado')) ?>)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.tipo_combustible')) ?></span>
            <strong>
                <?php if (!empty($vehiculo['tipo_combustible'])): ?>
                    <?= htmlspecialchars($vehiculo['tipo_combustible']) ?>
                <?php else: ?>
                    (<?= htmlspecialchars(t('garaje.detalle.no_indicado')) ?>)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.tipo_cambio')) ?></span>
            <strong>
                <?php if (!empty($vehiculo['tipo_cambio'])): ?>
                    <?= htmlspecialchars($vehiculo['tipo_cambio']) ?>
                <?php else: ?>
                    (<?= htmlspecialchars(t('garaje.detalle.no_indicado')) ?>)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.potencia')) ?></span>
            <strong>
                <?php if (!is_null($vehiculo['potencia_cv'])): ?>
                    <?= (int) $vehiculo['potencia_cv'] ?> cv
                <?php else: ?>
                    (<?= htmlspecialchars(t('garaje.detalle.no_indicado')) ?>)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.cilindrada')) ?></span>
            <strong>
                <?php if (!is_null($vehiculo['cilindrada_cm3'])): ?>
                    <?= (int) $vehiculo['cilindrada_cm3'] ?> cc
                <?php else: ?>
                    (<?= htmlspecialchars(t('garaje.detalle.no_indicado')) ?>)
                <?php endif; ?>
            </strong>
        </div>

        <div class="detalle-vehiculo-dato">
            <span class="detalle-vehiculo-label"><?= htmlspecialchars(t('garaje.detalle.fecha_alta')) ?></span>
            <strong><?= formatear_fecha($vehiculo['fecha_creacion']) ?></strong>
        </div>
    </section>

    <hr>

    <h1><?= htmlspecialchars(t('garaje.historial.titulo')) ?></h1>

    <p>
        <a href="<?= url('/garaje/mantenimientos/nuevo?vehiculo_id=' . (int) $vehiculo['id']) ?>">
            <?= htmlspecialchars(t('garaje.historial.anadir')) ?>
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
            <?= htmlspecialchars(t('garaje.historial.exportar_csv')) ?>
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
        data-url-ajax="<?= url('/garaje/mantenimientos/filtrar') ?>"
        data-error-ajax="<?= htmlspecialchars(t('garaje.historial.error_ajax')) ?>">

        <input type="hidden" name="id" value="<?= (int) $vehiculo['id'] ?>">
        <input type="hidden" name="vehiculo_id" value="<?= (int) $vehiculo['id'] ?>">
        <input type="hidden" name="pagina" id="pagina-historial" value="<?= (int) ($pagina_actual ?? 1) ?>">

        <div class="fila-filtros">
            <div class="campo-filtro">
                <label for="tipo"><?= htmlspecialchars(t('garaje.historial.tipo')) ?></label>
                <select id="tipo" name="tipo">
                    <option value=""><?= htmlspecialchars(t('garaje.historial.todos')) ?></option>

                    <?php foreach ($tipos_mantenimiento as $tipo_mantenimiento): ?>
                        <option
                            value="<?= htmlspecialchars($tipo_mantenimiento) ?>"
                            <?= (($filtros['tipo'] ?? '') === $tipo_mantenimiento) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tipo_mantenimiento) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo-filtro">
                <label for="fecha_desde"><?= htmlspecialchars(t('garaje.historial.fecha_desde')) ?></label>
                <input
                    type="date"
                    id="fecha_desde"
                    name="fecha_desde"
                    value="<?= htmlspecialchars($filtros['fecha_desde'] ?? '') ?>">
            </div>

            <div class="campo-filtro">
                <label for="fecha_hasta"><?= htmlspecialchars(t('garaje.historial.fecha_hasta')) ?></label>
                <input
                    type="date"
                    id="fecha_hasta"
                    name="fecha_hasta"
                    value="<?= htmlspecialchars($filtros['fecha_hasta'] ?? '') ?>">
            </div>
            <!-- </div> -->

            <!-- <div class="fila-filtros"> -->
            <div class="campo-filtro">
                <label for="kilometros_min"><?= htmlspecialchars(t('garaje.historial.km_minimos')) ?></label>
                <input
                    type="number"
                    id="kilometros_min"
                    name="kilometros_min"
                    min="0"
                    step="1"
                    value="<?= htmlspecialchars($filtros['kilometros_min'] ?? '') ?>">
            </div>

            <div class="campo-filtro">
                <label for="kilometros_max"><?= htmlspecialchars(t('garaje.historial.km_maximos')) ?></label>
                <input
                    type="number"
                    id="kilometros_max"
                    name="kilometros_max"
                    min="0"
                    step="1"
                    value="<?= htmlspecialchars($filtros['kilometros_max'] ?? '') ?>">
            </div>

            <div class="campo-filtro">
                <label for="coste_min"><?= htmlspecialchars(t('garaje.historial.coste_minimo')) ?></label>
                <input
                    type="number"
                    id="coste_min"
                    name="coste_min"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($filtros['coste_min'] ?? '') ?>">
            </div>

            <div class="campo-filtro">
                <label for="coste_max"><?= htmlspecialchars(t('garaje.historial.coste_maximo')) ?></label>
                <input
                    type="number"
                    id="coste_max"
                    name="coste_max"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($filtros['coste_max'] ?? '') ?>">
            </div>
        </div>

        <div class="fila-filtros">
            <div class="campo-filtro">
                <label for="orden_campo"><?= htmlspecialchars(t('garaje.historial.ordenar_por')) ?></label>
                <select id="orden_campo" name="orden_campo">
                    <option value="fecha" <?= (($filtros['orden_campo'] ?? 'fecha') === 'fecha') ? 'selected' : '' ?>>
                        <?= htmlspecialchars(t('garaje.historial.fecha')) ?>
                    </option>
                    <option value="kilometros" <?= (($filtros['orden_campo'] ?? '') === 'kilometros') ? 'selected' : '' ?>>
                        <?= htmlspecialchars(t('garaje.historial.kilometros')) ?>
                    </option>
                    <option value="coste" <?= (($filtros['orden_campo'] ?? '') === 'coste') ? 'selected' : '' ?>>
                        <?= htmlspecialchars(t('garaje.historial.coste')) ?>
                    </option>
                </select>
            </div>

            <div class="campo-filtro">
                <label for="orden_direccion"><?= htmlspecialchars(t('garaje.historial.direccion')) ?></label>
                <select id="orden_direccion" name="orden_direccion">
                    <option value="desc" <?= (($filtros['orden_direccion'] ?? 'desc') === 'desc') ? 'selected' : '' ?>>
                        <?= htmlspecialchars(t('garaje.historial.descendente')) ?>
                    </option>
                    <option value="asc" <?= (($filtros['orden_direccion'] ?? '') === 'asc') ? 'selected' : '' ?>>
                        <?= htmlspecialchars(t('garaje.historial.ascendente')) ?>
                    </option>
                </select>
            </div>
        </div>

        <div class="acciones-filtros">
            <button type="submit"><?= htmlspecialchars(t('garaje.historial.filtrar')) ?></button>
            <button type="button" id="btn-limpiar-filtros"><?= htmlspecialchars(t('garaje.historial.limpiar')) ?></button>
        </div>
    </form>

    <div id="contenedor-historial-mantenimientos">
        <?php require __DIR__ . '/mantenimientos/resumen.php'; ?>
        <?php require __DIR__ . '/mantenimientos/paginacion.php'; ?>
        <?php require __DIR__ . '/mantenimientos/tabla.php'; ?>
    </div>

    <script src="<?= url('/public/js/garaje/ver.js') ?>"></script>

</body>

</html>