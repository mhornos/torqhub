<?php
$publicaciones = isset($publicaciones) && is_array($publicaciones) ? $publicaciones : [];
?>

<!DOCTYPE html>
<html lang="<?= escapar(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar(t('admin.publicaciones.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= escapar(url('/public/css/estilos.css')) ?>">
</head>

<body>
    <main class="admin-panel">
        <section class="admin-panel__cabecera">
            <h1><?= escapar(t('admin.publicaciones.titulo')) ?></h1>

            <p>
                <?= escapar(t('admin.publicaciones.descripcion')) ?>
            </p>

            <a href="<?= escapar(url('/admin')) ?>" class="admin-enlace-volver">
                <?= escapar(t('admin.publicaciones.volver')) ?>
            </a>
        </section>

        <?php if ($m = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= escapar($m) ?></p>
        <?php endif; ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= escapar($m) ?></p>
        <?php endif; ?>

        <?php if (empty($publicaciones)): ?>
            <p><?= escapar(t('admin.publicaciones.sin_publicaciones')) ?></p>
        <?php else: ?>
            <div class="admin-tabla-contenedor">
                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th><?= escapar(t('admin.publicaciones.id')) ?></th>
                            <th><?= escapar(t('admin.publicaciones.autor')) ?></th>
                            <th><?= escapar(t('admin.publicaciones.contenido')) ?></th>
                            <th><?= escapar(t('admin.publicaciones.imagen')) ?></th>
                            <th><?= escapar(t('admin.publicaciones.estadisticas')) ?></th>
                            <th><?= escapar(t('admin.publicaciones.fecha')) ?></th>
                            <th><?= escapar(t('admin.publicaciones.acciones')) ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($publicaciones as $publicacion): ?>
                            <?php
                            $publicacion_id = (int) ($publicacion['id'] ?? 0);
                            $contenido = (string) ($publicacion['contenido'] ?? '');
                            $contenido_resumido = mb_strlen($contenido) > 140
                                ? mb_substr($contenido, 0, 140) . '...'
                                : $contenido;
                            ?>

                            <tr>
                                <td><?= $publicacion_id ?></td>

                                <td>
                                    <a href="<?= escapar(url('/perfil?usuario=' . urlencode($publicacion['autor_nombre'] ?? ''))) ?>">
                                        @<?= escapar($publicacion['autor_nombre'] ?? '') ?>
                                    </a>
                                </td>

                                <td class="admin-publicacion-contenido">
                                    <?= nl2br(escapar($contenido_resumido)) ?>
                                </td>

                                <td>
                                    <?php if (!empty($publicacion['imagen'])): ?>
                                        <img
                                            src="<?= escapar(url_publica_segura($publicacion['imagen'])) ?>"
                                            alt="<?= escapar(t('admin.publicaciones.alt_imagen')) ?>"
                                            class="admin-publicacion-imagen">
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <p>
                                        <?= (int) ($publicacion['total_comentarios'] ?? 0) ?>
                                        <?= escapar(t('admin.publicaciones.comentarios')) ?>
                                    </p>

                                    <p>
                                        <?= (int) ($publicacion['total_likes'] ?? 0) ?>
                                        <?= escapar(t('admin.publicaciones.likes')) ?>
                                    </p>
                                </td>

                                <td>
                                    <?= !empty($publicacion['fecha_creacion'])
                                        ? escapar(formatear_fecha($publicacion['fecha_creacion']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <div class="admin-acciones">
                                        <a href="<?= escapar(url('/comunidad/ver?id=' . $publicacion_id)) ?>" class="admin-enlace-accion">
                                            <?= escapar(t('admin.publicaciones.ver')) ?>
                                        </a>

                                        <form method="POST" action="<?= escapar(url('/admin/publicaciones/eliminar')) ?>" class="admin-formulario-accion">
                                            <?= csrf_campo() ?>

                                            <input type="hidden" name="publicacion_id" value="<?= $publicacion_id ?>">

                                            <label class="admin-confirmacion">
                                                <input type="checkbox" name="confirmar" value="1" required>
                                                <?= escapar(t('admin.publicaciones.confirmar_eliminar')) ?>
                                            </label>

                                            <button type="submit" class="admin-boton admin-boton--peligro">
                                                <?= escapar(t('admin.publicaciones.eliminar')) ?>
                                            </button>
                                        </form>
                                    </div>
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