<?php

class RepositorioUsuarios
{
    
    public static function existe_nombre(string $nombre): bool{
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT 1 FROM usuarios WHERE nombre = :nombre LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nombre' => $nombre]);

        return (bool) $stmt->fetchColumn();
    }

    public static function existe_email(string $email): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT 1 FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        return (bool) $stmt->fetchColumn();
    }

    public static function crear(string $nombre, string $email, string $hash_password): int {
        $pdo = ConexionBBDD::obtener();

        $sql = "INSERT INTO usuarios (nombre, email, password_hash) VALUES (:nombre, :email, :password_hash)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'password_hash' => $hash_password,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function buscar_por_email(string $email): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }
}