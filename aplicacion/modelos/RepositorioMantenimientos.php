<?php

class RepositorioMantenimientos
{
    //devuelve el historial de mantenimientos de un vehículo ordenado del más reciente al más antiguo
    public static function listar_por_vehiculo(int $vehiculo_id): array
    {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, vehiculo_id, fecha, tipo, descripcion, kilometros, coste, fecha_creacion
                FROM mantenimientos
                WHERE vehiculo_id = :vehiculo_id
                ORDER BY fecha DESC, id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vehiculo_id' => $vehiculo_id,
        ]);

        return $stmt->fetchAll();
    }
}