<?php

class RepositorioPublicaciones
{

// devuelve todas las publicaciones con el nombre del autor
    public static function listar_todas(): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT 
                    p.id,
                    p.usuario_id,
                    p.contenido,
                    p.imagen,
                    p.fecha_creacion,
                    u.nombre AS autor_nombre,
                    COUNT(DISTINCT c.id) AS total_comentarios,
                    COUNT(DISTINCT l.id) AS total_likes
                FROM publicaciones p
                INNER JOIN usuarios u ON u.id = p.usuario_id
                LEFT JOIN comentarios_publicaciones c ON c.publicacion_id = p.id
                LEFT JOIN publicaciones_likes l ON l.publicacion_id = p.id
                GROUP BY p.id, p.usuario_id, p.contenido, p.imagen, p.fecha_creacion, u.nombre
                ORDER BY p.id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

// crea una nueva publicación y devuelve su id
    public static function crear(int $usuario_id, string $contenido, ?string $imagen): int {
        $pdo = ConexionBBDD::obtener();

        $sql = "INSERT INTO publicaciones (usuario_id, contenido, imagen)
        VALUES (:usuario_id, :contenido, :imagen)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'contenido' => $contenido,
            'imagen' => $imagen,
        ]);

        return (int) $pdo->lastInsertId();
    }

// devuelve una publicación por su id con el nombre del autor
    public static function obtener_por_id(int $id): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT 
                    p.id,
                    p.usuario_id,
                    p.contenido,
                    p.imagen,
                    p.fecha_creacion,
                    u.nombre AS autor_nombre
                FROM publicaciones p
                INNER JOIN usuarios u ON u.id = p.usuario_id
                WHERE p.id = :id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);

        $publicacion = $stmt->fetch();

        return $publicacion ?: null;
    }

// actualiza una publicación
    public static function actualizar(int $id, string $contenido, ?string $imagen): void {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE publicaciones
                SET contenido = :contenido,
                    imagen = :imagen
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'contenido' => $contenido,
            'imagen' => $imagen,
        ]);
    }

// elimina una publicación
    public static function eliminar(int $id): void {
        $pdo = ConexionBBDD::obtener();

        $sql = "DELETE FROM publicaciones
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
    }
}