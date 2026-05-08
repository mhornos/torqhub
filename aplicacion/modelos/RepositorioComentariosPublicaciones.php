<?php

class RepositorioComentariosPublicaciones
{
    
// devuelve solo los comentarios principales de una publicación con su número de respuestas
    public static function listar_principales_por_publicacion(int $publicacion_id): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT
                    c.id,
                    c.publicacion_id,
                    c.usuario_id,
                    c.contenido,
                    c.fecha_creacion,
                    c.respuesta_a_id,
                    u.nombre AS autor_nombre,
                    u.foto_perfil AS autor_foto_perfil,
                    COUNT(r.id) AS total_respuestas
                FROM comentarios_publicaciones c
                INNER JOIN usuarios u ON u.id = c.usuario_id
                LEFT JOIN comentarios_publicaciones r ON r.respuesta_a_id = c.id
                WHERE c.publicacion_id = :publicacion_id
                AND c.respuesta_a_id IS NULL
                GROUP BY c.id, c.publicacion_id, c.usuario_id, c.contenido, c.fecha_creacion, c.respuesta_a_id, u.nombre, u.foto_perfil
                ORDER BY c.fecha_creacion DESC, c.id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
        ]);

        return $stmt->fetchAll();
    }

// devuelve las respuestas de un comentario principal
    public static function listar_respuestas_de_comentario(int $comentario_id): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT
                    c.id,
                    c.publicacion_id,
                    c.usuario_id,
                    c.contenido,
                    c.fecha_creacion,
                    c.respuesta_a_id,
                    u.nombre AS autor_nombre,
                    u.foto_perfil AS autor_foto_perfil
                FROM comentarios_publicaciones c
                INNER JOIN usuarios u ON u.id = c.usuario_id
                WHERE c.respuesta_a_id = :comentario_id
                ORDER BY c.id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'comentario_id' => $comentario_id,
        ]);

        return $stmt->fetchAll();
    }

// crea un comentario o una respuesta y devuelve su id
    public static function crear(int $publicacion_id, int $usuario_id, string $contenido, ?int $respuesta_a_id = null): int {
        $pdo = ConexionBBDD::obtener();

        $sql = "INSERT INTO comentarios_publicaciones (
                    publicacion_id,
                    respuesta_a_id,
                    usuario_id,
                    contenido
                ) VALUES (
                    :publicacion_id,
                    :respuesta_a_id,
                    :usuario_id,
                    :contenido
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'publicacion_id' => $publicacion_id,
            'respuesta_a_id' => $respuesta_a_id,
            'usuario_id' => $usuario_id,
            'contenido' => $contenido,
        ]);

        return (int) $pdo->lastInsertId();
    }

// comprueba si un comentario es principal dentro de una publicación
    public static function obtener_comentario_principal(int $comentario_id, int $publicacion_id): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT
                    c.id,
                    c.publicacion_id,
                    c.usuario_id,
                    c.contenido,
                    c.fecha_creacion,
                    c.respuesta_a_id,
                    u.nombre AS autor_nombre
                FROM comentarios_publicaciones c
                INNER JOIN usuarios u ON u.id = c.usuario_id
                WHERE c.id = :comentario_id
                AND c.publicacion_id = :publicacion_id
                AND c.respuesta_a_id IS NULL
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'comentario_id' => $comentario_id,
            'publicacion_id' => $publicacion_id,
        ]);

        $comentario = $stmt->fetch();

        return $comentario ?: null;
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

// actualiza un comentario solo si pertenece al usuario indicado
    public static function actualizar(int $id, int $usuario_id, string $contenido): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE comentarios_publicaciones
                SET contenido = :contenido
                WHERE id = :id
                AND usuario_id = :usuario_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'usuario_id' => $usuario_id,
            'contenido' => $contenido,
        ]);
    }

// elimina un comentario solo si pertenece al usuario indicado
    public static function eliminar(int $id, int $usuario_id): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "DELETE FROM comentarios_publicaciones
                WHERE id = :id
                AND usuario_id = :usuario_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'usuario_id' => $usuario_id,
        ]);

        return $stmt->rowCount() > 0;
    }
    
}