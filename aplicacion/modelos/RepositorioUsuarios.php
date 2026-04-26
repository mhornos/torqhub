<?php

class RepositorioUsuarios {
    
//devuelve true si el nombre ya existe en la base de datos, false si no
    public static function existe_nombre(string $nombre): bool{
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT 1 FROM usuarios WHERE nombre = :nombre LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nombre' => $nombre]);

        return (bool) $stmt->fetchColumn();
    }

//devuelve true si el email ya existe en la base de datos, false si no
    public static function existe_email(string $email): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT 1 FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        return (bool) $stmt->fetchColumn();
    }

//añade un nuevo usuario a la base de datos y devuelve su id
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

//busca un usuario por su email, devuelve un array con los datos o null si no se encuentra
    public static function buscar_por_email(string $email): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }


// busca un usuario por su nombre de usuario
    public static function buscar_por_nombre(string $nombre): ?array {
        $pdo = ConexionBBDD::obtener();
    
        $sql = "SELECT id, nombre, email, foto_perfil, rol, fecha_creacion
                FROM usuarios
                WHERE nombre = :nombre
                LIMIT 1";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
        ]);
    
        $usuario = $stmt->fetch();
    
        return $usuario ?: null;
    }


// actualiza la foto de perfil del usuario
    public static function actualizar_foto_perfil(int $usuario_id, string $foto_perfil): bool {
        $pdo = ConexionBBDD::obtener();
    
        $sql = "UPDATE usuarios
                SET foto_perfil = :foto_perfil
                WHERE id = :usuario_id";
    
        $stmt = $pdo->prepare($sql);
    
        return $stmt->execute([
            'foto_perfil' => $foto_perfil,
            'usuario_id' => $usuario_id,
        ]);
    }


// comprueba si un nombre de usuario ya está usado por otro usuario
    public static function existe_nombre_en_otro_usuario(string $nombre, int $usuario_id): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id
                FROM usuarios
                WHERE nombre = :nombre
                AND id != :usuario_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'usuario_id' => $usuario_id,
        ]);

        return (bool) $stmt->fetch();
    }

// comprueba si un email ya está usado por otro usuario
    public static function existe_email_en_otro_usuario(string $email, int $usuario_id): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id
                FROM usuarios
                WHERE email = :email
                AND id != :usuario_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'usuario_id' => $usuario_id,
        ]);

        return (bool) $stmt->fetch();
    }

// actualiza nombre de usuario y email
    public static function actualizar_datos_perfil(int $usuario_id, string $nombre, string $email): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE usuarios
                SET nombre = :nombre,
                    email = :email
                WHERE id = :usuario_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'usuario_id' => $usuario_id,
        ]);
    }


// busca un usuario por id incluyendo la contraseña
    public static function buscar_por_id_con_password(int $usuario_id): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, nombre, email, password_hash
                FROM usuarios
                WHERE id = :usuario_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'usuario_id' => $usuario_id,
        ]);

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

// actualiza la contraseña del usuario
    public static function actualizar_password(int $usuario_id, string $password_hash): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE usuarios
                SET password_hash = :password
                WHERE id = :usuario_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'password' => $password_hash,
            'usuario_id' => $usuario_id,
        ]);
    }
}