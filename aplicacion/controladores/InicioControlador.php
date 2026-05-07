<?php

class InicioControlador extends ControladorBase
{
    public function index(): void
    {
        $usuario = $_SESSION['usuario'] ?? null;
        $usuario_autenticado = is_array($usuario);
        $usuario_id = $usuario_autenticado ? (int) ($usuario['id'] ?? 0) : 0;

        $estadisticas_dashboard = [
            'total_vehiculos' => 0,
            'total_mantenimientos' => 0,
            'total_publicaciones' => 0,
            'total_likes_recibidos' => 0,
        ];

        $actividad_reciente = [
            'ultimos_vehiculos' => [],
            'ultimos_mantenimientos' => [],
            'ultimas_publicaciones' => [],
        ];

        if ($usuario_autenticado && $usuario_id > 0) {
            $estadisticas_dashboard = [
                'total_vehiculos' => RepositorioVehiculos::contar_por_usuario($usuario_id),
                'total_mantenimientos' => RepositorioMantenimientos::contar_por_usuario($usuario_id),
                'total_publicaciones' => RepositorioPublicaciones::contar_por_usuario($usuario_id),
                'total_likes_recibidos' => RepositorioPublicaciones::contar_likes_recibidos_por_usuario($usuario_id),
            ];

            $actividad_reciente = [
                'ultimos_vehiculos' => RepositorioVehiculos::listar_ultimos_por_usuario($usuario_id, 3),
                'ultimos_mantenimientos' => RepositorioMantenimientos::listar_ultimos_por_usuario($usuario_id, 3),
                'ultimas_publicaciones' => RepositorioPublicaciones::listar_ultimas_por_usuario($usuario_id, 3),
            ];
        }

        $this->render('inicio', [
            'usuario_autenticado' => $usuario_autenticado,
            'usuario_nombre' => $usuario_autenticado ? ($usuario['nombre'] ?? '') : '',
            'estadisticas_dashboard' => $estadisticas_dashboard,
            'actividad_reciente' => $actividad_reciente,
        ]);
    }
}
