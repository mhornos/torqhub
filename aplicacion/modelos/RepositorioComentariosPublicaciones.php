<?php

class RepositorioComentariosPublicaciones
{
    
// devuelve los comentarios de una publicación con el nombre del autor
    public static function listar_por_publicacion(int $publicacion_id): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT
                    c.id,
                    c.publicacion_id,
                    c.usuario_id,
                    c.contenido,
                    c.fecha_creacion,
                    u.nombre AS autor_nombre
                FROM comentarios_publicaciones c
                INNER JOIN usuarios u ON u.id = c.usuario_id
                WHERE c.publicacion_id = :publicacion_id
                ORDER BY c.id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
        ]);

        return $stmt->fetchAll();
    }


// crea un comentario en una publicación y devuelve su id
    public static function crear(int $publicacion_id, int $usuario_id, string $contenido): int{
        $pdo = ConexionBBDD::obtener();

        $sql = "INSERT INTO comentarios_publicaciones (publicacion_id, usuario_id, contenido)
                VALUES (:publicacion_id, :usuario_id, :contenido)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
            'usuario_id' => $usuario_id,
            'contenido' => $contenido,
        ]);

        return (int) $pdo->lastInsertId();
    }


// devuelve un comentario por su id con el nombre del autor
    public static function obtener_por_id(int $id): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT
                    c.id,
                    c.publicacion_id,
                    c.usuario_id,
                    c.contenido,
                    c.fecha_creacion,
                    u.nombre AS autor_nombre
                FROM comentarios_publicaciones c
                INNER JOIN usuarios u ON u.id = c.usuario_id
                WHERE c.id = :id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);

        $comentario = $stmt->fetch();

        return $comentario ?: null;
    }

// actualiza un comentario
    public static function actualizar(int $id, string $contenido): void {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE comentarios_publicaciones
                SET contenido = :contenido
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'contenido' => $contenido,
        ]);
    }

// elimina un comentario
    public static function eliminar(int $id): void  {
        $pdo = ConexionBBDD::obtener();

        $sql = "DELETE FROM comentarios_publicaciones
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
    }
    
}