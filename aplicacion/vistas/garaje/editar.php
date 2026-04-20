<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Editar vehículo</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= url('/garaje/editar') ?>" enctype="multipart/form-data" class="formulario-garaje-validado" novalidate>
        <?= csrf_campo() ?>

        <input type="hidden" name="id" value="<?= (int) $vehiculo['id'] ?>">

        <div>
            <label>Marca: *</label>
            <input type="text" name="marca" value="<?= htmlspecialchars($vehiculo['marca']) ?>" required>
        </div>

        <div>
            <label>Modelo: *</label>
            <input type="text" name="modelo" value="<?= htmlspecialchars($vehiculo['modelo']) ?>" required>
        </div>

        <div>
            <label>Año:</label>
            <input type="number" name="any" min="1900" max="2100" value="<?= htmlspecialchars((string) ($vehiculo['any'] ?? '')) ?>">
        </div>

        <div>
            <label>VIN:</label>
            <input type="text" name="vin" maxlength="25" value="<?= htmlspecialchars((string) ($vehiculo['vin'] ?? '')) ?>">
        </div>
        
        <div>
            <label for="carroceria">Carroceria:</label>
            <select name="carroceria" id="carroceria">
                <option value="">Selecciona una opción</option>
                <option value="coche pequeño" <?= ($vehiculo['carroceria'] ?? '') === 'coche pequeño' ? 'selected' : '' ?>>Coche pequeño</option>
                <option value="sedán" <?= ($vehiculo['carroceria'] ?? '') === 'sedán' ? 'selected' : '' ?>>Sedán</option>
                <option value="familiar" <?= ($vehiculo['carroceria'] ?? '') === 'familiar' ? 'selected' : '' ?>>Familiar</option>
                <option value="cabrio" <?= ($vehiculo['carroceria'] ?? '') === 'cabrio' ? 'selected' : '' ?>>Cabrio</option>
                <option value="coupé" <?= ($vehiculo['carroceria'] ?? '') === 'coupé' ? 'selected' : '' ?>>Coupé</option>
                <option value="suv/4x4" <?= ($vehiculo['carroceria'] ?? '') === 'suv/4x4' ? 'selected' : '' ?>>SUV/4x4</option>
                <option value="monovolumen" <?= ($vehiculo['carroceria'] ?? '') === 'monovolumen' ? 'selected' : '' ?>>Monovolumen</option>
                <option value="furgoneta" <?= ($vehiculo['carroceria'] ?? '') === 'furgoneta' ? 'selected' : '' ?>>Furgoneta</option>
                <option value="otros" <?= ($vehiculo['carroceria'] ?? '') === 'otros' ? 'selected' : '' ?>>Otros</option>
            </select>
        </div>

        <div>
            <label for="tipo_combustible">Tipo de combustible:</label>
            <select name="tipo_combustible" id="tipo_combustible">
                <option value="">Selecciona una opción</option>
                <option value="gasolina" <?= ($vehiculo['tipo_combustible'] ?? '') === 'gasolina' ? 'selected' : '' ?>>Gasolina</option>
                <option value="diesel" <?= ($vehiculo['tipo_combustible'] ?? '') === 'diesel' ? 'selected' : '' ?>>Diesel</option>
                <option value="electrico" <?= ($vehiculo['tipo_combustible'] ?? '') === 'electrico' ? 'selected' : '' ?>>Eléctrico</option>
                <option value="electro/gasolina" <?= ($vehiculo['tipo_combustible'] ?? '') === 'electro/gasolina' ? 'selected' : '' ?>>Eléctrico/Gasolina</option>
                <option value="electro/diesel" <?= ($vehiculo['tipo_combustible'] ?? '') === 'electro/diesel' ? 'selected' : '' ?>>Eléctrico/Diesel</option>
                <option value="gas natural (CNG)" <?= ($vehiculo['tipo_combustible'] ?? '') === 'gas natural (CNG)' ? 'selected' : '' ?>>Gas Natural (CNG)</option>
                <option value="etanol" <?= ($vehiculo['tipo_combustible'] ?? '') === 'etanol' ? 'selected' : '' ?>>Etanol</option>
                <option value="hidrogeno" <?= ($vehiculo['tipo_combustible'] ?? '') === 'hidrogeno' ? 'selected' : '' ?>>Hidrógeno</option>
                <option value="gas licuado (GLP)" <?= ($vehiculo['tipo_combustible'] ?? '') === 'gas licuado (GLP)' ? 'selected' : '' ?>>Gas Licuado (GLP)</option>
                <option value="otros" <?= ($vehiculo['tipo_combustible'] ?? '') === 'otros' ? 'selected' : '' ?>>Otros</option>
            </select>
        </div>

        <div>
            <label for="tipo_cambio">Tipo de cambio:</label>
            <select name="tipo_cambio" id="tipo_cambio">
                <option value="">Selecciona una opción</option>
                <option value="automatico" <?= ($vehiculo['tipo_cambio'] ?? '') === 'automatico' ? 'selected' : '' ?>>Automático</option>
                <option value="manual" <?= ($vehiculo['tipo_cambio'] ?? '') === 'manual' ? 'selected' : '' ?>>Manual</option>
            </select>
        </div>

        <div>
            <label for="potencia_cv">Potencia (cv):</label>
            <input
                type="number"
                name="potencia_cv"
                id="potencia_cv"
                min="0"
                value="<?= isset($vehiculo['potencia_cv']) && $vehiculo['potencia_cv'] !== null ? (int) $vehiculo['potencia_cv'] : '' ?>"
            >
        </div>

        <div>
            <label for="cilindrada_cm3">Cilindrada (cm³):</label>
            <input
                type="number"
                name="cilindrada_cm3"
                id="cilindrada_cm3"
                min="0"
                value="<?= isset($vehiculo['cilindrada_cm3']) && $vehiculo['cilindrada_cm3'] !== null ? (int) $vehiculo['cilindrada_cm3'] : '' ?>"
            >
        </div> <br>

        <div>
            <label for="imagen">Cambiar imagen del vehiculo:</label>
            <input type="file" name="imagen" id="imagen" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"> <br>
            <small>Si subes una nueva imagen, reemplazará la actual. (Máximo 3 mb)</small>
        </div>

        <?php if (!empty($vehiculo['imagen'])): ?>
            <p>
                <strong>Imagen actual:</strong><br>
                <img
                    src="<?= url('/public/uploads/vehiculos/' . rawurlencode($vehiculo['imagen'])) ?>"
                    alt="imagen actual del vehiculo"
                    style="max-width: 280px; height: auto; margin-top: 8px;"
                >
            </p>
        <?php endif; ?>
        
        <br>
        <button type="button" onclick="history.back()">Cancelar</button>
        <button type="submit">Guardar cambios</button>
    </form>
    
    <script src="<?= url('/public/js/garaje-formulario.js') ?>"></script>
</body>
</html>