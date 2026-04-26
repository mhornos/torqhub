<?php

class PerfilControlador extends ControladorBase {

    // muestra el perfil público de un usuario
    public function ver(): void {
        $nombre = trim($_GET['usuario'] ?? '');

        if ($nombre === '') {
            flash_set('error', 'Perfil no válido');
            $this->redirigir('/comunidad');
        }

        $usuario = RepositorioUsuarios::buscar_por_nombre($nombre);

        if (!$usuario) {
            flash_set('error', 'El perfil no existe');
            $this->redirigir('/comunidad');
        }

        $publicaciones = RepositorioPublicaciones::listar_por_usuario((int) $usuario['id']);

        $es_mi_perfil = (int) $_SESSION['usuario']['id'] === (int) $usuario['id'];

        $this->render('perfil/ver', [
            'usuario' => $usuario,
            'publicaciones' => $publicaciones,
            'es_mi_perfil' => $es_mi_perfil,
        ]);
    }
}