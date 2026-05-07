<?php
$usuario_autenticado = isset($usuario_autenticado) ? (bool) $usuario_autenticado : isset($_SESSION['usuario']);
$usuario_nombre = isset($usuario_nombre) ? (string) $usuario_nombre : (string) ($_SESSION['usuario']['nombre'] ?? '');

$url_perfil = $usuario_nombre !== ''
    ? url('/perfil?usuario=' . urlencode($usuario_nombre))
    : url('/perfil');

$estadisticas_dashboard = isset($estadisticas_dashboard) && is_array($estadisticas_dashboard)
    ? $estadisticas_dashboard
    : [];

$total_vehiculos = (int) ($estadisticas_dashboard['total_vehiculos'] ?? 0);
$total_mantenimientos = (int) ($estadisticas_dashboard['total_mantenimientos'] ?? 0);
$total_publicaciones = (int) ($estadisticas_dashboard['total_publicaciones'] ?? 0);
$total_likes_recibidos = (int) ($estadisticas_dashboard['total_likes_recibidos'] ?? 0);

$actividad_reciente = isset($actividad_reciente) && is_array($actividad_reciente)
    ? $actividad_reciente
    : [];

$ultimos_vehiculos = isset($actividad_reciente['ultimos_vehiculos']) && is_array($actividad_reciente['ultimos_vehiculos'])
    ? $actividad_reciente['ultimos_vehiculos']
    : [];

$ultimos_mantenimientos = isset($actividad_reciente['ultimos_mantenimientos']) && is_array($actividad_reciente['ultimos_mantenimientos'])
    ? $actividad_reciente['ultimos_mantenimientos']
    : [];

$ultimas_publicaciones = isset($actividad_reciente['ultimas_publicaciones']) && is_array($actividad_reciente['ultimas_publicaciones'])
    ? $actividad_reciente['ultimas_publicaciones']
    : [];

?>

<!DOCTYPE html>
<html lang="<?= escapar(idioma_actual()) ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar(t('inicio.titulo_pagina')) ?> - TorqHub</title>
    <link rel="stylesheet" href="<?= escapar(url('/public/css/estilos.css')) ?>">
</head>

<body>
    <main class="inicio-contenedor">
        <?php if ($m = flash_get('ok')): ?>
            <p class="mensaje-ok"><?= escapar($m) ?></p>
        <?php endif; ?>

        <?php if ($m = flash_get('error')): ?>
            <p class="mensaje-error"><?= escapar($m) ?></p>
        <?php endif; ?>

        <?php if (!$usuario_autenticado): ?>
            <header class="inicio-hero">
                <div class="inicio-hero__contenido">
                    <p class="inicio-etiqueta">
                        <?= escapar(t('inicio.publico.etiqueta')) ?>
                    </p>

                    <h1><?= escapar(t('inicio.publico.titulo')) ?></h1>

                    <p class="inicio-hero__descripcion">
                        <?= escapar(t('inicio.publico.descripcion')) ?>
                    </p>

                    <div class="inicio-acciones">
                        <a href="<?= escapar(url('/login')) ?>" class="inicio-boton-enlace inicio-boton-enlace--principal">
                            <?= escapar(t('inicio.publico.login')) ?>
                        </a>

                        <a href="<?= escapar(url('/registro')) ?>" class="inicio-boton-enlace">
                            <?= escapar(t('inicio.publico.registro')) ?>
                        </a>
                    </div>
                </div>

                <aside class="inicio-hero__panel" aria-label="<?= escapar(t('inicio.publico.panel.etiqueta')) ?>">
                    <p class="inicio-etiqueta">
                        <?= escapar(t('inicio.publico.panel.etiqueta')) ?>
                    </p>

                    <h2><?= escapar(t('inicio.publico.panel.titulo')) ?></h2>

                    <p>
                        <?= escapar(t('inicio.publico.panel.descripcion')) ?>
                    </p>

                    <ul class="inicio-lista">
                        <li><?= escapar(t('inicio.publico.panel.mvc')) ?></li>
                        <li><?= escapar(t('inicio.publico.panel.seguridad')) ?></li>
                        <li><?= escapar(t('inicio.publico.panel.multilenguaje')) ?></li>
                    </ul>
                </aside>
            </header>

            <section class="inicio-seccion" aria-label="<?= escapar(t('inicio.publico.funcionalidades')) ?>">
                <div class="inicio-seccion__cabecera">
                    <h2><?= escapar(t('inicio.publico.funcionalidades')) ?></h2>

                    <p>
                        <?= escapar(t('inicio.publico.funcionalidades.descripcion')) ?>
                    </p>
                </div>

                <div class="inicio-grid">
                    <article class="inicio-tarjeta">
                        <span class="inicio-tarjeta__etiqueta">
                            <?= escapar(t('inicio.publico.garaje.etiqueta')) ?>
                        </span>

                        <h3><?= escapar(t('inicio.publico.garaje.titulo')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.publico.garaje.descripcion')) ?>
                        </p>
                    </article>

                    <article class="inicio-tarjeta">
                        <span class="inicio-tarjeta__etiqueta">
                            <?= escapar(t('inicio.publico.comunidad.etiqueta')) ?>
                        </span>

                        <h3><?= escapar(t('inicio.publico.comunidad.titulo')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.publico.comunidad.descripcion')) ?>
                        </p>
                    </article>

                    <article class="inicio-tarjeta">
                        <span class="inicio-tarjeta__etiqueta">
                            <?= escapar(t('inicio.publico.diagnostico.etiqueta')) ?>
                        </span>

                        <h3><?= escapar(t('inicio.publico.diagnostico.titulo')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.publico.diagnostico.descripcion')) ?>
                        </p>
                    </article>

                    <article class="inicio-tarjeta">
                        <span class="inicio-tarjeta__etiqueta">
                            <?= escapar(t('inicio.publico.perfiles.etiqueta')) ?>
                        </span>

                        <h3><?= escapar(t('inicio.publico.perfiles.titulo')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.publico.perfiles.descripcion')) ?>
                        </p>
                    </article>
                </div>
            </section>
        <?php else: ?>
            <header class="inicio-hero inicio-hero--dashboard">
                <div class="inicio-hero__contenido">
                    <p class="inicio-etiqueta">
                        <?= escapar(t('inicio.dashboard.etiqueta')) ?>
                    </p>

                    <h1>
                        <?= escapar(t('inicio.dashboard.titulo')) ?>
                        <?php if ($usuario_nombre !== ''): ?>
                            , <?= escapar($usuario_nombre) ?>
                        <?php endif; ?>
                    </h1>

                    <p class="inicio-hero__descripcion">
                        <?= escapar(t('inicio.dashboard.descripcion')) ?>
                    </p>

                    <div class="inicio-acciones">
                        <a href="<?= escapar(url('/garaje')) ?>" class="inicio-boton-enlace inicio-boton-enlace--principal">
                            <?= escapar(t('inicio.dashboard.accion_garaje')) ?>
                        </a>

                        <a href="<?= escapar(url('/comunidad/nueva')) ?>" class="inicio-boton-enlace">
                            <?= escapar(t('inicio.dashboard.accion_publicacion')) ?>
                        </a>
                    </div>
                </div>

                <aside class="inicio-hero__panel" aria-label="<?= escapar(t('inicio.dashboard.panel.etiqueta')) ?>">
                    <p class="inicio-etiqueta">
                        <?= escapar(t('inicio.dashboard.panel.etiqueta')) ?>
                    </p>

                    <h2><?= escapar(t('inicio.dashboard.panel.titulo')) ?></h2>

                    <p>
                        <?= escapar(t('inicio.dashboard.panel.descripcion')) ?>
                    </p>

                    <ul class="inicio-lista">
                        <li><?= escapar(t('inicio.dashboard.panel.garaje')) ?></li>
                        <li><?= escapar(t('inicio.dashboard.panel.comunidad')) ?></li>
                        <li><?= escapar(t('inicio.dashboard.panel.diagnostico')) ?></li>
                    </ul>
                </aside>
            </header>

            <section class="inicio-seccion" aria-label="<?= escapar(t('inicio.dashboard.resumen')) ?>">
                <div class="inicio-seccion__cabecera">
                    <h2><?= escapar(t('inicio.dashboard.resumen')) ?></h2>

                    <p>
                        <?= escapar(t('inicio.dashboard.resumen_descripcion')) ?>
                    </p>
                </div>

                <div class="inicio-grid">
                    <a href="<?= escapar(url('/garaje')) ?>" class="inicio-tarjeta inicio-tarjeta--enlace inicio-tarjeta--contador">
                        <span class="inicio-tarjeta__etiqueta">
                            <?= escapar(t('inicio.dashboard.vehiculos.etiqueta')) ?>
                        </span>

                        <strong class="inicio-tarjeta__numero">
                            <?= escapar($total_vehiculos) ?>
                        </strong>

                        <h3><?= escapar(t('inicio.dashboard.vehiculos.titulo')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.dashboard.vehiculos.descripcion')) ?>
                        </p>
                    </a>

                    <a href="<?= escapar(url('/garaje')) ?>" class="inicio-tarjeta inicio-tarjeta--enlace inicio-tarjeta--contador">
                        <span class="inicio-tarjeta__etiqueta">
                            <?= escapar(t('inicio.dashboard.mantenimientos.etiqueta')) ?>
                        </span>

                        <strong class="inicio-tarjeta__numero">
                            <?= escapar($total_mantenimientos) ?>
                        </strong>

                        <h3><?= escapar(t('inicio.dashboard.mantenimientos.titulo')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.dashboard.mantenimientos.descripcion')) ?>
                        </p>
                    </a>

                    <a href="<?= escapar($url_perfil) ?>" class="inicio-tarjeta inicio-tarjeta--enlace inicio-tarjeta--contador">
                        <span class="inicio-tarjeta__etiqueta">
                            <?= escapar(t('inicio.dashboard.publicaciones.etiqueta')) ?>
                        </span>

                        <strong class="inicio-tarjeta__numero">
                            <?= escapar($total_publicaciones) ?>
                        </strong>

                        <h3><?= escapar(t('inicio.dashboard.publicaciones.titulo')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.dashboard.publicaciones.descripcion')) ?>
                        </p>
                    </a>

                    <a href="<?= escapar($url_perfil) ?>" class="inicio-tarjeta inicio-tarjeta--enlace inicio-tarjeta--contador">
                        <span class="inicio-tarjeta__etiqueta">
                            <?= escapar(t('inicio.dashboard.likes.etiqueta')) ?>
                        </span>

                        <strong class="inicio-tarjeta__numero">
                            <?= escapar($total_likes_recibidos) ?>
                        </strong>

                        <h3><?= escapar(t('inicio.dashboard.likes.titulo')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.dashboard.likes.descripcion')) ?>
                        </p>
                    </a>
                </div>
            </section>

            <section class="inicio-seccion" aria-label="<?= escapar(t('inicio.dashboard.acciones')) ?>">
                <div class="inicio-seccion__cabecera">
                    <h2><?= escapar(t('inicio.dashboard.acciones')) ?></h2>

                    <p>
                        <?= escapar(t('inicio.dashboard.acciones_descripcion')) ?>
                    </p>
                </div>

                <div class="inicio-grid inicio-grid--dos">
                    <a href="<?= escapar(url('/garaje/nuevo')) ?>" class="inicio-tarjeta inicio-tarjeta--enlace inicio-tarjeta--accion">
                        <h3><?= escapar(t('inicio.dashboard.accion.anadir_vehiculo')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.dashboard.accion.anadir_vehiculo.descripcion')) ?>
                        </p>
                    </a>

                    <a href="<?= escapar(url('/comunidad/nueva')) ?>" class="inicio-tarjeta inicio-tarjeta--enlace inicio-tarjeta--accion">
                        <h3><?= escapar(t('inicio.dashboard.accion.crear_publicacion')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.dashboard.accion.crear_publicacion.descripcion')) ?>
                        </p>
                    </a>

                    <a href="<?= escapar(url('/diagnostico')) ?>" class="inicio-tarjeta inicio-tarjeta--enlace inicio-tarjeta--accion">
                        <h3><?= escapar(t('inicio.dashboard.accion.hacer_diagnostico')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.dashboard.accion.hacer_diagnostico.descripcion')) ?>
                        </p>
                    </a>

                    <a href="<?= escapar($url_perfil) ?>" class="inicio-tarjeta inicio-tarjeta--enlace inicio-tarjeta--accion">
                        <h3><?= escapar(t('inicio.dashboard.accion.ver_perfil')) ?></h3>

                        <p>
                            <?= escapar(t('inicio.dashboard.accion.ver_perfil.descripcion')) ?>
                        </p>
                    </a>
                </div>
            </section>

            <section class="inicio-seccion" aria-label="<?= escapar(t('inicio.dashboard.actividad')) ?>">
                <div class="inicio-seccion__cabecera">
                    <h2><?= escapar(t('inicio.dashboard.actividad')) ?></h2>

                    <p>
                        <?= escapar(t('inicio.dashboard.actividad.descripcion')) ?>
                    </p>
                </div>

                <div class="inicio-actividad-grid">
                    <article class="inicio-actividad-bloque">
                        <div class="inicio-actividad-bloque__cabecera">
                            <h3><?= escapar(t('inicio.dashboard.actividad.vehiculos')) ?></h3>

                            <a href="<?= escapar(url('/garaje')) ?>">
                                <?= escapar(t('inicio.dashboard.actividad.ver_todo')) ?>
                            </a>
                        </div>

                        <?php if (empty($ultimos_vehiculos)): ?>
                            <p class="inicio-vacio">
                                <?= escapar(t('inicio.dashboard.actividad.vehiculos.vacio')) ?>
                            </p>
                        <?php else: ?>
                            <ul class="inicio-lista-actividad">
                                <?php foreach ($ultimos_vehiculos as $vehiculo): ?>
                                    <li>
                                        <a href="<?= escapar(url('/garaje/ver?id=' . (int) $vehiculo['id'])) ?>" class="inicio-item-actividad">
                                            <strong>
                                                <?= escapar(($vehiculo['marca'] ?? '') . ' ' . ($vehiculo['modelo'] ?? '')) ?>
                                            </strong>

                                            <span>
                                                <?= escapar((string) ($vehiculo['any'] ?? '')) ?>
                                                ·
                                                <?= !empty($vehiculo['fecha_creacion']) ? escapar(formatear_fecha($vehiculo['fecha_creacion'])) : escapar(t('inicio.dashboard.actividad.sin_fecha')) ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </article>

                    <article class="inicio-actividad-bloque">
                        <div class="inicio-actividad-bloque__cabecera">
                            <h3><?= escapar(t('inicio.dashboard.actividad.mantenimientos')) ?></h3>

                            <a href="<?= escapar(url('/garaje')) ?>">
                                <?= escapar(t('inicio.dashboard.actividad.ver_todo')) ?>
                            </a>
                        </div>

                        <?php if (empty($ultimos_mantenimientos)): ?>
                            <p class="inicio-vacio">
                                <?= escapar(t('inicio.dashboard.actividad.mantenimientos.vacio')) ?>
                            </p>
                        <?php else: ?>
                            <ul class="inicio-lista-actividad">
                                <?php foreach ($ultimos_mantenimientos as $mantenimiento): ?>
                                    <?php
                                    $fecha_mantenimiento = strtotime((string) ($mantenimiento['fecha'] ?? ''));
                                    $coste_mantenimiento = $mantenimiento['coste'] ?? null;
                                    ?>

                                    <li>
                                        <a href="<?= escapar(url('/garaje/ver?id=' . (int) $mantenimiento['vehiculo_id'] . '#final')) ?>" class="inicio-item-actividad" >
                                            <strong>
                                                <?= escapar((string) ($mantenimiento['tipo'] ?? '')) ?>
                                            </strong>

                                            <span>
                                                <?= escapar(trim(($mantenimiento['marca'] ?? '') . ' ' . ($mantenimiento['modelo'] ?? ''))) ?>
                                                ·
                                                <?= $fecha_mantenimiento ? escapar(date('d/m/Y', $fecha_mantenimiento)) : escapar(t('inicio.dashboard.actividad.sin_fecha')) ?>
                                            </span>

                                            <?php if ($coste_mantenimiento !== null && $coste_mantenimiento !== ''): ?>
                                                <span>
                                                    <?= escapar(number_format((float) $coste_mantenimiento, 2, ',', '.')) ?> €
                                                </span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </article>

                    <article class="inicio-actividad-bloque">
                        <div class="inicio-actividad-bloque__cabecera">
                            <h3><?= escapar(t('inicio.dashboard.actividad.publicaciones')) ?></h3>

                            <a href="<?= escapar($url_perfil) ?>">
                                <?= escapar(t('inicio.dashboard.actividad.ver_todo')) ?>
                            </a>
                        </div>

                        <?php if (empty($ultimas_publicaciones)): ?>
                            <p class="inicio-vacio">
                                <?= escapar(t('inicio.dashboard.actividad.publicaciones.vacio')) ?>
                            </p>
                        <?php else: ?>
                            <ul class="inicio-lista-actividad">
                                <?php foreach ($ultimas_publicaciones as $publicacion): ?>
                                    <li>
                                        <a href="<?= escapar(url('/comunidad/ver?id=' . (int) $publicacion['id'])) ?>" class="inicio-item-actividad">
                                            <p class="inicio-item-actividad__texto">
                                                <?= escapar((string) ($publicacion['contenido'] ?? '')) ?>
                                            </p>

                                            <span>
                                                <?= !empty($publicacion['fecha_creacion']) ? escapar(formatear_fecha($publicacion['fecha_creacion'])) : escapar(t('inicio.dashboard.actividad.sin_fecha')) ?>
                                            </span>

                                            <span>
                                                <?= escapar((string) ($publicacion['total_likes'] ?? 0)) ?>
                                                <?= escapar(t('inicio.dashboard.actividad.likes')) ?>
                                                ·
                                                <?= escapar((string) ($publicacion['total_comentarios'] ?? 0)) ?>
                                                <?= escapar(t('inicio.dashboard.actividad.comentarios')) ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </article>
                </div>
            </section>

        <?php endif; ?>
    </main>
</body>

</html>