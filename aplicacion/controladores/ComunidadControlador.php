<?php

class ComunidadControlador extends ControladorBase {
// muestra el listado de publicaciones
    public function index(): void
    {
        $publicaciones = RepositorioPublicaciones::listar_todas();

        $this->render('comunidad/index', [
            'publicaciones' => $publicaciones,
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

        $titulo = trim($_POST['titulo'] ?? '');
        $contenido = trim($_POST['contenido'] ?? '');

        if ($titulo === '' || $contenido === '') {
            flash_set('error', 'Título y contenido son obligatorios');
            $this->redirigir('/comunidad/nueva');
        }

        if (mb_strlen($titulo) > 150) {
            flash_set('error', 'El título no puede superar los 150 caracteres');
            $this->redirigir('/comunidad/nueva');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        try {
            RepositorioPublicaciones::crear($usuario_id, $titulo, $contenido);
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

        $comentarios = RepositorioComentariosPublicaciones::listar_por_publicacion($id);

        $this->render('comunidad/ver', [
            'publicacion' => $publicacion,
            'comentarios' => $comentarios,
        ]);
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
}