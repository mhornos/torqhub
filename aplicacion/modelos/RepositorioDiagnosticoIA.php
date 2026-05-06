<?php

class RepositorioDiagnosticoIA
{
// comprueba si la base de conocimiento ya tiene causas cargadas
    public static function tiene_causas(): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT COUNT(*) FROM ia_causas";
        $stmt = $pdo->query($sql);

        return (int) $stmt->fetchColumn() > 0;
    }

// carga las causas activas con sus keywords para el motor de diagnóstico
    public static function listar_causas_activas_con_keywords(): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "
            SELECT
                c.id,
                c.clave,
                c.titulo,
                c.descripcion,
                c.recomendacion,
                c.activo,
                k.keyword
            FROM ia_causas c
            LEFT JOIN ia_keywords k ON k.causa_id = c.id
            WHERE c.activo = 1
            ORDER BY c.id ASC, k.keyword ASC
        ";

        $stmt = $pdo->query($sql);
        $filas = $stmt->fetchAll();

        $causas = [];

        foreach ($filas as $fila) {
            $causa_id = (int) $fila['id'];

            if (!isset($causas[$causa_id])) {
                $causas[$causa_id] = [
                    'id' => $causa_id,
                    'clave' => $fila['clave'],
                    'titulo' => $fila['titulo'],
                    'descripcion' => $fila['descripcion'],
                    'recomendacion' => $fila['recomendacion'],
                    'activo' => (int) $fila['activo'],
                    'keywords' => [],
                ];
            }

            if (!empty($fila['keyword'])) {
                $causas[$causa_id]['keywords'][] = $fila['keyword'];
            }
        }

        return array_values($causas);
    }

// carga todas las causas con sus keywords para el panel de administración
    public static function listar_causas_con_keywords(): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "
        SELECT
            c.id,
            c.clave,
            c.titulo,
            c.descripcion,
            c.recomendacion,
            c.activo,
            c.fecha_creacion,
            k.keyword
        FROM ia_causas c
        LEFT JOIN ia_keywords k ON k.causa_id = c.id
        ORDER BY c.id ASC, k.keyword ASC
    ";

        $stmt = $pdo->query($sql);
        $filas = $stmt->fetchAll();

        $causas = [];

        foreach ($filas as $fila) {
            $causa_id = (int) $fila['id'];

            if (!isset($causas[$causa_id])) {
                $causas[$causa_id] = [
                    'id' => $causa_id,
                    'clave' => $fila['clave'],
                    'titulo' => $fila['titulo'],
                    'descripcion' => $fila['descripcion'],
                    'recomendacion' => $fila['recomendacion'],
                    'activo' => (int) $fila['activo'],
                    'fecha_creacion' => $fila['fecha_creacion'],
                    'keywords' => [],
                ];
            }

            if (!empty($fila['keyword'])) {
                $causas[$causa_id]['keywords'][] = $fila['keyword'];
            }
        }

        return array_values($causas);
    }

// obtiene una causa concreta por id
    public static function obtener_causa_por_id(int $causa_id): ?array {
        if ($causa_id <= 0) {
            return null;
        }

        $pdo = ConexionBBDD::obtener();

        $sql = "
        SELECT
            id,
            clave,
            titulo,
            activo
        FROM ia_causas
        WHERE id = :id
        LIMIT 1
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $causa_id, PDO::PARAM_INT);
        $stmt->execute();

        $causa = $stmt->fetch();

        return $causa ?: null;
    }

// activa o desactiva una causa de la base de conocimiento ia
    public static function actualizar_estado_causa(int $causa_id, int $activo): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "
        UPDATE ia_causas
        SET activo = :activo
        WHERE id = :id
        LIMIT 1
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':activo', $activo, PDO::PARAM_INT);
        $stmt->bindValue(':id', $causa_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    

}
