<?php

class RepositorioUsuarios
{
    public static function iniciar(): void
    {
        if (!isset($_SESSION['usuarios'])) {
            $_SESSION['usuarios'] = [];
        }
    }

    public static function existe_email(string $email): bool
    {
        self::iniciar();

        foreach ($_SESSION['usuarios'] as $usuario) {
            if ($usuario['email'] === $email) {
                return true;
            }
        }
        return false;
    }

    public static function crear(string $nombre, string $email, string $hash_password): void
    {
        self::iniciar();

        $_SESSION['usuarios'][] = [
            'id' => count($_SESSION['usuarios']) + 1,
            'nombre' => $nombre,
            'email' => $email,
            'password' => $hash_password,
        ];
    }

    public static function buscar_por_email(string $email): ?array
    {
        self::iniciar();

        foreach ($_SESSION['usuarios'] as $usuario) {
            if ($usuario['email'] === $email) {
                return $usuario;
            }
        }
        return null;
    }
}
