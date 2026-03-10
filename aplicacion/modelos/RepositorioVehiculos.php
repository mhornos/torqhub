<?php

class RepositorioVehiculos
{
    //devuelve un array de vehículos asociados a un usuario
    public static function listar_por_usuario(int $usuario_id): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, marca, modelo, any, vin, fecha_creacion
                FROM vehiculos
                WHERE usuario_id = :usuario_id
                ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['usuario_id' => $usuario_id]);

        return $stmt->fetchAll();
    }

    //añade un nuevo vehículo a la base de datos y devuelve su id
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

    //busca un vehículo por su id y el id del usuario propietario, devuelve un array con los datos o null si no se encuentra
    public static function buscar_por_id_y_usuario(int $vehiculo_id, int $usuario_id): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, usuario_id, marca, modelo, any, vin, fecha_creacion
                FROM vehiculos
                WHERE id = :id AND usuario_id = :usuario_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $vehiculo_id,
            'usuario_id' => $usuario_id,
        ]);

        $vehiculo = $stmt->fetch();

        return $vehiculo ?: null;
    }

    //elimina un vehículo, devuelve true si se eliminó o false si no se encontró
    public static function eliminar(int $vehiculo_id, int $usuario_id): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "DELETE FROM vehiculos
                WHERE id = :id AND usuario_id = :usuario_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $vehiculo_id,
            'usuario_id' => $usuario_id,
        ]);

        return $stmt->rowCount() > 0;
    }

    //edita los datos de un vehículo, devuelve true si se actualizó o false si no se encontró
    public static function actualizar(int $vehiculo_id, int $usuario_id, string $marca, string $modelo, ?int $any, ?string $vin): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE vehiculos
                SET marca = :marca,
                    modelo = :modelo,
                    any = :any,
                    vin = :vin
                WHERE id = :id
                  AND usuario_id = :usuario_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'marca' => $marca,
            'modelo' => $modelo,
            'any' => $any,
            'vin' => $vin,
            'id' => $vehiculo_id,
            'usuario_id' => $usuario_id,
        ]);

        return $stmt->rowCount() > 0;
    }
}