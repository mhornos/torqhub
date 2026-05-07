<?php

class PerfilControlador extends ControladorBase {

// muestra el perfil público de un usuario
    public function ver(): void {
        $nombre = trim($_GET['usuario'] ?? '');

        if ($nombre === '') {
            flash_set('error', t('perfil.error.no_valido'));
            $this->redirigir('/comunidad');
        }

        $usuario = RepositorioUsuarios::buscar_por_nombre($nombre);

        if (!$usuario) {
            flash_set('error', t('perfil.error.no_existe'));
            $this->redirigir('/comunidad');
        }

        $publicaciones = RepositorioPublicaciones::listar_por_usuario((int) $usuario['id']);
        $vehiculos = RepositorioVehiculos::listar_por_usuario_publico((int) $usuario['id']);

        $es_mi_perfil = (int) $_SESSION['usuario']['id'] === (int) $usuario['id'];

        $this->render('perfil/ver', [
            'usuario' => $usuario,
            'publicaciones' => $publicaciones,
            'vehiculos' => $vehiculos,
            'es_mi_perfil' => $es_mi_perfil,
            'scripts' => [
                '/public/js/perfil/perfil.js',
            ],
        ]);
    }

// actualiza la foto de perfil del usuario logueado
    public function actualizar_foto(): void {
        csrf_verificar();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $nombre_usuario = $_SESSION['usuario']['nombre'];

        $usuario = RepositorioUsuarios::buscar_por_nombre($nombre_usuario);

        if (!$usuario) {
            flash_set('error', t('perfil.error.usuario_no_encontrado'));
            $this->redirigir('/comunidad');
        }

        $archivo_imagen = $_FILES['foto_perfil'] ?? null;

        if (!$archivo_imagen || ($archivo_imagen['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            flash_set('error', t('perfil.error.imagen_obligatoria'));
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

        flash_set('ok', t('perfil.ok.foto_actualizada'));
        $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
    }


// guarda una foto de perfil validada en public/uploads/perfiles
    private function guardar_foto_perfil(array $archivo): string {
        if (($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException(t('perfil.error.subir_foto'));
        }

        if (!isset($archivo['tmp_name']) || !is_uploaded_file($archivo['tmp_name'])) {
            throw new RuntimeException(t('perfil.error.archivo_no_valido'));
        }

        $tamanyo_maximo = 3 * 1024 * 1024;

        if (($archivo['size'] ?? 0) > $tamanyo_maximo) {
            throw new RuntimeException(t('perfil.error.imagen_tamanyo'));
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($archivo['tmp_name']);

        $extensiones_permitidas = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (!isset($extensiones_permitidas[$mime_type])) {
            throw new RuntimeException(t('perfil.error.imagen_formato'));
        }

        $directorio = dirname(__DIR__, 2) . '/public/uploads/perfiles';

        if (!is_dir($directorio) && !mkdir($directorio, 0775, true)) {
            throw new RuntimeException(t('perfil.error.crear_directorio'));
        }

        $nombre_archivo = 'perfil_' . bin2hex(random_bytes(16)) . '.' . $extensiones_permitidas[$mime_type];
        $ruta_destino = $directorio . '/' . $nombre_archivo;

        if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            throw new RuntimeException(t('perfil.error.guardar_foto'));
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
            flash_set('error', t('perfil.error.campos_obligatorios'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        if (!$this->nombre_usuario_valido($nombre)) {
            flash_set('error', t('perfil.error.nombre_reglas'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash_set('error', t('perfil.error.email_no_valido'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        if (RepositorioUsuarios::existe_nombre_en_otro_usuario($nombre, $usuario_id)) {
            flash_set('error', t('perfil.error.nombre_en_uso'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        if (RepositorioUsuarios::existe_email_en_otro_usuario($email, $usuario_id)) {
            flash_set('error', t('perfil.error.email_en_uso'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_actual));
        }

        RepositorioUsuarios::actualizar_datos_perfil($usuario_id, $nombre, $email);

        $_SESSION['usuario']['nombre'] = $nombre;
        $_SESSION['usuario']['email'] = $email;

        flash_set('ok', t('perfil.ok.actualizado'));
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

// cambia la contraseña del usuario logueado
    public function cambiar_password(): void {
        csrf_verificar();

        $usuario_id = (int) $_SESSION['usuario']['id'];
        $nombre_usuario = $_SESSION['usuario']['nombre'];

        $password_actual = $_POST['password_actual'] ?? '';
        $password_nueva = $_POST['password_nueva'] ?? '';
        $password_nueva_repetida = $_POST['password_nueva_repetida'] ?? '';

        if ($password_actual === '' || $password_nueva === '' || $password_nueva_repetida === '') {
            flash_set('error', t('perfil.error.password_obligatoria'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
        }

        $usuario = RepositorioUsuarios::buscar_por_id_con_password($usuario_id);

        if (!$usuario) {
            flash_set('error', t('perfil.error.usuario_no_encontrado'));
            $this->redirigir('/comunidad');
        }

        if (!password_verify($password_actual, $usuario['password_hash'])) {
            flash_set('error', t('perfil.error.password_actual'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
        }

        if ($password_nueva !== $password_nueva_repetida) {
            flash_set('error', t('perfil.error.password_no_coincide'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
        }

        if (!$this->password_segura($password_nueva)) {
            flash_set('error', t('perfil.error.password_requisitos'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
        }

        if (password_verify($password_nueva, $usuario['password_hash'])) {
            flash_set('error', t('perfil.error.password_igual'));
            $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
        }

        $password_hash = password_hash($password_nueva, PASSWORD_DEFAULT);

        RepositorioUsuarios::actualizar_password($usuario_id, $password_hash);

        flash_set('ok', t('perfil.ok.password_actualizada'));
        $this->redirigir('/perfil?usuario=' . urlencode($nombre_usuario));
    }

// valida requisitos mínimos de contraseña
    private function password_segura(string $password): bool {
        if (strlen($password) < 8) {
            return false;
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return true;
    }

// muestra el detalle publico de un vehiculo
    public function vehiculo_publico(): void {
        $vehiculo_id = (int) ($_GET['id'] ?? 0);

        if ($vehiculo_id <= 0) {
            flash_set('error', t('perfil.vehiculo.error.no_valido'));
            $this->redirigir('/comunidad');
        }

        $vehiculo = RepositorioVehiculos::obtener_publico_por_id($vehiculo_id);

        if (!$vehiculo) {
            flash_set('error', t('perfil.vehiculo.error.no_existe'));
            $this->redirigir('/comunidad');
        }

        $this->render('perfil/vehiculo', [
            'vehiculo' => $vehiculo,
        ]);
    }
}