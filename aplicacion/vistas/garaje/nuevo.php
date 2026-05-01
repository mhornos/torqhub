<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('garaje.form.nuevo.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">

</head>

<body>
    <h1><?= htmlspecialchars(t('garaje.form.nuevo.titulo')) ?></h1>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <form
        method="post"
        action="<?= url('/garaje/nuevo') ?>"
        enctype="multipart/form-data"
        class="formulario-garaje-validado"
        data-url-consultar-vin="<?= url('/garaje/vin/consultar') ?>"
        novalidate>
        <?= csrf_campo() ?>

        <div>
            <label><?= htmlspecialchars(t('garaje.form.marca')) ?>: *</label>
            <input type="text" name="marca" required>
        </div>

        <div>
            <label><?= htmlspecialchars(t('garaje.form.modelo')) ?>: *</label>
            <input type="text" name="modelo" required>
        </div>

        <div>
            <label><?= htmlspecialchars(t('garaje.form.any')) ?>:</label>
            <input type="number" name="any" min="1900" max="2026" required>
        </div>


        <label for="vin"><?= htmlspecialchars(t('garaje.form.vin_opcional')) ?>:</label>
        <input type="text" name="vin" id="vin" maxlength="17" autocomplete="off"> <br>

        <button type="button" class="boton-consultar-vin">
            <?= htmlspecialchars(t('garaje.form.consultar_vin')) ?>
        </button>
        <br>
        <small><?= htmlspecialchars(t('garaje.form.vin_ayuda_nuevo')) ?>.</small>
        <p class="mensaje-vin" aria-live="polite"></p>


        <div>
            <label for="carroceria"><?= htmlspecialchars(t('garaje.form.carroceria')) ?>:</label>
            <select name="carroceria" id="carroceria">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
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
            <label for="tipo_combustible"><?= htmlspecialchars(t('garaje.form.tipo_combustible')) ?>:</label>
            <select name="tipo_combustible" id="tipo_combustible">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
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
            <label for="tipo_cambio"><?= htmlspecialchars(t('garaje.form.tipo_cambio')) ?>:</label>
            <select name="tipo_cambio" id="tipo_cambio">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
                <option value="automatico">Automático</option>
                <option value="manual">Manual</option>
            </select>
        </div>

        <div>
            <label for="potencia_cv"><?= htmlspecialchars(t('garaje.form.potencia_cv')) ?>:</label>
            <input type="number" name="potencia_cv" id="potencia_cv" min="0">
        </div>

        <div>
            <label for="cilindrada_cm3"><?= htmlspecialchars(t('garaje.form.cilindrada_cm3')) ?>:</label>
            <input type="number" name="cilindrada_cm3" id="cilindrada_cm3" min="0">
        </div>
        <br>
        <div>
            <label for="imagen"><?= htmlspecialchars(t('garaje.form.imagen')) ?>:</label>
            <input type="file" name="imagen" id="imagen" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"> <br>
            <small><?= htmlspecialchars(t('garaje.form.imagen_ayuda_nuevo')) ?>.</small>
        </div>
        <br>
        <button type="button" onclick="location.href='<?= url('/garaje') ?>'">
            <?= htmlspecialchars(t('garaje.form.cancelar')) ?>
        </button>

        <button type="submit">
            <?= htmlspecialchars(t('garaje.form.guardar_vehiculo')) ?>
        </button>
    </form>

    <script src="<?= url('/public/js/garaje/formulario.js') ?>"></script>
    <script src="<?= url('/public/js/garaje/vin.js') ?>"></script>
</body>

</html>