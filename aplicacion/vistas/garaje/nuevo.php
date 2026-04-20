<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>
    <h1>Añadir vehículo</h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= url('/garaje/nuevo') ?>" enctype="multipart/form-data">
        <?= csrf_campo() ?>

        <div>
            <label>Marca: *</label>
            <input type="text" name="marca" required>
        </div>

        <div>
            <label>Modelo: *</label>
            <input type="text" name="modelo" required>
        </div>

        <div>
            <label>Año:</label>
            <input type="number" name="any" min="1900" max="2026">
        </div>

        <div>
            <label>VIN (opcional):</label>
            <input type="text" name="vin" maxlength="25">
        </div>
        
        <div>
            <label for="carroceria">Carrocería:</label>
            <select name="carroceria" id="carroceria">
                <option value="">Selecciona una opción</option>
                <option value="coche pequeño">Coche pequeño</option>
                <option value="sedán">Sedán</option>
                <option value="familiar">Familiar</option>
                <option value="cabrio">Cabrio</option>
                <option value="coupé">Coupé</option>
                <option value="suv/4x4">SUV/4x4</option>
                <option value="monovolumen">Monovolumen</option>
                <option value="furgoneta">Furgoneta</option>
                <option value="otros">Otros</option>
            </select>
        </div>

        <div>
            <label for="tipo_combustible">Tipo de combustible:</label>
            <select name="tipo_combustible" id="tipo_combustible">
                <option value="">Selecciona una opción</option>
                <option value="gasolina">Gasolina</option>
                <option value="diesel">Diesel</option>
                <option value="electrico">Eléctrico</option>
                <option value="electro/gasolina">Electro/gasolina</option>
                <option value="electro/diesel">Electro/diesel</option>
                <option value="gas natural (CNG)">Gas natural (CNG)</option>
                <option value="etanol">Etanol</option>
                <option value="hidrogeno">Hidrógeno</option>
                <option value="gas licuado (GLP)">Gas licuado (GLP)</option>
                <option value="otros">Otros</option>
            </select>
        </div>

        <div>
            <label for="tipo_cambio">Tipo de cambio:</label>
            <select name="tipo_cambio" id="tipo_cambio">
                <option value="">Selecciona una opción</option>
                <option value="automatico">Automático</option>
                <option value="manual">Manual</option>
            </select>
        </div>

        <div>
            <label for="potencia_cv">Potencia (cv):</label>
            <input type="number" name="potencia_cv" id="potencia_cv" min="0">
        </div>

        <div>
            <label for="cilindrada_cm3">Cilindrada (cm3):</label>
            <input type="number" name="cilindrada_cm3" id="cilindrada_cm3" min="0">
        </div>
        <br>
        <div>
            <label for="imagen">Imagen del vehículo:</label>
            <input type="file" name="imagen" id="imagen" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"> <br>
            <small>Formatos permitidos: jpg, png, webp. (Máximo 3 mb)</small>
        </div>
        <br>
        <button type="button" onclick="location.href='<?= url('/garaje') ?>'">Cancelar</button>
        <button type="submit">Guardar vehículo</button>
    </form>

</body>
</html>