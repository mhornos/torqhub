<?php

class RepositorioRecuperacionesPassword {

// invalida recuperaciones anteriores del usuario
    public static function invalidar_anteriores(int $usuario_id): void {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE recuperaciones_password
                SET usado = 1
                WHERE usuario_id = :usuario_id
                AND usado = 0";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'usuario_id' => $usuario_id,
        ]);
    }

// crea una nueva recuperacion de contraseña
    public static function crear(int $usuario_id, string $token_hash, string $fecha_expiracion): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "INSERT INTO recuperaciones_password 
                    (usuario_id, token_hash, fecha_expiracion)
                VALUES 
                    (:usuario_id, :token_hash, :fecha_expiracion)";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'usuario_id' => $usuario_id,
            'token_hash' => $token_hash,
            'fecha_expiracion' => $fecha_expiracion,
        ]);
    }

// busca un token válido
    public static function buscar_token_valido(string $token_hash): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT *
                FROM recuperaciones_password
                WHERE token_hash = :token_hash
                AND usado = 0
                AND fecha_expiracion >= NOW()
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'token_hash' => $token_hash,
        ]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }

// marca token como usado
    public static function marcar_como_usado(int $id): void {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE recuperaciones_password
                SET usado = 1
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
    }
}