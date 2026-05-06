<?php

class AdminControlador extends ControladorBase {

// muestra la página principal del panel de administración
    public function index(): void {
        $this->render('admin/index');
    }

// muestra la gestión de usuarios
    public function usuarios(): void {
        try {
            $usuarios = RepositorioAdminUsuarios::listar();
        } catch (PDOException $e) {
            error_log('Error listando usuarios en admin: ' . $e->getMessage());

            flash_set('error', t('admin.usuarios.error.listar'));
            $usuarios = [];
        }

        $this->render('admin/usuarios', [
            'usuarios' => $usuarios,
        ]);
    }

// cambia el rol de un usuario desde el panel admin
    public function usuarios_cambiar_rol(): void {
        csrf_verificar();

        $usuario_id = (int) ($_POST['usuario_id'] ?? 0);
        $rol = trim($_POST['rol'] ?? '');
        $admin_id = (int) ($_SESSION['usuario']['id'] ?? 0);

        if ($usuario_id <= 0) {
            flash_set('error', t('admin.usuarios.error.datos_invalidos'));
            $this->redirigir('/admin/usuarios');
        }

        if (!in_array($rol, ['usuario', 'admin'], true)) {
            flash_set('error', t('admin.usuarios.error.rol_invalido'));
            $this->redirigir('/admin/usuarios');
        }

        if ($usuario_id === $admin_id && $rol !== 'admin') {
            flash_set('error', t('admin.usuarios.error.autorol'));
            $this->redirigir('/admin/usuarios');
        }

        try {
            RepositorioAdminUsuarios::actualizar_rol($usuario_id, $rol);

            flash_set('ok', t('admin.usuarios.ok.rol_actualizado'));
        } catch (PDOException $e) {
            error_log('Error cambiando rol de usuario en admin: ' . $e->getMessage());

            flash_set('error', t('admin.usuarios.error.actualizar_rol'));
        }

        $this->redirigir('/admin/usuarios');
    }

// activa o desactiva un usuario desde el panel admin
    public function usuarios_cambiar_estado(): void {
        csrf_verificar();

        $usuario_id = (int) ($_POST['usuario_id'] ?? 0);
        $activo = (int) ($_POST['activo'] ?? -1);
        $admin_id = (int) ($_SESSION['usuario']['id'] ?? 0);

        if ($usuario_id <= 0 || !in_array($activo, [0, 1], true)) {
            flash_set('error', t('admin.usuarios.error.datos_invalidos'));
            $this->redirigir('/admin/usuarios');
        }

        if ($usuario_id === $admin_id && $activo === 0) {
            flash_set('error', t('admin.usuarios.error.autodesactivar'));
            $this->redirigir('/admin/usuarios');
        }

        try {
            RepositorioAdminUsuarios::actualizar_estado($usuario_id, $activo);

            $mensaje = $activo === 1
                ? t('admin.usuarios.ok.usuario_activado')
                : t('admin.usuarios.ok.usuario_desactivado');

            flash_set('ok', $mensaje);
        } catch (PDOException $e) {
            error_log('Error cambiando estado de usuario en admin: ' . $e->getMessage());

            flash_set('error', t('admin.usuarios.error.actualizar_estado'));
        }

        $this->redirigir('/admin/usuarios');
    }

// muestra la gestión de publicaciones
    public function publicaciones(): void {
        try {
            $publicaciones = RepositorioAdminPublicaciones::listar();
        } catch (PDOException $e) {
            error_log('Error listando publicaciones en admin: ' . $e->getMessage());

            flash_set('error', t('admin.publicaciones.error.listar'));
            $publicaciones = [];
        }

        $this->render('admin/publicaciones', [
            'publicaciones' => $publicaciones,
        ]);
    }

// elimina una publicación desde el panel de administración
    public function publicaciones_eliminar(): void {
        csrf_verificar();

        $publicacion_id = (int) ($_POST['publicacion_id'] ?? 0);
        $confirmar = (string) ($_POST['confirmar'] ?? '');

        if ($publicacion_id <= 0) {
            flash_set('error', t('admin.publicaciones.error.datos_invalidos'));
            $this->redirigir('/admin/publicaciones');
        }

        if ($confirmar !== '1') {
            flash_set('error', t('admin.publicaciones.error.confirmacion'));
            $this->redirigir('/admin/publicaciones');
        }

        try {
            $publicacion = RepositorioAdminPublicaciones::obtener_por_id($publicacion_id);

            if (!$publicacion) {
                flash_set('error', t('admin.publicaciones.error.no_existe'));
                $this->redirigir('/admin/publicaciones');
            }

            $eliminada = RepositorioAdminPublicaciones::eliminar($publicacion_id);

            if (!$eliminada) {
                flash_set('error', t('admin.publicaciones.error.eliminar'));
                $this->redirigir('/admin/publicaciones');
            }

            $this->eliminar_imagen_publicacion_admin($publicacion['imagen'] ?? null);

            flash_set('ok', t('admin.publicaciones.ok.eliminada'));
        } catch (PDOException $e) {
            error_log('Error eliminando publicación desde admin: ' . $e->getMessage());

            flash_set('error', t('admin.publicaciones.error.eliminar'));
        }

        $this->redirigir('/admin/publicaciones');
    }

// elimina del disco la imagen asociada a una publicación
    private function eliminar_imagen_publicacion_admin(?string $imagen): void {
        $ruta_relativa = trim((string) $imagen);

        if ($ruta_relativa === '') {
            return;
        }

        $ruta_relativa = str_replace('\\', '/', $ruta_relativa);

        if (!str_starts_with($ruta_relativa, 'uploads/publicaciones/')) {
            return;
        }

        $directorio_base = realpath(__DIR__ . '/../../public/uploads/publicaciones');
        $ruta_imagen = realpath(__DIR__ . '/../../public/' . $ruta_relativa);

        if (!$directorio_base || !$ruta_imagen) {
            return;
        }

        if (!str_starts_with($ruta_imagen, $directorio_base . DIRECTORY_SEPARATOR)) {
            return;
        }

        if (is_file($ruta_imagen)) {
            unlink($ruta_imagen);
        }
    }

// muestra la base de conocimiento del sistema experto de diagnóstico
    public function ia(): void {
        try {
            $causas_ia = RepositorioDiagnosticoIA::listar_causas_con_keywords();
        } catch (Throwable $e) {
            error_log('Error listando causas IA en admin: ' . $e->getMessage());

            flash_set('error', t('admin.ia.error.listar'));
            $causas_ia = [];
        }

        $this->render('admin/ia', [
            'causas_ia' => $causas_ia,
        ]);
    }

// activa o desactiva una causa del sistema experto ia
    public function ia_cambiar_estado(): void {
        csrf_verificar();

        $causa_id = (int) ($_POST['causa_id'] ?? 0);
        $activo = (int) ($_POST['activo'] ?? -1);

        if ($causa_id <= 0 || !in_array($activo, [0, 1], true)) {
            flash_set('error', t('admin.ia.error.datos_invalidos'));
            $this->redirigir('/admin/ia');
        }

        try {
            $causa = RepositorioDiagnosticoIA::obtener_causa_por_id($causa_id);

            if (!$causa) {
                flash_set('error', t('admin.ia.error.no_existe'));
                $this->redirigir('/admin/ia');
            }

            RepositorioDiagnosticoIA::actualizar_estado_causa($causa_id, $activo);

            $mensaje = $activo === 1
                ? t('admin.ia.ok.causa_activada')
                : t('admin.ia.ok.causa_desactivada');

            flash_set('ok', $mensaje);
        } catch (PDOException $e) {
            error_log('Error cambiando estado de causa IA en admin: ' . $e->getMessage());

            flash_set('error', t('admin.ia.error.actualizar_estado'));
        }

        $this->redirigir('/admin/ia');
    }

}