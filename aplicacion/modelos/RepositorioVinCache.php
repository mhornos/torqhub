<?php

class RepositorioVinCache
{

// busca en la base de datos por VIN, devuelve un array con los datos o null si no se encuentra
    public static function buscar_por_vin(string $vin): ?array {
        $pdo = ConexionBBDD::obtener();

        $sql = "SELECT 
                    id,
                    vin,
                    marca,
                    modelo,
                    `any`,
                    carroceria_api,
                    combustible_api,
                    cambio_api,
                    carroceria_torqhub,
                    combustible_torqhub,
                    cambio_torqhub,
                    potencia_cv,
                    cilindrada_cm3,
                    error_codigo,
                    error_texto,
                    respuesta_json,
                    fecha_consulta,
                    fecha_actualizacion
                FROM cache_vin
                WHERE vin = :vin
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vin' => $vin,
        ]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }

// guarda o actualiza los datos de un VIN en la base de datos
    public static function guardar_o_actualizar(array $datos): void {
        $pdo = ConexionBBDD::obtener();

        $sql = "INSERT INTO cache_vin (
                    vin,
                    marca,
                    modelo,
                    `any`,
                    carroceria_api,
                    combustible_api,
                    cambio_api,
                    carroceria_torqhub,
                    combustible_torqhub,
                    cambio_torqhub,
                    potencia_cv,
                    cilindrada_cm3,
                    error_codigo,
                    error_texto,
                    respuesta_json
                ) VALUES (
                    :vin,
                    :marca,
                    :modelo,
                    :any,
                    :carroceria_api,
                    :combustible_api,
                    :cambio_api,
                    :carroceria_torqhub,
                    :combustible_torqhub,
                    :cambio_torqhub,
                    :potencia_cv,
                    :cilindrada_cm3,
                    :error_codigo,
                    :error_texto,
                    :respuesta_json
                )
                ON DUPLICATE KEY UPDATE
                    marca = VALUES(marca),
                    modelo = VALUES(modelo),
                    `any` = VALUES(`any`),
                    carroceria_api = VALUES(carroceria_api),
                    combustible_api = VALUES(combustible_api),
                    cambio_api = VALUES(cambio_api),
                    carroceria_torqhub = VALUES(carroceria_torqhub),
                    combustible_torqhub = VALUES(combustible_torqhub),
                    cambio_torqhub = VALUES(cambio_torqhub),
                    potencia_cv = VALUES(potencia_cv),
                    cilindrada_cm3 = VALUES(cilindrada_cm3),
                    error_codigo = VALUES(error_codigo),
                    error_texto = VALUES(error_texto),
                    respuesta_json = VALUES(respuesta_json),
                    fecha_actualizacion = NOW()";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'vin' => $datos['vin'],
            'marca' => $datos['marca'],
            'modelo' => $datos['modelo'],
            'any' => $datos['any'],
            'carroceria_api' => $datos['carroceria_api'],
            'combustible_api' => $datos['combustible_api'],
            'cambio_api' => $datos['cambio_api'],
            'carroceria_torqhub' => $datos['carroceria_torqhub'],
            'combustible_torqhub' => $datos['combustible_torqhub'],
            'cambio_torqhub' => $datos['cambio_torqhub'],
            'potencia_cv' => $datos['potencia_cv'],
            'cilindrada_cm3' => $datos['cilindrada_cm3'],
            'error_codigo' => $datos['error_codigo'],
            'error_texto' => $datos['error_texto'],
            'respuesta_json' => $datos['respuesta_json'],
        ]);
    }
}