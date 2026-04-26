<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>perfil - torqhub</title>
    <link rel="stylesheet" href="<?= url('/public/css/estilos.css') ?>">
</head>
<body>

    <h1>Perfil de @<?= htmlspecialchars($usuario['nombre']) ?></h1>

    <?php if ($m = flash_get('ok')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <?php if ($m = flash_get('error')): ?>
        <p><?= htmlspecialchars($m) ?></p>
    <?php endif; ?>

    <section class="perfil-contenedor">
        <div class="perfil-cabecera">
            <div class="perfil-foto">
                <?php if (!empty($usuario['foto_perfil'])): ?>
                    <img 
                        src="<?= url('/public/uploads/perfiles/' . rawurlencode($usuario['foto_perfil'])) ?>" 
                        alt="Foto de perfil de <?= htmlspecialchars($usuario['nombre']) ?>"
                    >
                <?php else: ?>
                    <div class="perfil-foto-vacia">
                        Sin foto
                    </div>
                <?php endif; ?>
            </div>

            <div class="perfil-info">
                <h2>@<?= htmlspecialchars($usuario['nombre']) ?></h2>

                <?php if ($es_mi_perfil): ?>
                    <p><?= htmlspecialchars($usuario['email']) ?></p>
                    <p>Contraseña: ********</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($es_mi_perfil): ?>
            <div class="perfil-bloque">
                <h3>Cambiar foto de perfil</h3>

                <form action="<?= url('/perfil/foto') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_campo() ?>

                    <div>
                        <label for="foto_perfil">Nueva foto:</label>
                        <input 
                            type="file" 
                            name="foto_perfil" 
                            id="foto_perfil" 
                            accept="image/jpeg,image/png,image/webp"
                            required
                        >
                    </div>

                    <button type="submit">Actualizar foto</button>
                </form>
            </div>

            <div class="perfil-bloque">
                <h3>Editar datos del perfil</h3>

                <form action="<?= url('/perfil/actualizar') ?>" method="POST">
                    <?= csrf_campo() ?>

                    <div>
                        <label for="nombre">Nombre de usuario:</label>
                        <input 
                            type="text" 
                            name="nombre" 
                            id="nombre" 
                            value="<?= htmlspecialchars($usuario['nombre']) ?>"
                            required
                        >
                    </div>

                    <div>
                        <label for="email">Correo electrónico:</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="<?= htmlspecialchars($usuario['email']) ?>"
                            required
                        >
                    </div>

                    <button type="submit">Guardar cambios</button>
                </form>
            </div>

            <div class="perfil-bloque">
            <h3>Cambiar contraseña</h3>
                
            <form action="<?= url('/perfil/cambiar-password') ?>" method="POST">
                <?= csrf_campo() ?>
                
                <div>
                    <label for="password_actual">Contraseña actual:</label>
                    <input 
                        type="password" 
                        name="password_actual" 
                        id="password_actual"
                        required
                    >
                </div>
                
                <div>
                    <label for="password_nueva">Nueva contraseña:</label>
                    <input 
                        type="password" 
                        name="password_nueva" 
                        id="password_nueva"
                        required
                    >
                </div>
                
                <div>
                    <label for="password_nueva_repetida">Repetir nueva contraseña:</label>
                    <input 
                        type="password" 
                        name="password_nueva_repetida" 
                        id="password_nueva_repetida"
                        required
                    >
                </div>
                
                <button type="submit">Cambiar contraseña</button>
            </form>
        </div>
        <?php endif; ?>
    </section>

    <hr>

    <h2>Publicaciones</h2>

    <?php if (count($publicaciones) === 0): ?>
        <p>Este usuario todavía no tiene publicaciones.</p>
    <?php else: ?>
        <?php foreach ($publicaciones as $publicacion): ?>
            <article>
                <p>
                    <?= formatear_fecha($publicacion['fecha_creacion']) ?>
                </p>

                <?php if (!empty($publicacion['imagen'])): ?>
                    <img 
                        src="<?= url('/public/' . $publicacion['imagen']) ?>"
                        alt="Imagen publicación"
                        style="max-width:400px;display:block;margin-bottom:10px;"
                    >
                <?php endif; ?>

                <p><?= nl2br(htmlspecialchars($publicacion['contenido'])) ?></p>

                <p>
                    <?= (int) $publicacion['total_comentarios'] ?> comentarios ·
                    <?= (int) $publicacion['total_likes'] ?> likes
                </p>

                <p>
                    <a href="<?= url('/comunidad/ver?id=' . $publicacion['id']) ?>">Ver publicación</a>
                </p>

                <hr>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>