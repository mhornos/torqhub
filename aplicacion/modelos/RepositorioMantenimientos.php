<?php

class RepositorioMantenimientos
{

//devuelve el historial de mantenimientos de un vehículo ordenado del más reciente al más antiguo
    public static function listar_por_vehiculo(int $vehiculo_id): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, vehiculo_id, fecha, tipo, descripcion, kilometros, coste, fecha_creacion
                FROM mantenimientos
                WHERE vehiculo_id = :vehiculo_id
                ORDER BY fecha DESC, id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vehiculo_id' => $vehiculo_id,
        ]);

        return $stmt->fetchAll();
    }

//crea un nuevo mantenimiento asociado a un vehículo y devuelve su id
    public static function crear(int $vehiculo_id, string $fecha, string $tipo, ?string $descripcion, ?int $kilometros, ?float $coste): int {
        $pdo = ConexionBBDD::obtener();
        
        $sql = "INSERT INTO mantenimientos (vehiculo_id, fecha, tipo, descripcion, kilometros, coste)
                VALUES (:vehiculo_id, :fecha, :tipo, :descripcion, :kilometros, :coste)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vehiculo_id' => $vehiculo_id,
            'fecha' => $fecha,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'kilometros' => $kilometros,
            'coste' => $coste,
        ]);

        return (int) $pdo->lastInsertId();
    }

//devuelve un mantenimiento si pertenece a un vehículo del usuario
    public static function buscar_por_id_y_usuario(int $mantenimiento_id, int $usuario_id): ?array{
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT m.id, m.vehiculo_id, m.fecha, m.tipo, m.descripcion, m.kilometros, m.coste, m.fecha_creacion
                FROM mantenimientos m
                INNER JOIN vehiculos v ON v.id = m.vehiculo_id
                WHERE m.id = :id
                  AND v.usuario_id = :usuario_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $mantenimiento_id,
            'usuario_id' => $usuario_id,
        ]);

        $mantenimiento = $stmt->fetch();

        return $mantenimiento ?: null;
    }

//actualiza un mantenimiento existente
    public static function actualizar(int $mantenimiento_id, int $vehiculo_id, string $fecha, string $tipo, ?string $descripcion, ?int $kilometros, ?float $coste): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "UPDATE mantenimientos
                SET fecha = :fecha,
                    tipo = :tipo,
                    descripcion = :descripcion,
                    kilometros = :kilometros,
                    coste = :coste
                WHERE id = :id
                  AND vehiculo_id = :vehiculo_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $mantenimiento_id,
            'vehiculo_id' => $vehiculo_id,
            'fecha' => $fecha,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'kilometros' => $kilometros,
            'coste' => $coste,
        ]);
    }

//elimina un mantenimiento si pertenece al vehículo indicado
    public static function eliminar(int $mantenimiento_id, int $vehiculo_id): bool {
        $pdo = ConexionBBDD::obtener();

        $sql = "DELETE FROM mantenimientos
                WHERE id = :id
                  AND vehiculo_id = :vehiculo_id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'id' => $mantenimiento_id,
            'vehiculo_id' => $vehiculo_id,
        ]);
    }

//devuelve los tipos de mantenimiento distintos de un vehículo
    public static function listar_tipos_por_vehiculo(int $vehiculo_id): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT DISTINCT tipo
                FROM mantenimientos
                WHERE vehiculo_id = :vehiculo_id
                ORDER BY tipo ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vehiculo_id' => $vehiculo_id,
        ]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

//devuelve los mantenimientos filtrados de un vehículo
    public static function filtrar_por_vehiculo(int $vehiculo_id, array $filtros = []): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT id, vehiculo_id, fecha, tipo, descripcion, kilometros, coste, fecha_creacion
                FROM mantenimientos
                WHERE vehiculo_id = :vehiculo_id";

        $params = [
            'vehiculo_id' => $vehiculo_id,
        ];

        if (($filtros['tipo'] ?? '') !== '') {
            $sql .= " AND tipo = :tipo";
            $params['tipo'] = $filtros['tipo'];
        }

        if (($filtros['fecha_desde'] ?? '') !== '') {
            $sql .= " AND fecha >= :fecha_desde";
            $params['fecha_desde'] = $filtros['fecha_desde'];
        }

        if (($filtros['fecha_hasta'] ?? '') !== '') {
            $sql .= " AND fecha <= :fecha_hasta";
            $params['fecha_hasta'] = $filtros['fecha_hasta'];
        }

        if (($filtros['kilometros_min'] ?? '') !== '') {
            $sql .= " AND kilometros IS NOT NULL AND kilometros >= :kilometros_min";
            $params['kilometros_min'] = $filtros['kilometros_min'];
        }

        if (($filtros['kilometros_max'] ?? '') !== '') {
            $sql .= " AND kilometros IS NOT NULL AND kilometros <= :kilometros_max";
            $params['kilometros_max'] = $filtros['kilometros_max'];
        }

        if (($filtros['coste_min'] ?? '') !== '') {
            $sql .= " AND coste IS NOT NULL AND coste >= :coste_min";
            $params['coste_min'] = $filtros['coste_min'];
        }

        if (($filtros['coste_max'] ?? '') !== '') {
            $sql .= " AND coste IS NOT NULL AND coste <= :coste_max";
            $params['coste_max'] = $filtros['coste_max'];
        }

                $orden_campo = $filtros['orden_campo'] ?? 'fecha';
        $orden_direccion = $filtros['orden_direccion'] ?? 'desc';

        $campos_orden_validos = [
            'fecha' => 'fecha',
            'kilometros' => 'kilometros',
            'coste' => 'coste',
        ];

        $direcciones_validas = [
            'asc' => 'ASC',
            'desc' => 'DESC',
        ];

        $sql_campo_orden = $campos_orden_validos[$orden_campo] ?? 'fecha';
        $sql_direccion_orden = $direcciones_validas[$orden_direccion] ?? 'DESC';

        $sql .= " ORDER BY {$sql_campo_orden} {$sql_direccion_orden}, id DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }


//devuelve un resumen de los mantenimientos filtrados de un vehículo
    public static function obtener_resumen_filtrado_por_vehiculo(int $vehiculo_id, array $filtros = []): array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT
                    COUNT(*) AS total_mantenimientos,
                    COALESCE(SUM(coste), 0) AS coste_total
                FROM mantenimientos
                WHERE vehiculo_id = :vehiculo_id";

        $params = [
            'vehiculo_id' => $vehiculo_id,
        ];

        if (($filtros['tipo'] ?? '') !== '') {
            $sql .= " AND tipo = :tipo";
            $params['tipo'] = $filtros['tipo'];
        }

        if (($filtros['fecha_desde'] ?? '') !== '') {
            $sql .= " AND fecha >= :fecha_desde";
            $params['fecha_desde'] = $filtros['fecha_desde'];
        }

        if (($filtros['fecha_hasta'] ?? '') !== '') {
            $sql .= " AND fecha <= :fecha_hasta";
            $params['fecha_hasta'] = $filtros['fecha_hasta'];
        }

        if (($filtros['kilometros_min'] ?? '') !== '') {
            $sql .= " AND kilometros IS NOT NULL AND kilometros >= :kilometros_min";
            $params['kilometros_min'] = $filtros['kilometros_min'];
        }

        if (($filtros['kilometros_max'] ?? '') !== '') {
            $sql .= " AND kilometros IS NOT NULL AND kilometros <= :kilometros_max";
            $params['kilometros_max'] = $filtros['kilometros_max'];
        }

        if (($filtros['coste_min'] ?? '') !== '') {
            $sql .= " AND coste IS NOT NULL AND coste >= :coste_min";
            $params['coste_min'] = $filtros['coste_min'];
        }

        if (($filtros['coste_max'] ?? '') !== '') {
            $sql .= " AND coste IS NOT NULL AND coste <= :coste_max";
            $params['coste_max'] = $filtros['coste_max'];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $resumen = $stmt->fetch();

        return [
            'total_mantenimientos' => (int) ($resumen['total_mantenimientos'] ?? 0),
            'coste_total' => (float) ($resumen['coste_total'] ?? 0),
        ];
    }
}