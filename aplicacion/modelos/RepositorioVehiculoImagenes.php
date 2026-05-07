<?php

class RepositorioVehiculoImagenes
{
// guarda varias imágenes asociadas a un vehículo
    public static function insertar_varias(int $vehiculo_id, array $nombres_archivo): void {
        if ($vehiculo_id <= 0 || empty($nombres_archivo)) {
            return;
        }

        $pdo = ConexionBBDD::obtener();

        $sql_orden = "
            SELECT COALESCE(MAX(orden), 0)
            FROM vehiculo_imagenes
            WHERE vehiculo_id = :vehiculo_id
        ";

        $stmt_orden = $pdo->prepare($sql_orden);
        $stmt_orden->execute([
            'vehiculo_id' => $vehiculo_id,
        ]);

        $orden_actual = (int) $stmt_orden->fetchColumn();

        $sql = "
            INSERT IGNORE INTO vehiculo_imagenes (
                vehiculo_id,
                nombre_archivo,
                texto_alt,
                principal,
                orden
            )
            VALUES (
                :vehiculo_id,
                :nombre_archivo,
                :texto_alt,
                :principal,
                :orden
            )
        ";

        $stmt = $pdo->prepare($sql);

        foreach ($nombres_archivo as $indice => $nombre_archivo) {
            $orden_actual++;

            $stmt->execute([
                'vehiculo_id' => $vehiculo_id,
                'nombre_archivo' => $nombre_archivo,
                'texto_alt' => null,
                'principal' => $orden_actual === 1 ? 1 : 0,
                'orden' => $orden_actual,
            ]);
        }
    }

// lista las imágenes de un vehículo comprobando que pertenece al usuario
    public static function listar_por_vehiculo_y_usuario(int $vehiculo_id, int $usuario_id): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "
            SELECT
                vi.id,
                vi.vehiculo_id,
                vi.nombre_archivo,
                vi.texto_alt,
                vi.principal,
                vi.orden,
                vi.fecha_creacion
            FROM vehiculo_imagenes vi
            INNER JOIN vehiculos v ON v.id = vi.vehiculo_id
            WHERE vi.vehiculo_id = :vehiculo_id
              AND v.usuario_id = :usuario_id
            ORDER BY vi.principal DESC, vi.orden ASC, vi.id ASC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vehiculo_id' => $vehiculo_id,
            'usuario_id' => $usuario_id,
        ]);

        return $stmt->fetchAll();
    }
    
}