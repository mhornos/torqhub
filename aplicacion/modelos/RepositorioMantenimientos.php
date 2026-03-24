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

    //crea un nuevo mantenimiento asociado a un vehículo y devuelve su id
    public static function crear(int $vehiculo_id, string $fecha, string $tipo, ?string $descripcion, ?int $kilometros, ?float $coste): int {
        $pdo = ConexionBBDD::obtener();
        
        $sql = "INSERT INTO mantenimientos (vehiculo_id, fecha, tipo, descripcion, kilometros, coste)
                VALUES (:vehiculo_id, :fecha, :tipo, :descripcion, :kilometros, :coste)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vehiculo_id' => $vehiculo_id,
            'fecha' => $fecha,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'kilometros' => $kilometros,
            'coste' => $coste,
        ]);

        return (int) $pdo->lastInsertId();
    }

    //devuelve un mantenimiento si pertenece a un vehículo del usuario
    public static function buscar_por_id_y_usuario(int $mantenimiento_id, int $usuario_id): ?array
    {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT m.id, m.vehiculo_id, m.fecha, m.tipo, m.descripcion, m.kilometros, m.coste, m.fecha_creacion
                FROM mantenimientos m
                INNER JOIN vehiculos v ON v.id = m.vehiculo_id
                WHERE m.id = :id
                  AND v.usuario_id = :usuario_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $mantenimiento_id,
            'usuario_id' => $usuario_id,
        ]);

        $mantenimiento = $stmt->fetch();

        return $mantenimiento ?: null;
    }

    //actualiza un mantenimiento existente
    public static function actualizar(int $mantenimiento_id, int $vehiculo_id, string $fecha, string $tipo, ?string $descripcion, ?int $kilometros, ?float $coste): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE mantenimientos
                SET fecha = :fecha,
                    tipo = :tipo,
                    descripcion = :descripcion,
                    kilometros = :kilometros,
                    coste = :coste
                WHERE id = :id
                  AND vehiculo_id = :vehiculo_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $mantenimiento_id,
            'vehiculo_id' => $vehiculo_id,
            'fecha' => $fecha,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'kilometros' => $kilometros,
            'coste' => $coste,
        ]);
    }

    //elimina un mantenimiento si pertenece al vehículo indicado
    public static function eliminar(int $mantenimiento_id, int $vehiculo_id): bool
    {
        $pdo = ConexionBBDD::obtener();

        $sql = "DELETE FROM mantenimientos
                WHERE id = :id
                  AND vehiculo_id = :vehiculo_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $mantenimiento_id,
            'vehiculo_id' => $vehiculo_id,
        ]);
    }
}