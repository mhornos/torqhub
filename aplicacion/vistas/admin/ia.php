<?php
$causas_ia = isset($causas_ia) && is_array($causas_ia) ? $causas_ia : [];
$total_keywords = 0;

foreach ($causas_ia as $causa) {
    $total_keywords += count($causa['keywords'] ?? []);
}
?>

<!DOCTYPE html>
<html lang="<?= escapar(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar(t('admin.ia.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= escapar(url('/public/css/estilos.css')) ?>">
</head>

<body>
    <main class="admin-panel">
        <section class="admin-panel__cabecera">
            <h1><?= escapar(t('admin.ia.titulo')) ?></h1>

            <p>
                <?= escapar(t('admin.ia.descripcion')) ?>
            </p>

            <a href="<?= escapar(url('/admin')) ?>" class="admin-enlace-volver">
                <?= escapar(t('admin.ia.volver')) ?>
            </a>
        </section>

        <?php if ($m = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= escapar($m) ?></p>
        <?php endif; ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= escapar($m) ?></p>
        <?php endif; ?>

        <section class="admin-bloque-info">
            <h2><?= escapar(t('admin.ia.resumen.titulo')) ?></h2>

            <p>
                <?= escapar(t('admin.ia.resumen.texto')) ?>
            </p>

            <div class="admin-ia-resumen">
                <article class="admin-ia-resumen__dato">
                    <strong><?= count($causas_ia) ?></strong>
                    <span><?= escapar(t('admin.ia.resumen.causas')) ?></span>
                </article>

                <article class="admin-ia-resumen__dato">
                    <strong><?= $total_keywords ?></strong>
                    <span><?= escapar(t('admin.ia.resumen.keywords')) ?></span>
                </article>
            </div>
        </section>

        <?php if (empty($causas_ia)): ?>
            <section class="admin-bloque-info">
                <p><?= escapar(t('admin.ia.sin_causas')) ?></p>
            </section>
        <?php else: ?>
            <div class="admin-tabla-contenedor">
                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th><?= escapar(t('admin.ia.tabla.id')) ?></th>
                            <th><?= escapar(t('admin.ia.tabla.causa')) ?></th>
                            <th><?= escapar(t('admin.ia.tabla.recomendacion')) ?></th>
                            <th><?= escapar(t('admin.ia.tabla.estado')) ?></th>
                            <th><?= escapar(t('admin.ia.tabla.keywords')) ?></th>
                            <th><?= escapar(t('admin.ia.tabla.fecha')) ?></th>
                            <th><?= escapar(t('admin.ia.tabla.acciones')) ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($causas_ia as $causa): ?>
                            <?php
                            $activo = (int) ($causa['activo'] ?? 0);
                            $keywords = $causa['keywords'] ?? [];
                            ?>

                            <tr class="<?= $activo === 1 ? '' : 'fila-inactiva' ?>">
                                <td>
                                    <?= (int) ($causa['id'] ?? 0) ?>
                                </td>

                                <td>
                                    <strong><?= escapar($causa['titulo'] ?? '') ?></strong>

                                    <span class="admin-ia-clave">
                                        <?= escapar($causa['clave'] ?? '') ?>
                                    </span>
                                </td>

                                <td class="admin-ia-texto-largo">
                                    <?= escapar($causa['recomendacion'] ?? '') ?>
                                </td>

                                <td>
                                    <span class="admin-estado <?= $activo === 1 ? 'admin-estado--activo' : 'admin-estado--inactivo' ?>">
                                        <?= $activo === 1
                                            ? escapar(t('admin.ia.estado.activa'))
                                            : escapar(t('admin.ia.estado.inactiva')) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if (empty($keywords)): ?>
                                        <span class="admin-accion-bloqueada">
                                            <?= escapar(t('admin.ia.sin_keywords')) ?>
                                        </span>
                                    <?php else: ?>
                                        <div class="admin-ia-keywords">
                                            <?php foreach ($keywords as $keyword): ?>
                                                <span class="admin-ia-keyword">
                                                    <?= escapar($keyword) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= !empty($causa['fecha_creacion'])
                                        ? escapar(formatear_fecha($causa['fecha_creacion']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <form method="POST" action="<?= escapar(url('/admin/ia/estado')) ?>" class="admin-formulario-accion">
                                        <?= csrf_campo() ?>

                                        <input type="hidden" name="causa_id" value="<?= (int) ($causa['id'] ?? 0) ?>">
                                        <input type="hidden" name="activo" value="<?= $activo === 1 ? 0 : 1 ?>">

                                        <button type="submit" class="admin-boton <?= $activo === 1 ? 'admin-boton--secundario' : '' ?>">
                                            <?= $activo === 1
                                                ? escapar(t('admin.ia.desactivar'))
                                                : escapar(t('admin.ia.activar')) ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>