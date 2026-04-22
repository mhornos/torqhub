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
            flash_set('error', 'titulo y contenido son obligatorios');
            $this->redirigir('/comunidad/nueva');
        }

        if (mb_strlen($titulo) > 150) {
            flash_set('error', 'el titulo no puede superar los 150 caracteres');
            $this->redirigir('/comunidad/nueva');
        }

        $usuario_id = (int) $_SESSION['usuario']['id'];

        try {
            RepositorioPublicaciones::crear($usuario_id, $titulo, $contenido);
        } catch (PDOException $e) {
            flash_set('error', 'no se pudo guardar la publicacion');
            $this->redirigir('/comunidad/nueva');
        }

        flash_set('ok', 'publicacion creada correctamente');
        $this->redirigir('/comunidad');
    }

// muestra el detalle de una publicación
    public function ver(): void {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            flash_set('error', 'publicacion no valida');
            $this->redirigir('/comunidad');
        }

        $publicacion = RepositorioPublicaciones::obtener_por_id($id);

        if (!$publicacion) {
            flash_set('error', 'la publicacion no existe');
            $this->redirigir('/comunidad');
        }

        $comentarios = RepositorioComentariosPublicaciones::listar_por_publicacion($id);

        $this->render('comunidad/ver', [
            'publicacion' => $publicacion,
            'comentarios' => $comentarios,
        ]);
    }
}