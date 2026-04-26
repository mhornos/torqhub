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

// actualiza la foto de perfil del usuario logueado
    public function actualizar_foto(): void {
        csrf_verificar();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $nombre_usuario = $_SESSION['usuario']['nombre'];

        $usuario = RepositorioUsuarios::buscar_por_nombre($nombre_usuario);

        if (!$usuario) {
            flash_set('error', 'Usuario no encontrado');
            $this->redirigir('/comunidad');
        }

        $archivo_imagen = $_FILES['foto_perfil'] ?? null;

        if (!$archivo_imagen || ($archivo_imagen['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            flash_set('error', 'Debes seleccionar una imagen');
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
        }

        try {
            $nueva_foto = $this->guardar_foto_perfil($archivo_imagen);
        } catch (RuntimeException $e) {
            flash_set('error', $e->getMessage());
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
        }

        RepositorioUsuarios::actualizar_foto_perfil($usuario_id, $nueva_foto);

        if (!empty($usuario['foto_perfil'])) {
            $this->eliminar_foto_perfil($usuario['foto_perfil']);
        }

        flash_set('ok', 'Foto de perfil actualizada');
        $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
    }


// guarda una foto de perfil validada en public/uploads/perfiles
    private function guardar_foto_perfil(array $archivo): string {
        if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('No se pudo subir la foto de perfil');
        }

        if (!isset($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            throw new RuntimeException('El archivo subido no es válido');
        }

        $tamanyo_maximo = 3 * 1024 * 1024;

        if (($archivo['size'] ?? 0) > $tamanyo_maximo) {
            throw new RuntimeException('La imagen no puede superar los 3 MB');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($archivo['tmp_name']);

        $extensiones_permitidas = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($extensiones_permitidas[$mime_type])) {
            throw new RuntimeException('Solo se permiten imágenes jpg, png o webp');
        }

        $directorio = dirname(__DIR__, 2) . '/public/uploads/perfiles';

        if (!is_dir($directorio) && !mkdir($directorio, 0775, true)) {
            throw new RuntimeException('No se pudo crear el directorio de fotos de perfil');
        }

        $nombre_archivo = 'perfil_' . bin2hex(random_bytes(16)) . '.' . $extensiones_permitidas[$mime_type];
        $ruta_destino = $directorio . '/' . $nombre_archivo;

        if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            throw new RuntimeException('No se pudo guardar la foto de perfil');
        }

        return $nombre_archivo;
    }

// elimina la foto anterior del usuario
    private function eliminar_foto_perfil(?string $nombre_archivo): void {
        if (empty($nombre_archivo)) {
            return;
        }

        $ruta = dirname(__DIR__, 2) . '/public/uploads/perfiles/' . $nombre_archivo;

        if (is_file($ruta)) {
            @unlink($ruta);
        }
    }

// actualiza el nombre de usuario y el correo del perfil
    public function actualizar(): void {
        csrf_verificar();

        $usuario_id = (int) $_SESSION['usuario']['id'];

        $nombre_actual = $_SESSION['usuario']['nombre'];

        $nombre = strtolower(trim($_POST['nombre'] ?? ''));
        $email = strtolower(trim($_POST['email'] ?? ''));

        if ($nombre === '' || $email === '') {
            flash_set('error', 'Todos los campos son obligatorios');
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        if (!$this->nombre_usuario_valido($nombre)) {
            flash_set('error', 'El nombre de usuario no cumple las reglas permitidas');
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', 'El correo electrónico no es válido');
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        if (RepositorioUsuarios::existe_nombre_en_otro_usuario($nombre, $usuario_id)) {
            flash_set('error', 'El nombre de usuario ya está en uso');
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        if (RepositorioUsuarios::existe_email_en_otro_usuario($email, $usuario_id)) {
            flash_set('error', 'El correo electrónico ya está en uso');
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        RepositorioUsuarios::actualizar_datos_perfil($usuario_id, $nombre, $email);

        $_SESSION['usuario']['nombre'] = $nombre;
        $_SESSION['usuario']['email'] = $email;

        flash_set('ok', 'Perfil actualizado correctamente');
        $this->redirigir('/perfil?usuario=' . urlencode($nombre));
    }

// valida las reglas del handle del usuario
    private function nombre_usuario_valido(string $nombre): bool {
        if (!preg_match('/^[a-z0-9._]+$/', $nombre)) {
            return false;
        }

        if (str_contains($nombre, '..')) {
            return false;
        }

        if (str_ends_with($nombre, '.')) {
            return false;
        }

        return true;
    }
}