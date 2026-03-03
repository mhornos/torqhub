<?php

class RepositorioVehiculos
{
    public static function listar_por_usuario(int $usuario_id): array
    {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, marca, modelo, any, vin, fecha_creacion
                FROM vehiculos
                WHERE usuario_id = :usuario_id
                ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['usuario_id' => $usuario_id]);

        return $stmt->fetchAll();
    }

    public static function crear(int $usuario_id, string $marca, string $modelo, ?int $any, ?string $vin): int {
        $pdo = ConexionBBDD::obtener();

        $sql = "INSERT INTO vehiculos (usuario_id, marca, modelo, any, vin)
                VALUES (:usuario_id, :marca, :modelo, :any, :vin)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'marca' => $marca,
            'modelo' => $modelo,
            'any' => $any,
            'vin' => $vin,
        ]);

        return (int) $pdo->lastInsertId();
    }
}