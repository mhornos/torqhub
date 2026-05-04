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

    <?php
    $traducciones_garaje_js = [
        'campo_marca' => t('garaje.js.campo.marca'),
        'campo_modelo' => t('garaje.js.campo.modelo'),
        'campo_any' => t('garaje.js.campo.any'),
        'campo_vin' => t('garaje.js.campo.vin'),
        'campo_carroceria' => t('garaje.js.campo.carroceria'),
        'campo_tipo_combustible' => t('garaje.js.campo.tipo_combustible'),
        'campo_tipo_cambio' => t('garaje.js.campo.tipo_cambio'),
        'campo_potencia_cv' => t('garaje.js.campo.potencia_cv'),
        'campo_cilindrada_cm3' => t('garaje.js.campo.cilindrada_cm3'),
        'campo_imagen' => t('garaje.js.campo.imagen'),
        'campo_este_campo' => t('garaje.js.campo.este_campo'),

        'error_falta' => t('garaje.js.error.falta'),
        'error_any_rango' => t('garaje.js.error.any_rango'),
        'error_potencia_entero' => t('garaje.js.error.potencia_entero'),
        'error_cilindrada_entero' => t('garaje.js.error.cilindrada_entero'),
        'error_imagen_tipo' => t('garaje.js.error.imagen_tipo'),
        'error_imagen_tamanyo' => t('garaje.js.error.imagen_tamanyo'),

        'vin_no_preparar' => t('garaje.js.vin.no_preparar'),
        'vin_introduce' => t('garaje.js.vin.introduce'),
        'vin_formato' => t('garaje.js.vin.formato'),
        'vin_consultando_boton' => t('garaje.js.vin.consultando_boton'),
        'vin_consultando_mensaje' => t('garaje.js.vin.consultando_mensaje'),
        'vin_respuesta_no_json' => t('garaje.js.vin.respuesta_no_json'),
        'vin_no_consultar' => t('garaje.js.vin.no_consultar'),
        'vin_origen_cache' => t('garaje.js.vin.origen_cache'),
        'vin_origen_api' => t('garaje.js.vin.origen_api'),
        'vin_ok_desde' => t('garaje.js.vin.ok_desde'),
    ];
    ?>

    <form
        method="post"
        action="<?= url('/garaje/nuevo') ?>"
        enctype="multipart/form-data"
        class="formulario-garaje-validado"
        data-url-consultar-vin="<?= url('/garaje/vin/consultar') ?>"
        data-traducciones-garaje="<?= htmlspecialchars(json_encode($traducciones_garaje_js, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
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
                <option value="coche pequeño"><?= htmlspecialchars(t('garaje.opcion.carroceria.coche_pequeno')) ?></option>
                <option value="sedán"><?= htmlspecialchars(t('garaje.opcion.carroceria.sedan')) ?></option>
                <option value="familiar"><?= htmlspecialchars(t('garaje.opcion.carroceria.familiar')) ?></option>
                <option value="cabrio"><?= htmlspecialchars(t('garaje.opcion.carroceria.cabrio')) ?></option>
                <option value="coupé"><?= htmlspecialchars(t('garaje.opcion.carroceria.coupe')) ?></option>
                <option value="suv/4x4"><?= htmlspecialchars(t('garaje.opcion.carroceria.suv_4x4')) ?></option>
                <option value="monovolumen"><?= htmlspecialchars(t('garaje.opcion.carroceria.monovolumen')) ?></option>
                <option value="furgoneta"><?= htmlspecialchars(t('garaje.opcion.carroceria.furgoneta')) ?></option>
                <option value="otros"><?= htmlspecialchars(t('garaje.opcion.carroceria.otros')) ?></option>
            </select>
        </div>

        <div>
            <label for="tipo_combustible"><?= htmlspecialchars(t('garaje.form.tipo_combustible')) ?>:</label>
            <select name="tipo_combustible" id="tipo_combustible">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
                <option value="gasolina"><?= htmlspecialchars(t('garaje.opcion.combustible.gasolina')) ?></option>
                <option value="diesel"><?= htmlspecialchars(t('garaje.opcion.combustible.diesel')) ?></option>
                <option value="electrico"><?= htmlspecialchars(t('garaje.opcion.combustible.electrico')) ?></option>
                <option value="electro/gasolina"><?= htmlspecialchars(t('garaje.opcion.combustible.electro_gasolina')) ?></option>
                <option value="electro/diesel"><?= htmlspecialchars(t('garaje.opcion.combustible.electro_diesel')) ?></option>
                <option value="gas natural (CNG)"><?= htmlspecialchars(t('garaje.opcion.combustible.gas_natural')) ?></option>
                <option value="etanol"><?= htmlspecialchars(t('garaje.opcion.combustible.etanol')) ?></option>
                <option value="hidrogeno"><?= htmlspecialchars(t('garaje.opcion.combustible.hidrogeno')) ?></option>
                <option value="gas licuado (GLP)"><?= htmlspecialchars(t('garaje.opcion.combustible.glp')) ?></option>
                <option value="otros"><?= htmlspecialchars(t('garaje.opcion.combustible.otros')) ?></option>
            </select>
        </div>

        <div>
            <label for="tipo_cambio"><?= htmlspecialchars(t('garaje.form.tipo_cambio')) ?>:</label>
            <select name="tipo_cambio" id="tipo_cambio">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
                <option value="automatico"><?= htmlspecialchars(t('garaje.opcion.cambio.automatico')) ?></option>
                <option value="manual"><?= htmlspecialchars(t('garaje.opcion.cambio.manual')) ?></option>
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
        <a href="<?= url('/garaje') ?>">
            <?= htmlspecialchars(t('garaje.form.cancelar')) ?>
        </a>

        <button type="submit">
            <?= htmlspecialchars(t('garaje.form.guardar_vehiculo')) ?>
        </button>
    </form>

    <script src="<?= url('/public/js/garaje/formulario.js') ?>"></script>
    <script src="<?= url('/public/js/garaje/vin.js') ?>"></script>
</body>

</html>