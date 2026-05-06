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
}
