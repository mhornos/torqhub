<?php

class RepositorioLikesPublicaciones
{

// devuelve true si el usuario ya ha dado like a la publicación, false en caso contrario
    public static function usuario_ya_dio_like(int $publicacion_id, int $usuario_id): bool{
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id
                FROM publicaciones_likes
                WHERE publicacion_id = :publicacion_id
                AND usuario_id = :usuario_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
            'usuario_id' => $usuario_id,
        ]);

        return (bool) $stmt->fetch();
    }

// añade un like a la publicación por parte del usuario
    public static function dar_like(int $publicacion_id, int $usuario_id): void{
        $pdo = ConexionBBDD::obtener();

        $sql = "INSERT INTO publicaciones_likes (publicacion_id, usuario_id)
                VALUES (:publicacion_id, :usuario_id)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
            'usuario_id' => $usuario_id,
        ]);
    }

// quita el like de la publicación por parte del usuario
    public static function quitar_like(int $publicacion_id, int $usuario_id): void {
        $pdo = ConexionBBDD::obtener();

        $sql = "DELETE FROM publicaciones_likes
                WHERE publicacion_id = :publicacion_id
                AND usuario_id = :usuario_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
            'usuario_id' => $usuario_id,
        ]);
    }

// devuelve el número total de likes que tiene una publicación
    public static function contar_likes(int $publicacion_id): int {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT COUNT(*) 
                FROM publicaciones_likes
                WHERE publicacion_id = :publicacion_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
        ]);

        return (int) $stmt->fetchColumn();
    }
}

?>