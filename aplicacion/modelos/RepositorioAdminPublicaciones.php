<?php

class RepositorioAdminPublicaciones {

    // obtiene todas las publicaciones para el panel de administración
    public static function listar(): array {
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
                ORDER BY p.fecha_creacion DESC, p.id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // obtiene una publicación concreta antes de eliminarla
    public static function obtener_por_id(int $publicacion_id): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT
                    id,
                    usuario_id,
                    contenido,
                    imagen,
                    fecha_creacion
                FROM publicaciones
                WHERE id = :publicacion_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
        ]);

        $publicacion = $stmt->fetch();

        return $publicacion ?: null;
    }

    // elimina una publicación desde administración
    public static function eliminar(int $publicacion_id): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "DELETE FROM publicaciones
                WHERE id = :publicacion_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
        ]);

        return $stmt->rowCount() > 0;
    }
}