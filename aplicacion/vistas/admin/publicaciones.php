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
    <main class="admin-contenedor">
        <header class="admin-cabecera">
            <div class="admin-cabecera__texto">
                <p class="admin-cabecera__etiqueta">
                    <?= escapar(t('admin.titulo_pagina')) ?>
                </p>

                <h1><?= escapar(t('admin.publicaciones.titulo')) ?></h1>

                <p>
                    <?= escapar(t('admin.publicaciones.descripcion')) ?>
                </p>
            </div>

            <div class="admin-cabecera__acciones">
                <a href="<?= escapar(url('/admin')) ?>" class="admin-boton-enlace">
                    <?= escapar(t('admin.publicaciones.volver')) ?>
                </a>
            </div>
        </header>

        <?php if ($m = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= escapar($m) ?></p>
        <?php endif; ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= escapar($m) ?></p>
        <?php endif; ?>

        <?php if (empty($publicaciones)): ?>
            <section class="admin-estado-vacio">
                <p><?= escapar(t('admin.publicaciones.sin_publicaciones')) ?></p>
            </section>
        <?php else: ?>
            <section class="admin-tabla-panel">
                <div class="admin-tabla-contenedor">
                    <table class="tabla-admin tabla-admin--publicaciones">
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
                                            <span class="admin-accion-bloqueada">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="admin-publicacion-estadisticas">
                                            <p>
                                                <?= (int) ($publicacion['total_comentarios'] ?? 0) ?>
                                                <?= escapar(t('admin.publicaciones.comentarios')) ?>
                                            </p>

                                            <p>
                                                <?= (int) ($publicacion['total_likes'] ?? 0) ?>
                                                <?= escapar(t('admin.publicaciones.likes')) ?>
                                            </p>
                                        </div>
                                    </td>

                                    <td>
                                        <?= !empty($publicacion['fecha_creacion'])
                                            ? escapar(formatear_fecha($publicacion['fecha_creacion']))
                                            : '-' ?>
                                    </td>

                                    <td class="admin-tabla-celda-acciones">
                                        <div class="admin-acciones">
                                            <a href="<?= escapar(url('/comunidad/ver?id=' . $publicacion_id)) ?>" class="admin-boton-enlace admin-boton-enlace--compacto">
                                                <?= escapar(t('admin.publicaciones.ver')) ?>
                                            </a>

                                            <form method="POST" action="<?= escapar(url('/admin/publicaciones/eliminar')) ?>" class="admin-formulario-accion admin-formulario-accion--vertical">
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
            </section>
        <?php endif; ?>
    </main>
</body>

</html>