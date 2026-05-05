<?php
$usuarios = isset($usuarios) && is_array($usuarios) ? $usuarios : [];
$usuario_actual_id = (int) ($_SESSION['usuario']['id'] ?? 0);
?>

<!DOCTYPE html>
<html lang="<?= escapar(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar(t('admin.usuarios.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= escapar(url('/public/css/estilos.css')) ?>">
</head>

<body>
    <main class="admin-panel">
        <section class="admin-panel__cabecera">
            <h1><?= escapar(t('admin.usuarios.titulo')) ?></h1>

            <p>
                <?= escapar(t('admin.usuarios.descripcion')) ?>
            </p>

            <a href="<?= escapar(url('/admin')) ?>" class="admin-enlace-volver">
                <?= escapar(t('admin.usuarios.volver')) ?>
            </a>
        </section>

        <?php if ($m = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= escapar($m) ?></p>
        <?php endif; ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= escapar($m) ?></p>
        <?php endif; ?>

        <?php if (empty($usuarios)): ?>
            <p><?= escapar(t('admin.usuarios.sin_usuarios')) ?></p>
        <?php else: ?>
            <div class="admin-tabla-contenedor">
                <table class="tabla-admin">
                    <thead>
                        <tr>
                            <th><?= escapar(t('admin.usuarios.id')) ?></th>
                            <th><?= escapar(t('admin.usuarios.nombre')) ?></th>
                            <th><?= escapar(t('admin.usuarios.email')) ?></th>
                            <th><?= escapar(t('admin.usuarios.rol')) ?></th>
                            <th><?= escapar(t('admin.usuarios.estado')) ?></th>
                            <th><?= escapar(t('admin.usuarios.fecha_creacion')) ?></th>
                            <th><?= escapar(t('admin.usuarios.acciones')) ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <?php
                            $usuario_id = (int) ($usuario['id'] ?? 0);
                            $rol = (string) ($usuario['rol'] ?? 'usuario');
                            $activo = (int) ($usuario['activo'] ?? 1);
                            $es_usuario_actual = $usuario_id === $usuario_actual_id;
                            ?>

                            <tr class="<?= $activo === 1 ? '' : 'fila-inactiva' ?>">
                                <td><?= $usuario_id ?></td>

                                <td>
                                    @<?= escapar($usuario['nombre'] ?? '') ?>
                                </td>

                                <td>
                                    <?= escapar($usuario['email'] ?? '') ?>
                                </td>

                                <td>
                                    <?= $rol === 'admin'
                                        ? escapar(t('admin.usuarios.rol.admin'))
                                        : escapar(t('admin.usuarios.rol.usuario')) ?>
                                </td>

                                <td>
                                    <span class="admin-estado <?= $activo === 1 ? 'admin-estado--activo' : 'admin-estado--inactivo' ?>">
                                        <?= $activo === 1
                                            ? escapar(t('admin.usuarios.activo'))
                                            : escapar(t('admin.usuarios.inactivo')) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= !empty($usuario['fecha_creacion'])
                                        ? escapar(formatear_fecha($usuario['fecha_creacion']))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?php if ($es_usuario_actual): ?>
                                        <span class="admin-accion-bloqueada">
                                            <?= escapar(t('admin.usuarios.accion_propia')) ?>
                                        </span>
                                    <?php else: ?>
                                        <div class="admin-acciones">
                                            <form method="POST" action="<?= escapar(url('/admin/usuarios/rol')) ?>" class="admin-formulario-accion">
                                                <?= csrf_campo() ?>

                                                <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">

                                                <select name="rol" aria-label="<?= escapar(t('admin.usuarios.cambiar_rol')) ?>">
                                                    <option value="usuario" <?= $rol === 'usuario' ? 'selected' : '' ?>>
                                                        <?= escapar(t('admin.usuarios.rol.usuario')) ?>
                                                    </option>

                                                    <option value="admin" <?= $rol === 'admin' ? 'selected' : '' ?>>
                                                        <?= escapar(t('admin.usuarios.rol.admin')) ?>
                                                    </option>
                                                </select>

                                                <button type="submit" class="admin-boton">
                                                    <?= escapar(t('admin.usuarios.guardar_rol')) ?>
                                                </button>
                                            </form>

                                            <form method="POST" action="<?= escapar(url('/admin/usuarios/estado')) ?>" class="admin-formulario-accion">
                                                <?= csrf_campo() ?>

                                                <input type="hidden" name="usuario_id" value="<?= $usuario_id ?>">
                                                <input type="hidden" name="activo" value="<?= $activo === 1 ? 0 : 1 ?>">

                                                <button type="submit" class="admin-boton admin-boton--secundario">
                                                    <?= $activo === 1
                                                        ? escapar(t('admin.usuarios.desactivar'))
                                                        : escapar(t('admin.usuarios.activar')) ?>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
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