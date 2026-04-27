<?php

class RepositorioVehiculos
{

    //devuelve un array de vehículos asociados a un usuario
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

    //añade un nuevo vehículo a la base de datos y devuelve su id
    public static function crear(int $usuario_id, string $marca, string $modelo, ?int $any, ?string $vin, ?string $carroceria, ?string $tipo_combustible, ?string $tipo_cambio, ?int $potencia_cv, ?int $cilindrada_cm3, ?string $imagen): int
    {
        $pdo = ConexionBBDD::obtener();

        $sql = "INSERT INTO vehiculos (
            usuario_id, marca, modelo, any, vin, carroceria,
            tipo_combustible, tipo_cambio, potencia_cv, cilindrada_cm3, imagen
        )   VALUES (:usuario_id, :marca, :modelo, :any, :vin, :carroceria, :tipo_combustible, :tipo_cambio, :potencia_cv, :cilindrada_cm3, :imagen)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'marca' => $marca,
            'modelo' => $modelo,
            'any' => $any,
            'vin' => $vin,
            'carroceria' => $carroceria,
            'tipo_combustible' => $tipo_combustible,
            'tipo_cambio' => $tipo_cambio,
            'potencia_cv' => $potencia_cv,
            'cilindrada_cm3' => $cilindrada_cm3,
            'imagen' => $imagen,
        ]);

        return (int) $pdo->lastInsertId();
    }

    //busca un vehículo por su id y el id del usuario propietario, devuelve un array con los datos o null si no se encuentra
    public static function buscar_por_id_y_usuario(int $vehiculo_id, int $usuario_id): ?array
    {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, usuario_id, marca, modelo, any, vin,
               carroceria, tipo_combustible, tipo_cambio,
               potencia_cv, cilindrada_cm3, imagen, fecha_creacion
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
    public static function eliminar(int $vehiculo_id, int $usuario_id): bool
    {
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
    public static function actualizar(int $vehiculo_id, int $usuario_id, string $marca, string $modelo, ?int $any, ?string $vin, ?string $carroceria, ?string $tipo_combustible, ?string $tipo_cambio, ?int $potencia_cv, ?int $cilindrada_cm3, ?string $imagen): bool
    {
        $pdo = ConexionBBDD::obtener();

        $sql_comprobar = "SELECT id
                            FROM vehiculos
                            WHERE id = :id
                            AND usuario_id = :usuario_id
                            LIMIT 1";

        $stmt_comprobar = $pdo->prepare($sql_comprobar);
        $stmt_comprobar->execute([
            'id' => $vehiculo_id,
            'usuario_id' => $usuario_id,
        ]);

        $existe = $stmt_comprobar->fetch();

        if (!$existe) {
            return false;
        }

        $sql = "UPDATE vehiculos
            SET marca = :marca,
                modelo = :modelo,
                any = :any,
                vin = :vin,
                carroceria = :carroceria,
                tipo_combustible = :tipo_combustible,
                tipo_cambio = :tipo_cambio,
                potencia_cv = :potencia_cv,
                cilindrada_cm3 = :cilindrada_cm3,
                imagen = :imagen
            WHERE id = :id
              AND usuario_id = :usuario_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $vehiculo_id,
            'usuario_id' => $usuario_id,
            'marca' => $marca,
            'modelo' => $modelo,
            'any' => $any,
            'vin' => $vin,
            'carroceria' => $carroceria,
            'tipo_combustible' => $tipo_combustible,
            'tipo_cambio' => $tipo_cambio,
            'potencia_cv' => $potencia_cv,
            'cilindrada_cm3' => $cilindrada_cm3,
            'imagen' => $imagen,
        ]);

        return true;
    }

    // lista los vehiculos publicos de un usuario para su perfil
    public static function listar_por_usuario_publico(int $usuario_id): array
    {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, marca, modelo, any, imagen, tipo_combustible, potencia_cv
                FROM vehiculos
                WHERE usuario_id = :usuario_id
          ORDER BY id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'usuario_id' => $usuario_id,
        ]);

        return $stmt->fetchAll();
    }

// obtiene un vehiculo para vista publica
    public static function obtener_publico_por_id(int $vehiculo_id): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT 
                v.id,
                v.usuario_id,
                v.marca,
                v.modelo,
                v.any,
                v.imagen,
                v.carroceria,
                v.tipo_combustible,
                v.tipo_cambio,
                v.potencia_cv,
                v.cilindrada_cm3,
                u.nombre AS autor_nombre
            FROM vehiculos v
            INNER JOIN usuarios u ON u.id = v.usuario_id
            WHERE v.id = :vehiculo_id
            LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vehiculo_id' => $vehiculo_id,
        ]);

        $vehiculo = $stmt->fetch();

        return $vehiculo ?: null;
    }
}
