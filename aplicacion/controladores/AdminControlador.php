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
}