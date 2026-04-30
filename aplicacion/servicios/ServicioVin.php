<?php

// servicio para consultar datos de un VIN, con cache en base de datos

class ServicioVin
{

// url base de la api para consultar datos de un VIN
    private const URL_API = 'https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValuesExtended/';

// consulta los datos de un VIN, primero busca en cache y si no encuentra consulta la api
    public function consultar(string $vin): array {
        $vin = $this->normalizar_vin($vin);

        if (!$this->vin_valido($vin)) {
            throw new InvalidArgumentException('El VIN debe tener 17 carácteres y no puede contener "i", "o" ni "q"');
        }

        $cache = RepositorioVinCache::buscar_por_vin($vin);

        if ($cache) {
            return $this->formatear_salida($cache, 'cache');
        }

        $respuesta_api = $this->consultar_api($vin);
        $datos = $this->extraer_datos($vin, $respuesta_api);

        RepositorioVinCache::guardar_o_actualizar($datos);

        return $this->formatear_salida($datos, 'api');
    }

// normaliza el VIN eliminando espacios y convirtiendo a mayúsculas
    private function normalizar_vin(string $vin): string {
        $vin = trim($vin);
        $vin = strtoupper($vin);

        return preg_replace('/\s+/', '', $vin);
    }

// verifica si el VIN es válido
    private function vin_valido(string $vin): bool {
        return preg_match('/^[A-HJ-NPR-Z0-9]{17}$/', $vin) === 1;
    }

// consulta la api para obtener los datos de un VIN, devuelve un array con la respuesta o lanza una excepción si hay error
    private function consultar_api(string $vin): array {
        if (!function_exists('curl_init')) {
            throw new RuntimeException('Curl no está disponible en PHP');
        }

        $url = self::URL_API . rawurlencode($vin) . '?format=json';

        $curl = curl_init($url);

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
            ],
        ]);

        $respuesta = curl_exec($curl);
        $error_curl = curl_error($curl);
        $codigo_http = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($respuesta === false) {
            throw new RuntimeException('No se pudo consultar la api vin: ' . $error_curl);
        }

        if ($codigo_http < 200 || $codigo_http >= 300) {
            throw new RuntimeException('La api vin respondió con código http ' . $codigo_http);
        }

        $json = json_decode($respuesta, true);

        if (!is_array($json)) {
            throw new RuntimeException('La respuesta de la api vin no es un json válido');
        }

        return $json;
    }

// extrae los datos relevantes de la respuesta de la api y los adapta al formato que usamos en torqhub
    private function extraer_datos(string $vin, array $respuesta_api): array {
        $resultado = $respuesta_api['Results'][0] ?? [];

        if (!is_array($resultado)) {
            throw new RuntimeException('La api vin no devolvió resultados válidos');
        }

        $marca = $this->limpiar_valor($resultado['Make'] ?? null);
        $modelo = $this->limpiar_valor($resultado['Model'] ?? null);
        $any = $this->entero_desde_valor($resultado['ModelYear'] ?? null);

        $carroceria_api = $this->limpiar_valor($resultado['BodyClass'] ?? null);
        $combustible_api = $this->limpiar_valor($resultado['FuelTypePrimary'] ?? null);
        $cambio_api = $this->limpiar_valor($resultado['TransmissionStyle'] ?? null);

        $potencia_cv = $this->entero_desde_valor($resultado['EngineHP'] ?? null);
        $cilindrada_cm3 = $this->entero_desde_valor($resultado['DisplacementCC'] ?? null);

        return [
            'vin' => $vin,
            'marca' => $marca,
            'modelo' => $modelo,
            'any' => $any,

            'carroceria_api' => $carroceria_api,
            'combustible_api' => $combustible_api,
            'cambio_api' => $cambio_api,

            'carroceria_torqhub' => $this->adaptar_carroceria($carroceria_api),
            'combustible_torqhub' => $this->adaptar_combustible($combustible_api),
            'cambio_torqhub' => $this->adaptar_cambio($cambio_api),

            'potencia_cv' => $potencia_cv,
            'cilindrada_cm3' => $cilindrada_cm3,

            'error_codigo' => $this->limpiar_valor($resultado['ErrorCode'] ?? null),
            'error_texto' => $this->limpiar_valor($resultado['ErrorText'] ?? null),

            'respuesta_json' => json_encode($respuesta_api, JSON_UNESCAPED_UNICODE),
        ];
    }

// limpia un valor de texto eliminando espacios, convirtiendo a null si es vacío o si es un valor que indica que no está disponible
    private function limpiar_valor(?string $valor): ?string {
        if ($valor === null) {
            return null;
        }

        $valor = trim($valor);

        if ($valor === '') {
            return null;
        }

        $valor_minusculas = strtolower($valor);

        $valores_vacios = [
            'not applicable',
            'not available',
            'unknown',
            'null',
        ];

        if (in_array($valor_minusculas, $valores_vacios, true)) {
            return null;
        }

        return $valor;
    }

// convierte un valor a entero redondeando y devolviendo null si no es un número válido o si es cero o negativo
    private function entero_desde_valor(mixed $valor): ?int {
        if ($valor === null || $valor === '') {
            return null;
        }

        if (!is_numeric($valor)) {
            return null;
        }

        $numero = (int) round((float) $valor);

        return $numero > 0 ? $numero : null;
    }

// adapta el valor de la carrocería al formato que usamos en torqhub
    private function adaptar_carroceria(?string $carroceria_api): ?string {
        if ($carroceria_api === null) {
            return null;
        }

        $valor = strtolower($carroceria_api);

        if (str_contains($valor, 'sedan')) {
            return 'sedán';
        }

        if (str_contains($valor, 'coupe')) {
            return 'coupé';
        }

        if (str_contains($valor, 'convertible')) {
            return 'cabrio';
        }

        if (str_contains($valor, 'hatchback')) {
            return 'coche pequeño';
        }

        if (str_contains($valor, 'wagon')) {
            return 'familiar';
        }

        if (str_contains($valor, 'sport utility') || str_contains($valor, 'suv')) {
            return 'suv/4x4';
        }

        if (str_contains($valor, 'van')) {
            return 'furgoneta';
        }

        return 'otros';
    }

// adapta el valor del combustible al formato que usamos en torqhub
    private function adaptar_combustible(?string $combustible_api): ?string {
        if ($combustible_api === null) {
            return null;
        }

        $valor = strtolower($combustible_api);

        if (str_contains($valor, 'gasoline')) {
            return 'gasolina';
        }

        if (str_contains($valor, 'diesel')) {
            return 'diesel';
        }

        if (str_contains($valor, 'electric')) {
            return 'electrico';
        }

        if (str_contains($valor, 'natural gas')) {
            return 'gas natural (CNG)';
        }

        if (str_contains($valor, 'ethanol')) {
            return 'etanol';
        }

        if (str_contains($valor, 'hydrogen')) {
            return 'hidrogeno';
        }

        if (str_contains($valor, 'lpg') || str_contains($valor, 'propane')) {
            return 'gas licuado (GLP)';
        }

        return 'otros';
    }

// adapta el valor del tipo de cambio al formato que usamos en torqhub
    private function adaptar_cambio(?string $cambio_api): ?string {
        if ($cambio_api === null) {
            return null;
        }

        $valor = strtolower($cambio_api);

        if (str_contains($valor, 'automatic')) {
            return 'automatico';
        }

        if (str_contains($valor, 'manual')) {
            return 'manual';
        }

        return null;
    }

// formatea la salida con los datos relevantes y el origen de los datos (cache o api)
    private function formatear_salida(array $datos, string $origen): array {
        return [
            'origen' => $origen,
            'vin' => $datos['vin'],
            'datos_api' => [
                'marca' => $datos['marca'] ?? null,
                'modelo' => $datos['modelo'] ?? null,
                'any' => isset($datos['any']) ? (int) $datos['any'] : null,
                'carroceria' => $datos['carroceria_api'] ?? null,
                'combustible' => $datos['combustible_api'] ?? null,
                'cambio' => $datos['cambio_api'] ?? null,
                'potencia_cv' => isset($datos['potencia_cv']) ? (int) $datos['potencia_cv'] : null,
                'cilindrada_cm3' => isset($datos['cilindrada_cm3']) ? (int) $datos['cilindrada_cm3'] : null,
            ],
            'campos_torqhub' => [
                'marca' => $datos['marca'] ?? null,
                'modelo' => $datos['modelo'] ?? null,
                'any' => isset($datos['any']) ? (int) $datos['any'] : null,
                'carroceria' => $datos['carroceria_torqhub'] ?? null,
                'tipo_combustible' => $datos['combustible_torqhub'] ?? null,
                'tipo_cambio' => $datos['cambio_torqhub'] ?? null,
                'potencia_cv' => isset($datos['potencia_cv']) ? (int) $datos['potencia_cv'] : null,
                'cilindrada_cm3' => isset($datos['cilindrada_cm3']) ? (int) $datos['cilindrada_cm3'] : null,
            ],
            'error_codigo' => $datos['error_codigo'] ?? null,
            'error_texto' => $datos['error_texto'] ?? null,
        ];
    }
}