<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>editar vehiculo</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= url('/garaje/editar') ?>">
        <?= csrf_campo() ?>

        <input type="hidden" name="id" value="<?= (int) $vehiculo['id'] ?>">

        <div>
            <label>marca: *</label>
            <input type="text" name="marca" value="<?= htmlspecialchars($vehiculo['marca']) ?>" required>
        </div>

        <div>
            <label>modelo: *</label>
            <input type="text" name="modelo" value="<?= htmlspecialchars($vehiculo['modelo']) ?>" required>
        </div>

        <div>
            <label>año:</label>
            <input type="number" name="any" min="1900" max="2100" value="<?= htmlspecialchars((string) ($vehiculo['any'] ?? '')) ?>">
        </div>

        <div>
            <label>vin:</label>
            <input type="text" name="vin" maxlength="25" value="<?= htmlspecialchars((string) ($vehiculo['vin'] ?? '')) ?>">
        </div>
        
        <div>
            <label for="carroceria">carroceria:</label>
            <select name="carroceria" id="carroceria">
                <option value="">selecciona una opcion</option>
                <option value="coche pequeño" <?= ($vehiculo['carroceria'] ?? '') === 'coche pequeño' ? 'selected' : '' ?>>coche pequeño</option>
                <option value="sedán" <?= ($vehiculo['carroceria'] ?? '') === 'sedán' ? 'selected' : '' ?>>sedán</option>
                <option value="familiar" <?= ($vehiculo['carroceria'] ?? '') === 'familiar' ? 'selected' : '' ?>>familiar</option>
                <option value="cabrio" <?= ($vehiculo['carroceria'] ?? '') === 'cabrio' ? 'selected' : '' ?>>cabrio</option>
                <option value="coupé" <?= ($vehiculo['carroceria'] ?? '') === 'coupé' ? 'selected' : '' ?>>coupé</option>
                <option value="suv/4x4" <?= ($vehiculo['carroceria'] ?? '') === 'suv/4x4' ? 'selected' : '' ?>>suv/4x4</option>
                <option value="monovolumen" <?= ($vehiculo['carroceria'] ?? '') === 'monovolumen' ? 'selected' : '' ?>>monovolumen</option>
                <option value="furgoneta" <?= ($vehiculo['carroceria'] ?? '') === 'furgoneta' ? 'selected' : '' ?>>furgoneta</option>
                <option value="otros" <?= ($vehiculo['carroceria'] ?? '') === 'otros' ? 'selected' : '' ?>>otros</option>
            </select>
        </div>

        <div>
            <label for="tipo_combustible">tipo de combustible:</label>
            <select name="tipo_combustible" id="tipo_combustible">
                <option value="">selecciona una opcion</option>
                <option value="gasolina" <?= ($vehiculo['tipo_combustible'] ?? '') === 'gasolina' ? 'selected' : '' ?>>gasolina</option>
                <option value="diesel" <?= ($vehiculo['tipo_combustible'] ?? '') === 'diesel' ? 'selected' : '' ?>>diesel</option>
                <option value="electrico" <?= ($vehiculo['tipo_combustible'] ?? '') === 'electrico' ? 'selected' : '' ?>>electrico</option>
                <option value="electro/gasolina" <?= ($vehiculo['tipo_combustible'] ?? '') === 'electro/gasolina' ? 'selected' : '' ?>>electro/gasolina</option>
                <option value="electro/diesel" <?= ($vehiculo['tipo_combustible'] ?? '') === 'electro/diesel' ? 'selected' : '' ?>>electro/diesel</option>
                <option value="gas natural (CNG)" <?= ($vehiculo['tipo_combustible'] ?? '') === 'gas natural (CNG)' ? 'selected' : '' ?>>gas natural (CNG)</option>
                <option value="etanol" <?= ($vehiculo['tipo_combustible'] ?? '') === 'etanol' ? 'selected' : '' ?>>etanol</option>
                <option value="hidrogeno" <?= ($vehiculo['tipo_combustible'] ?? '') === 'hidrogeno' ? 'selected' : '' ?>>hidrogeno</option>
                <option value="gas licuado (GLP)" <?= ($vehiculo['tipo_combustible'] ?? '') === 'gas licuado (GLP)' ? 'selected' : '' ?>>gas licuado (GLP)</option>
                <option value="otros" <?= ($vehiculo['tipo_combustible'] ?? '') === 'otros' ? 'selected' : '' ?>>otros</option>
            </select>
        </div>

        <div>
            <label for="tipo_cambio">tipo de cambio:</label>
            <select name="tipo_cambio" id="tipo_cambio">
                <option value="">selecciona una opcion</option>
                <option value="automatico" <?= ($vehiculo['tipo_cambio'] ?? '') === 'automatico' ? 'selected' : '' ?>>automatico</option>
                <option value="manual" <?= ($vehiculo['tipo_cambio'] ?? '') === 'manual' ? 'selected' : '' ?>>manual</option>
            </select>
        </div>

        <div>
            <label for="potencia_cv">potencia (cv):</label>
            <input
                type="number"
                name="potencia_cv"
                id="potencia_cv"
                min="0"
                value="<?= isset($vehiculo['potencia_cv']) && $vehiculo['potencia_cv'] !== null ? (int) $vehiculo['potencia_cv'] : '' ?>"
            >
        </div>

        <div>
            <label for="cilindrada_cm3">cilindrada (cm³):</label>
            <input
                type="number"
                name="cilindrada_cm3"
                id="cilindrada_cm3"
                min="0"
                value="<?= isset($vehiculo['cilindrada_cm3']) && $vehiculo['cilindrada_cm3'] !== null ? (int) $vehiculo['cilindrada_cm3'] : '' ?>"
            >
        </div> <br>

        <button type="button" onclick="history.back()">cancelar</button>
        <button type="submit">guardar cambios</button>
    </form>

</body>
</html>