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

    <form
        method="post"
        action="<?= url('/garaje/editar') ?>"
        enctype="multipart/form-data"
        class="formulario-garaje-validado"
        data-url-consultar-vin="<?= url('/garaje/vin/consultar') ?>"
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
            <label for="tipo_combustible"><?= htmlspecialchars(t('garaje.form.tipo_combustible')) ?>:</label>
            <select name="tipo_combustible" id="tipo_combustible">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
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
            <label for="tipo_cambio"><?= htmlspecialchars(t('garaje.form.tipo_cambio')) ?>:</label>
            <select name="tipo_cambio" id="tipo_cambio">
                <option value=""><?= htmlspecialchars(t('garaje.form.selecciona_opcion')) ?></option>
                <option value="automatico" <?= ($vehiculo['tipo_cambio'] ?? '') === 'automatico' ? 'selected' : '' ?>>Automático</option>
                <option value="manual" <?= ($vehiculo['tipo_cambio'] ?? '') === 'manual' ? 'selected' : '' ?>>Manual</option>
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
            <label for="imagen"><?= htmlspecialchars(t('garaje.form.cambiar_imagen')) ?>:</label>
            <input type="file" name="imagen" id="imagen" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"> <br>
            <small><?= htmlspecialchars(t('garaje.form.cambiar_imagen_ayuda')) ?>.</small>
        </div>

        <?php if (!empty($vehiculo['imagen'])): ?>
            <p>
                <strong><?= htmlspecialchars(t('garaje.form.imagen_actual')) ?>:</strong><br>
                <img
                    src="<?= url('/public/uploads/vehiculos/' . rawurlencode($vehiculo['imagen'])) ?>"
                    alt="imagen actual del vehiculo"
                    style="max-width: 280px; height: auto; margin-top: 8px;">
            </p>
        <?php endif; ?>

        <br>
        <button type="button" onclick="history.back()">
            <?= htmlspecialchars(t('garaje.form.cancelar')) ?>
        </button>

        <button type="submit">
            <?= htmlspecialchars(t('garaje.form.guardar_cambios')) ?>
        </button>
    </form>

    <script src="<?= url('/public/js/garaje/formulario.js') ?>"></script>
    <script src="<?= url('/public/js/garaje/vin.js') ?>"></script>
</body>

</html>