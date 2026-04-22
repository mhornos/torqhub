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
}