<?php

class ComunidadControlador extends ControladorBase {
// muestra el listado de publicaciones con buscador, ordenación y paginación
    public function index(): void {
        $busqueda = trim($_GET['busqueda'] ?? '');
        $orden = $_GET['orden'] ?? 'recientes';
        $pagina_actual = (int) ($_GET['pagina'] ?? 1);

        $ordenes_permitidos = ['recientes', 'antiguas', 'likes', 'comentarios'];

        if (!in_array($orden, $ordenes_permitidos, true)) {
            $orden = 'recientes';
        }

        if ($pagina_actual < 1) {
            $pagina_actual = 1;
        }

        $limite = 5;
        $offset = ($pagina_actual - 1) * $limite;

        $total_publicaciones = RepositorioPublicaciones::contar_con_filtros($busqueda);
        $total_paginas = max(1, (int) ceil($total_publicaciones / $limite));

        if ($pagina_actual > $total_paginas) {
            $pagina_actual = $total_paginas;
            $offset = ($pagina_actual - 1) * $limite;
        }

        $publicaciones = RepositorioPublicaciones::listar_con_filtros(
            $busqueda,
            $orden,
            $limite,
            $offset
        );

        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest'
        ) {
            require __DIR__ . '/../vistas/comunidad/_resultado_publicaciones.php';
            return;
        }

        $this->render('comunidad/index', [
            'publicaciones' => $publicaciones,
            'busqueda' => $busqueda,
            'orden' => $orden,
            'pagina_actual' => $pagina_actual,
            'total_paginas' => $total_paginas,
            'total_publicaciones' => $total_publicaciones,
        ]);
    }

    
// muestra el formulario para crear una publicación
    public function nueva(): void
    {
        $this->render('comunidad/nueva');
    }


// procesa el alta de una nueva publicación
    public function nueva_post(): void
    {
        csrf_verificar();

        $contenido = trim($_POST['contenido'] ?? '');

        if ($contenido === '') {
            flash_set('error', 'El contenido es obligatorio');
            $this->redirigir('/comunidad/nueva');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        $imagen = null;

        if (!empty($_FILES['imagen']['name'])) {

            $carpeta = __DIR__ . '/../../public/uploads/publicaciones/';

            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));

            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $permitidas, true)) {
                flash_set('error', 'Formato de imagen no permitido');
                $this->redirigir('/comunidad/nueva');
            }

            $nombre_archivo = uniqid('post_', true) . '.' . $extension;

            move_uploaded_file(
                $_FILES['imagen']['tmp_name'],
                $carpeta . $nombre_archivo
            );

            $imagen = 'uploads/publicaciones/' . $nombre_archivo;
        }

        try {
            RepositorioPublicaciones::crear($usuario_id, $contenido, $imagen);
        } catch (PDOException $e) {
            flash_set('error', 'No se pudo guardar la publicación');
            $this->redirigir('/comunidad/nueva');
        }

        flash_set('ok', 'Publicación creada correctamente');
        $this->redirigir('/comunidad');
    }


// muestra el detalle de una publicación
    public function ver(): void {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            flash_set('error', 'Publicación no válida');
            $this->redirigir('/comunidad');
        }

        $publicacion = RepositorioPublicaciones::obtener_por_id($id);

        if (!$publicacion) {
            flash_set('error', 'La publicación no existe');
            $this->redirigir('/comunidad');
        }

        $comentarios = RepositorioComentariosPublicaciones::listar_principales_por_publicacion($id);

        $this->render('comunidad/ver', [
            'publicacion' => $publicacion,
            'comentarios' => $comentarios,
        ]);
    }

// guarda una respuesta a un comentario principal
    public function responder_comentario(): void {
        csrf_verificar();

        $publicacion_id = (int) ($_POST['publicacion_id'] ?? 0);
        $respuesta_a_id = (int) ($_POST['respuesta_a_id'] ?? 0);
        $contenido = trim($_POST['contenido'] ?? '');

        if ($publicacion_id <= 0 || $respuesta_a_id <= 0) {
            flash_set('error', 'Datos de respuesta no válidos');
            $this->redirigir('/comunidad');
        }

        $publicacion = RepositorioPublicaciones::obtener_por_id($publicacion_id);

        if (!$publicacion) {
            flash_set('error', 'La publicación no existe');
            $this->redirigir('/comunidad');
        }

        $comentario_padre = RepositorioComentariosPublicaciones::obtener_comentario_principal(
            $respuesta_a_id,
            $publicacion_id
        );

        if (!$comentario_padre) {
            flash_set('error', 'El comentario al que intentas responder no es válido');
            $this->redirigir('/comunidad/ver?id=' . $publicacion_id);
        }

        if ($contenido === '') {
            flash_set('error', 'La respuesta no puede estar vacía');
            $this->redirigir('/comunidad/ver?id=' . $publicacion_id);
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        try {
            RepositorioComentariosPublicaciones::crear(
                $publicacion_id,
                $usuario_id,
                $contenido,
                $respuesta_a_id
            );
        } catch (PDOException $e) {
            flash_set('error', 'No se pudo guardar la respuesta');
            $this->redirigir('/comunidad/ver?id=' . $publicacion_id);
        }

        flash_set('ok', 'Respuesta publicada correctamente');
        $this->redirigir('/comunidad/ver?id=' . $publicacion_id);
    }

// guarda un comentario en una publicación
    public function comentar(): void{
        csrf_verificar();

        $publicacion_id = (int) ($_POST['publicacion_id'] ?? 0);
        $contenido = trim($_POST['contenido'] ?? '');

        if ($publicacion_id <= 0) {
            flash_set('error', 'Publicación no válida');
            $this->redirigir('/comunidad');
        }

        $publicacion = RepositorioPublicaciones::obtener_por_id($publicacion_id);

        if (!$publicacion) {
            flash_set('error', 'La publicación no existe');
            $this->redirigir('/comunidad');
        }

        if ($contenido === '') {
            flash_set('error', 'El comentario no puede estar vacío');
            $this->redirigir('/comunidad/ver?id=' . $publicacion_id);
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        try {
            RepositorioComentariosPublicaciones::crear($publicacion_id, $usuario_id, $contenido);
        } catch (PDOException $e) {
            flash_set('error', 'No se pudo guardar el comentario');
            $this->redirigir('/comunidad/ver?id=' . $publicacion_id);
        }

        flash_set('ok', 'Comentario publicado correctamente');
        $this->redirigir('/comunidad/ver?id=' . $publicacion_id);
    }


// procesa el toggle de like de una publicación
    public function toggle_like(): void {
        csrf_verificar();

        $publicacion_id = (int) ($_POST['publicacion_id'] ?? 0);
        $usuario_id = (int) $_SESSION['usuario']['id'];

        if ($publicacion_id <= 0) {
            flash_set('error', 'Publicación no válida');
            $this->redirigir('/comunidad');
        }

        $ya_dio_like = RepositorioLikesPublicaciones::usuario_ya_dio_like(
            $publicacion_id,
            $usuario_id
        );

        try {
            if ($ya_dio_like) {
                RepositorioLikesPublicaciones::quitar_like($publicacion_id, $usuario_id);
                // flash_set('ok', 'Like eliminado');
            } else {
                RepositorioLikesPublicaciones::dar_like($publicacion_id, $usuario_id);
                // flash_set('ok', 'Like añadido');
            }
        } catch (PDOException $e) {
            flash_set('error', 'No se pudo actualizar el like');
        }

        $this->redirigir('/comunidad/ver?id=' . $publicacion_id);
    }

    
// muestra el formulario para editar una publicación propia
    public function editar(): void {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            flash_set('error', 'Publicación no válida');
            $this->redirigir('/comunidad');
        }

        $publicacion = RepositorioPublicaciones::obtener_por_id($id);

        if (!$publicacion) {
            flash_set('error', 'La publicación no existe');
            $this->redirigir('/comunidad');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        if ((int) $publicacion['usuario_id'] !== $usuario_id) {
            flash_set('error', 'No tienes permiso para editar esta publicación');
            $this->redirigir('/comunidad');
        }

        $this->render('comunidad/editar', [
            'publicacion' => $publicacion,
        ]);
    }


// procesa la edición de una publicación propia
    public function editar_post(): void {
        csrf_verificar();

        $id = (int) ($_POST['id'] ?? 0);
        $contenido = trim($_POST['contenido'] ?? '');

        if ($id <= 0) {
            flash_set('error', 'Publicación no válida');
            $this->redirigir('/comunidad');
        }

        $publicacion = RepositorioPublicaciones::obtener_por_id($id);

        if (!$publicacion) {
            flash_set('error', 'La publicación no existe');
            $this->redirigir('/comunidad');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        if ((int) $publicacion['usuario_id'] !== $usuario_id) {
            flash_set('error', 'No tienes permiso para editar esta publicación');
            $this->redirigir('/comunidad');
        }

        if ($contenido === '') {
            flash_set('error', 'El contenido es obligatorio');
            $this->redirigir('/comunidad/editar?id=' . $id);
        }

        $imagen = $publicacion['imagen'];

        if (!empty($_FILES['imagen']['name'])) {
            $carpeta = __DIR__ . '/../../public/uploads/publicaciones/';

            if (!is_dir($carpeta)) {
                mkdir($carpeta, 0777, true);
            }

            $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($extension, $permitidas, true)) {
                flash_set('error', 'Formato de imagen no permitido');
                $this->redirigir('/comunidad/editar?id=' . $id);
            }

            $nombre_archivo = uniqid('post_', true) . '.' . $extension;
            $ruta_destino = $carpeta . $nombre_archivo;

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
                flash_set('error', 'No se pudo subir la imagen');
                $this->redirigir('/comunidad/editar?id=' . $id);
            }

            if (!empty($publicacion['imagen'])) {
                $ruta_imagen_anterior = __DIR__ . '/../../public/' . $publicacion['imagen'];

                if (is_file($ruta_imagen_anterior)) {
                    unlink($ruta_imagen_anterior);
                }
            }

            $imagen = 'uploads/publicaciones/' . $nombre_archivo;
        }

        try {
            RepositorioPublicaciones::actualizar($id, $contenido, $imagen);
        } catch (PDOException $e) {
            flash_set('error', 'No se pudo actualizar la publicación');
            $this->redirigir('/comunidad/editar?id=' . $id);
        }

        flash_set('ok', 'Publicación actualizada correctamente');
        $this->redirigir('/comunidad/ver?id=' . $id);
    }


// elimina una publicación propia
    public function eliminar(): void {
        csrf_verificar();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            flash_set('error', 'Publicación no válida');
            $this->redirigir('/comunidad');
        }

        $publicacion = RepositorioPublicaciones::obtener_por_id($id);

        if (!$publicacion) {
            flash_set('error', 'La publicación no existe');
            $this->redirigir('/comunidad');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        if ((int) $publicacion['usuario_id'] !== $usuario_id) {
            flash_set('error', 'No tienes permiso para eliminar esta publicación');
            $this->redirigir('/comunidad');
        }

        try {
            RepositorioPublicaciones::eliminar($id);
        } catch (PDOException $e) {
            flash_set('error', 'No se pudo eliminar la publicación');
            $this->redirigir('/comunidad/ver?id=' . $id);
        }

        if (!empty($publicacion['imagen'])) {
            $ruta_imagen = __DIR__ . '/../../public/' . $publicacion['imagen'];

            if (is_file($ruta_imagen)) {
                unlink($ruta_imagen);
            }
        }

        flash_set('ok', 'Publicación eliminada correctamente');
        $this->redirigir('/comunidad');
    }


// muestra el formulario para editar un comentario propio
    public function editar_comentario(): void {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            flash_set('error', 'Comentario no válido');
            $this->redirigir('/comunidad');
        }

        $comentario = RepositorioComentariosPublicaciones::obtener_por_id($id);

        if (!$comentario) {
            flash_set('error', 'El comentario no existe');
            $this->redirigir('/comunidad');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        if ((int) $comentario['usuario_id'] !== $usuario_id) {
            flash_set('error', 'No tienes permiso para editar este comentario');
            $this->redirigir('/comunidad/ver?id=' . $comentario['publicacion_id']);
        }

        $this->render('comunidad/editar_comentario', [
            'comentario' => $comentario,
        ]);
    }

// procesa la edición de un comentario propio
    public function editar_comentario_post(): void {
        csrf_verificar();

        $id = (int) ($_POST['id'] ?? 0);
        $contenido = trim($_POST['contenido'] ?? '');

        if ($id <= 0) {
            flash_set('error', 'Comentario no válido');
            $this->redirigir('/comunidad');
        }

        $comentario = RepositorioComentariosPublicaciones::obtener_por_id($id);

        if (!$comentario) {
            flash_set('error', 'El comentario no existe');
            $this->redirigir('/comunidad');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        if ((int) $comentario['usuario_id'] !== $usuario_id) {
            flash_set('error', 'No tienes permiso para editar este comentario');
            $this->redirigir('/comunidad/ver?id=' . $comentario['publicacion_id']);
        }

        if ($contenido === '') {
            flash_set('error', 'El contenido del comentario es obligatorio');
            $this->redirigir('/comunidad/editar-comentario?id=' . $id);
        }

        try {
            RepositorioComentariosPublicaciones::actualizar($id, $contenido);
        } catch (PDOException $e) {
            flash_set('error', 'No se pudo actualizar el comentario');
            $this->redirigir('/comunidad/editar-comentario?id=' . $id);
        }

        flash_set('ok', 'Comentario actualizado correctamente');
        $this->redirigir('/comunidad/ver?id=' . $comentario['publicacion_id']);
    }

// elimina un comentario propio
    public function eliminar_comentario(): void {
        csrf_verificar();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            flash_set('error', 'Comentario no válido');
            $this->redirigir('/comunidad');
        }

        $comentario = RepositorioComentariosPublicaciones::obtener_por_id($id);

        if (!$comentario) {
            flash_set('error', 'El comentario no existe');
            $this->redirigir('/comunidad');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        if ((int) $comentario['usuario_id'] !== $usuario_id) {
            flash_set('error', 'No tienes permiso para eliminar este comentario');
            $this->redirigir('/comunidad/ver?id=' . $comentario['publicacion_id']);
        }

        try {
            RepositorioComentariosPublicaciones::eliminar($id);
        } catch (PDOException $e) {
            flash_set('error', 'No se pudo eliminar el comentario');
            $this->redirigir('/comunidad/ver?id=' . $comentario['publicacion_id']);
        }

        flash_set('ok', 'Comentario eliminado correctamente');
        $this->redirigir('/comunidad/ver?id=' . $comentario['publicacion_id']);
    }
    
        // devuelve por ajax las respuestas de un comentario principal
    public function respuestas_comentario(): void
    {
        $publicacion_id = (int) ($_GET['publicacion_id'] ?? 0);
        $comentario_id = (int) ($_GET['comentario_id'] ?? 0);

        header('Content-Type: application/json; charset=utf-8');

        if ($publicacion_id <= 0 || $comentario_id <= 0) {
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'mensaje' => 'Datos no válidos',
            ]);
            exit;
        }

        $publicacion = RepositorioPublicaciones::obtener_por_id($publicacion_id);

        if (!$publicacion) {
            http_response_code(404);
            echo json_encode([
                'ok' => false,
                'mensaje' => 'La publicación no existe',
            ]);
            exit;
        }

        $comentario_padre = RepositorioComentariosPublicaciones::obtener_comentario_principal(
            $comentario_id,
            $publicacion_id
        );

        if (!$comentario_padre) {
            http_response_code(404);
            echo json_encode([
                'ok' => false,
                'mensaje' => 'El comentario no es válido',
            ]);
            exit;
        }

        $respuestas = RepositorioComentariosPublicaciones::listar_respuestas_de_comentario($comentario_id);

        $respuestas_formateadas = array_map(function (array $respuesta): array {
            return [
                'id' => (int) $respuesta['id'],
                'publicacion_id' => (int) $respuesta['publicacion_id'],
                'usuario_id' => (int) $respuesta['usuario_id'],
                'autor_nombre' => $respuesta['autor_nombre'],
                'contenido' => $respuesta['contenido'],
                'fecha_creacion' => formatear_fecha($respuesta['fecha_creacion']),
            ];
        }, $respuestas);

        echo json_encode([
            'ok' => true,
            'respuestas' => $respuestas_formateadas,
        ]);

        exit;
    }


// procesa likes por ajax en una publicación
    public function toggle_like_ajax(): void {
        csrf_verificar();

        header('Content-Type: application/json; charset=utf-8');

        $publicacion_id = (int) ($_POST['publicacion_id'] ?? 0);
        $usuario_id = (int) ($_SESSION['usuario']['id'] ?? 0);

        if ($publicacion_id <= 0 || $usuario_id <= 0) {
            http_response_code(400);
            echo json_encode([
                'ok' => false,
                'mensaje' => 'Datos no válidos',
            ]);
            exit;
        }

        $publicacion = RepositorioPublicaciones::obtener_por_id($publicacion_id);

        if (!$publicacion) {
            http_response_code(404);
            echo json_encode([
                'ok' => false,
                'mensaje' => 'La publicación no existe',
            ]);
            exit;
        }

        $ya_dio_like = RepositorioLikesPublicaciones::usuario_ya_dio_like(
            $publicacion_id,
            $usuario_id
        );

        try {
            if ($ya_dio_like) {
                RepositorioLikesPublicaciones::quitar_like($publicacion_id, $usuario_id);
                $accion = 'quitado';
                $texto_boton = 'Dar like';
            } else {
                RepositorioLikesPublicaciones::dar_like($publicacion_id, $usuario_id);
                $accion = 'añadido';
                $texto_boton = 'Quitar like';
            }

            $total_likes = RepositorioLikesPublicaciones::contar_likes($publicacion_id);

            echo json_encode([
                'ok' => true,
                'accion' => $accion,
                'texto_boton' => $texto_boton,
                'total_likes' => $total_likes,
            ]);
            exit;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'mensaje' => 'No se pudo actualizar el like',
            ]);
            exit;
        }
    }
}