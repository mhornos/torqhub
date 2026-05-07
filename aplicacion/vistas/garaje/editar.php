<?php
if (!isset($vehiculo) || !is_array($vehiculo)) {
    flash_set('error', t('garaje.form.error.cargar_editar'));
    header('Location: ' . url('/garaje'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(t('garaje.form.editar.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>

<body>
    <h1><?= htmlspecialchars(t('garaje.form.editar.titulo')) ?></h1>

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
        'campo_imagenes' => t('garaje.js.campo.imagenes'),
        'error_imagenes_limite' => t('garaje.js.error.imagenes_limite'),

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
        action="<?= url('/garaje/editar') ?>"
        enctype="multipart/form-data"
        class="formulario-garaje-validado"
        data-url-consultar-vin="<?= url('/garaje/vin/consultar') ?>"
        data-traducciones-garaje="<?= htmlspecialchars(json_encode($traducciones_garaje_js, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
        novalidate>
        <?= csrf_campo() ?>

        <input type="hidden" name="id" value="<?= (int) $vehiculo['id'] ?>">

        <div>
            <label><?= htmlspecialchars(t('garaje.form.marca')) ?>: *</label>
            <input type="text" name="marca" value="<?= htmlspecialchars($vehiculo['marca']) ?>" required>
        </div>

        <div>
            <label><?= htmlspecialchars(t('garaje.form.modelo')) ?>: *</label>
            <input type="text" name="modelo" value="<?= htmlspecialchars($vehiculo['modelo']) ?>" required>
        </div>

        <div>
            <label><?= htmlspecialchars(t('garaje.form.any')) ?>:</label>
            <input type="number" name="any" min="1900" max="2100" value="<?= htmlspecialchars((string) ($vehiculo['any'] ?? '')) ?>" required>
        </div>
        <label for="vin"><?= htmlspecialchars(t('garaje.form.vin')) ?>:</label>
        <input
            type="text"
            name="vin"
            id="vin"
            maxlength="17"
            autocomplete="off"
            value="<?= htmlspecialchars((string) ($vehiculo['vin'] ?? '')) ?>"> <br>

        <button type="button" class="boton-consultar-vin">
            <?= htmlspecialchars(t('garaje.form.consultar_vin')) ?>
        </button> <br>

        <small><?= htmlspecialchars(t('garaje.form.vin_ayuda_editar')) ?>.</small>
        <p class="mensaje-vin" aria-live="polite"></p>

        <div>
            <label for="carroceria"><?= htmlspecialchars(t('garaje.form.carroceria')) ?>:</label>
            <select name="carroceria" id="carroceria">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
                <option value="coche pequeño" <?= ($vehiculo['carroceria'] ?? '') === 'coche pequeño' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.carroceria.coche_pequeno')) ?></option>
                <option value="sedán" <?= ($vehiculo['carroceria'] ?? '') === 'sedán' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.carroceria.sedan')) ?></option>
                <option value="familiar" <?= ($vehiculo['carroceria'] ?? '') === 'familiar' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.carroceria.familiar')) ?></option>
                <option value="cabrio" <?= ($vehiculo['carroceria'] ?? '') === 'cabrio' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.carroceria.cabrio')) ?></option>
                <option value="coupé" <?= ($vehiculo['carroceria'] ?? '') === 'coupé' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.carroceria.coupe')) ?></option>
                <option value="suv/4x4" <?= ($vehiculo['carroceria'] ?? '') === 'suv/4x4' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.carroceria.suv_4x4')) ?></option>
                <option value="monovolumen" <?= ($vehiculo['carroceria'] ?? '') === 'monovolumen' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.carroceria.monovolumen')) ?></option>
                <option value="furgoneta" <?= ($vehiculo['carroceria'] ?? '') === 'furgoneta' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.carroceria.furgoneta')) ?></option>
                <option value="otros" <?= ($vehiculo['carroceria'] ?? '') === 'otros' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.carroceria.otros')) ?></option>
            </select>
        </div>

        <div>
            <label for="tipo_combustible"><?= htmlspecialchars(t('garaje.form.tipo_combustible')) ?>:</label>
            <select name="tipo_combustible" id="tipo_combustible">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
                <option value="gasolina" <?= ($vehiculo['tipo_combustible'] ?? '') === 'gasolina' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.gasolina')) ?></option>
                <option value="diesel" <?= ($vehiculo['tipo_combustible'] ?? '') === 'diesel' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.diesel')) ?></option>
                <option value="electrico" <?= ($vehiculo['tipo_combustible'] ?? '') === 'electrico' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.electrico')) ?></option>
                <option value="electro/gasolina" <?= ($vehiculo['tipo_combustible'] ?? '') === 'electro/gasolina' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.electro_gasolina')) ?></option>
                <option value="electro/diesel" <?= ($vehiculo['tipo_combustible'] ?? '') === 'electro/diesel' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.electro_diesel')) ?></option>
                <option value="gas natural (CNG)" <?= ($vehiculo['tipo_combustible'] ?? '') === 'gas natural (CNG)' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.gas_natural')) ?></option>
                <option value="etanol" <?= ($vehiculo['tipo_combustible'] ?? '') === 'etanol' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.etanol')) ?></option>
                <option value="hidrogeno" <?= ($vehiculo['tipo_combustible'] ?? '') === 'hidrogeno' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.hidrogeno')) ?></option>
                <option value="gas licuado (GLP)" <?= ($vehiculo['tipo_combustible'] ?? '') === 'gas licuado (GLP)' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.glp')) ?></option>
                <option value="otros" <?= ($vehiculo['tipo_combustible'] ?? '') === 'otros' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.combustible.otros')) ?></option>
            </select>
        </div>

        <div>
            <label for="tipo_cambio"><?= htmlspecialchars(t('garaje.form.tipo_cambio')) ?>:</label>
            <select name="tipo_cambio" id="tipo_cambio">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
                <option value="automatico" <?= ($vehiculo['tipo_cambio'] ?? '') === 'automatico' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.cambio.automatico')) ?></option>
                <option value="manual" <?= ($vehiculo['tipo_cambio'] ?? '') === 'manual' ? 'selected' : '' ?>><?= htmlspecialchars(t('garaje.opcion.cambio.manual')) ?></option>
            </select>
        </div>

        <div>
            <label for="potencia_cv"><?= htmlspecialchars(t('garaje.form.potencia_cv')) ?>:</label>
            <input
                type="number"
                name="potencia_cv"
                id="potencia_cv"
                min="0"
                value="<?= isset($vehiculo['potencia_cv']) && $vehiculo['potencia_cv'] !== null ? (int) $vehiculo['potencia_cv'] : '' ?>">
        </div>

        <div>
            <label for="cilindrada_cm3"><?= htmlspecialchars(t('garaje.form.cilindrada_cm3')) ?>:</label>
            <input
                type="number"
                name="cilindrada_cm3"
                id="cilindrada_cm3"
                min="0"
                value="<?= isset($vehiculo['cilindrada_cm3']) && $vehiculo['cilindrada_cm3'] !== null ? (int) $vehiculo['cilindrada_cm3'] : '' ?>">
        </div> <br>

        <div>
            <label for="imagenes"><?= htmlspecialchars(t('garaje.form.cambiar_imagen')) ?>:</label>
            <input
                type="file"
                name="imagenes[]"
                id="imagenes"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                multiple>
            <br>
            <small><?= htmlspecialchars(t('garaje.form.cambiar_imagen_ayuda')) ?>.</small>
        </div>

        <?php if (!empty($vehiculo['imagen'])): ?>
            <p>
                <strong><?= htmlspecialchars(t('garaje.form.imagen_actual')) ?>:</strong><br>
                <img
                    src="<?= escapar(url_publica_segura('uploads/vehiculos/' . $vehiculo['imagen'])) ?>"
                    alt="<?= htmlspecialchars(t('garaje.form.alt_imagen_actual')) ?>"
                    style="max-width: 280px; height: auto; margin-top: 8px;">
            </p>
        <?php endif; ?>

        <br>
        <a href="<?= url('/garaje/ver') ?>">
            <?= htmlspecialchars(t('garaje.form.cancelar')) ?>
        </a>

        <button type="submit">
            <?= htmlspecialchars(t('garaje.form.guardar_cambios')) ?>
        </button>
    </form>

    <script src="<?= url('/public/js/garaje/formulario.js') ?>"></script>
    <script src="<?= url('/public/js/garaje/vin.js') ?>"></script>
</body>

</html>