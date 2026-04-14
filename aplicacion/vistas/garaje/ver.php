<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>detalle del vehiculo</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <p><strong>marca:</strong> <?= htmlspecialchars($vehiculo['marca']) ?></p>

    <p><strong>modelo:</strong> <?= htmlspecialchars($vehiculo['modelo']) ?></p>

    <p>
        <strong>año:</strong>
        <?php if (!empty($vehiculo['any'])): ?>
            <?= (int) $vehiculo['any'] ?>
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>vin:</strong>
        <?php if (!empty($vehiculo['vin'])): ?>
            <?= htmlspecialchars($vehiculo['vin']) ?>
        <?php else: ?>  
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>carroceria:</strong>
        <?php if (!empty($vehiculo['carroceria'])): ?>
            <?= htmlspecialchars($vehiculo['carroceria']) ?>
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>tipo de combustible:</strong>
        <?php if (!empty($vehiculo['tipo_combustible'])): ?>
            <?= htmlspecialchars($vehiculo['tipo_combustible']) ?>
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>tipo de cambio:</strong>
        <?php if (!empty($vehiculo['tipo_cambio'])): ?>
            <?= htmlspecialchars($vehiculo['tipo_cambio']) ?>
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>potencia:</strong>
        <?php if (!is_null($vehiculo['potencia_cv'])): ?>
            <?= (int) $vehiculo['potencia_cv'] ?> cv
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>

    <p>
        <strong>cilindrada:</strong>
        <?php if (!is_null($vehiculo['cilindrada_cm3'])): ?>
            <?= (int) $vehiculo['cilindrada_cm3'] ?> cc
        <?php else: ?>
            no indicado
        <?php endif; ?>
    </p>
    
    <p><strong>fecha de alta:</strong> <?= htmlspecialchars($vehiculo['fecha_creacion']) ?></p>

    <p>
        <button type="button" onclick="location.href='<?= url('/garaje') ?>'">volver al garaje</button>
        <button type="button" onclick="location.href='<?= url('/garaje/editar?id=' . (int) $vehiculo['id']) ?>'">editar</button>
        <button type="button" onclick="location.href='<?= url('/garaje/eliminar?id=' . (int) $vehiculo['id']) ?>'">eliminar</button>
    </p>

    <hr>
    <h1>historial de mantenimiento</h1>

    <p>
        <a href="<?= url('/garaje/mantenimientos/nuevo?vehiculo_id=' . (int) $vehiculo['id']) ?>">
            añadir mantenimiento
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

        <div class="fila-filtros">
            <div class="campo-filtro">
                <label for="tipo">tipo</label>
                <select id="tipo" name="tipo">
                    <option value="">todos</option>

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
                <label for="fecha_desde">fecha desde</label>
                <input
                    type="date"
                    id="fecha_desde"
                    name="fecha_desde"
                    value="<?= htmlspecialchars($filtros['fecha_desde'] ?? '') ?>"
                >
            </div>

            <div class="campo-filtro">
                <label for="fecha_hasta">fecha hasta</label>
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
                <label for="kilometros_min">km mínimos</label>
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
                <label for="kilometros_max">km máximos</label>
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
                <label for="coste_min">coste mínimo</label>
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
                <label for="coste_max">coste máximo</label>
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
                <label for="orden_campo">ordenar por</label>
                <select id="orden_campo" name="orden_campo">
                    <option value="fecha" <?= (($filtros['orden_campo'] ?? 'fecha') === 'fecha') ? 'selected' : '' ?>>
                        fecha
                    </option>
                    <option value="kilometros" <?= (($filtros['orden_campo'] ?? '') === 'kilometros') ? 'selected' : '' ?>>
                        kilómetros
                    </option>
                    <option value="coste" <?= (($filtros['orden_campo'] ?? '') === 'coste') ? 'selected' : '' ?>>
                        coste
                    </option>
                </select>
            </div>

            <div class="campo-filtro">
                <label for="orden_direccion">dirección</label>
                <select id="orden_direccion" name="orden_direccion">
                    <option value="desc" <?= (($filtros['orden_direccion'] ?? 'desc') === 'desc') ? 'selected' : '' ?>>
                        descendente
                    </option>
                    <option value="asc" <?= (($filtros['orden_direccion'] ?? '') === 'asc') ? 'selected' : '' ?>>
                        ascendente
                    </option>
                </select>
            </div>
        </div>

        <div class="acciones-filtros">
            <button type="submit">filtrar</button>
            <button type="button" id="btn-limpiar-filtros">limpiar</button>
        </div>
    </form>

    <div id="contenedor-tabla-mantenimientos">
        <?php require __DIR__ . '/mantenimientos/tabla.php'; ?>
    </div>

    <script src="<?= url('/public/js/garaje-ver.js') ?>"></script>
    
</body>
</html>