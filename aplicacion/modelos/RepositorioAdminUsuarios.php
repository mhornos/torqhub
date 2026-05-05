<?php

class RepositorioAdminUsuarios {

// obtiene todos los usuarios para el panel de administración
    public static function listar(): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, nombre, email, rol, activo, fecha_creacion
                FROM usuarios
                ORDER BY fecha_creacion DESC, id DESC";

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll();
    }

// actualiza el rol de un usuario
    public static function actualizar_rol(int $usuario_id, string $rol): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE usuarios
                SET rol = :rol
                WHERE id = :usuario_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'rol' => $rol,
            'usuario_id' => $usuario_id,
        ]);
    }

// activa o desactiva un usuario
    public static function actualizar_estado(int $usuario_id, int $activo): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE usuarios
                SET activo = :activo
                WHERE id = :usuario_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'activo' => $activo,
            'usuario_id' => $usuario_id,
        ]);
    }
}