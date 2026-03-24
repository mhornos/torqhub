<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>añadir vehiculo</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= url('/garaje/nuevo') ?>">
        <?= csrf_campo() ?>

        <div>
            <label>marca: *</label>
            <input type="text" name="marca" required>
        </div>

        <div>
            <label>modelo: *</label>
            <input type="text" name="modelo" required>
        </div>

        <div>
            <label>año:</label>
            <input type="number" name="any" min="1900" max="2026">
        </div>

        <div>
            <label>vin (opcional):</label>
            <input type="text" name="vin" maxlength="25">
        </div>
        
        <div>
            <label for="carroceria">carroceria:</label>
            <select name="carroceria" id="carroceria">
                <option value="">selecciona una opcion</option>
                <option value="coche pequeño">coche pequeño</option>
                <option value="sedán">sedán</option>
                <option value="familiar">familiar</option>
                <option value="cabrio">cabrio</option>
                <option value="coupé">coupé</option>
                <option value="suv/4x4">suv/4x4</option>
                <option value="monovolumen">monovolumen</option>
                <option value="furgoneta">furgoneta</option>
                <option value="otros">otros</option>
            </select>
        </div>

        <div>
            <label for="tipo_combustible">tipo de combustible:</label>
            <select name="tipo_combustible" id="tipo_combustible">
                <option value="">selecciona una opcion</option>
                <option value="gasolina">gasolina</option>
                <option value="diesel">diesel</option>
                <option value="electrico">electrico</option>
                <option value="electro/gasolina">electro/gasolina</option>
                <option value="electro/diesel">electro/diesel</option>
                <option value="gas natural (CNG)">gas natural (CNG)</option>
                <option value="etanol">etanol</option>
                <option value="hidrogeno">hidrogeno</option>
                <option value="gas licuado (GLP)">gas licuado (GLP)</option>
                <option value="otros">otros</option>
            </select>
        </div>

        <div>
            <label for="tipo_cambio">tipo de cambio:</label>
            <select name="tipo_cambio" id="tipo_cambio">
                <option value="">selecciona una opcion</option>
                <option value="automatico">automatico</option>
                <option value="manual">manual</option>
            </select>
        </div>

        <div>
            <label for="potencia_cv">potencia (cv):</label>
            <input type="number" name="potencia_cv" id="potencia_cv" min="0">
        </div>

        <div>
            <label for="cilindrada_cm3">cilindrada (cm3):</label>
            <input type="number" name="cilindrada_cm3" id="cilindrada_cm3" min="0">
        </div><br>

        <button type="button" onclick="location.href='<?= url('/garaje') ?>'">cancelar</button>
        <button type="submit">guardar vehículo</button>
    </form>

</body>
</html>