<?php
/* contiene un conjunto de posibles causas de problemas en un vehiculo, cada una con palabras clave asociadas y 
 recomendaciones para su solucion. la clase tiene un metodo diagnosticar que toma un texto de entrada, lo normaliza y 
 busca coincidencias con las palabras clave de cada causa para determinar posibles problemas y su nivel de confianza, 
 los resultados se ordenan por confianza y se devuelven los tres mas relevantes */

class MotorDiagnosticoIA
{
    private array $causas = [
        [
            'clave' => 'bateria',
            'titulo' => 'bateria descargada o en mal estado',
            'keywords' => [
                'bateria',
                'bateria descargada',
                'bateria baja',
                'bateria muerta',
                'sin bateria',
                'no arranca',
                'le cuesta arrancar',
                'tarda en arrancar',
                'tarda mucho en arrancar',
                'arranque lento',
                'motor gira lento',
                'gira lento',
                'luces flojas',
                'luces debiles',
                'luces bajas',
                'luces del cuadro flojas',
                'cuadro flojo',
                'cuadro se apaga',
                'click al arrancar',
                'hace click',
                'por las mananas',
                'en frio no arranca',
                'no tiene fuerza para arrancar'
            ],
            'recomendacion' => 'revisar la carga de la bateria, bornes y alternador antes de sustituirla.'
        ],
        [
            'clave' => 'arranque',
            'titulo' => 'fallo en el motor de arranque',
            'keywords' => [
                'motor de arranque',
                'fallo de arranque',
                'no gira',
                'motor no gira',
                'no hace nada',
                'llave y no hace nada',
                'giro la llave',
                'al girar la llave',
                'silencio al arrancar',
                'no se escucha nada',
                'solo hace click',
                'click seco',
                'arranque no responde',
                'no mueve el motor',
                'no intenta arrancar',
                'no acciona el arranque'
            ],
            'recomendacion' => 'comprobar motor de arranque, relé y conexiones electricas.'
        ],
        [
            'clave' => 'encendido',
            'titulo' => 'problema de bujias o encendido',
            'keywords' => [
                'bujias',
                'bobinas',
                'encendido',
                'fallo de encendido',
                'tirones',
                'da tirones',
                'tironea',
                'ratea',
                'rateo',
                'motor vibra',
                'vibra el motor',
                'vibraciones del motor',
                'ralenti inestable',
                'ralenti irregular',
                'falla al acelerar',
                'fallos al acelerar',
                'pierde potencia',
                'perdida de potencia',
                'perdida potencia',
                'explosiones',
                'petardea',
                'consume mucha gasolina',
                'huele a gasolina',
                'funciona a tirones'
            ],
            'recomendacion' => 'revisar bujias, bobinas y sistema de encendido.'
        ],
        [
            'clave' => 'filtros',
            'titulo' => 'filtro de aire o combustible obstruido',
            'keywords' => [
                'filtro',
                'filtro de aire',
                'filtro de combustible',
                'filtro sucio',
                'obstruido',
                'ahogado',
                'coche ahogado',
                'acelera poco',
                'le cuesta acelerar',
                'poca fuerza',
                'no tiene fuerza',
                'pierde fuerza',
                'pierde potencia',
                'perdida de potencia',
                'perdida potencia',
                'en carretera',
                'subiendo cuestas',
                'consume mucho',
                'consumo alto',
                'no responde al acelerar',
                'se queda sin fuerza',
                'le falta aire'
            ],
            'recomendacion' => 'revisar filtros y sustituirlos si estan sucios.'
        ],
        [
            'clave' => 'sobrecalentamiento',
            'titulo' => 'sobrecalentamiento del motor',
            'keywords' => [
                'temperatura alta',
                'temperatura muy alta',
                'sube la temperatura',
                'temperatura sube',
                'aguja temperatura',
                'aguja sube',
                'se calienta',
                'se calienta mucho',
                'motor caliente',
                'motor muy caliente',
                'sobrecalentamiento',
                'calienta en ciudad',
                'se calienta parado',
                'sale humo',
                'humo del motor',
                'humo capo',
                'anticongelante',
                'refrigerante',
                'pierde anticongelante',
                'pierde refrigerante',
                'pierde liquido refrigerante',
                'radiador',
                'termostato',
                'ventilador',
                'ventilador no funciona',
                'ventilador no salta',
                'hierve el agua',
                'vaso expansion',
                'nivel refrigerante bajo'
            ],
            'recomendacion' => 'parar el vehiculo y revisar refrigerante, radiador, termostato y ventilador.'
        ],
        [
            'clave' => 'frenos',
            'titulo' => 'fallo en frenos',
            'keywords' => [
                'frenos',
                'freno',
                'frena poco',
                'no frena bien',
                'le cuesta frenar',
                'pedal blando',
                'pedal de freno blando',
                'freno blando',
                'freno esta blando',
                'pedal se hunde',
                'ruido frenos',
                'ruido al frenar',
                'hace ruido al frenar',
                'chirrido',
                'chirrido al frenar',
                'pastillas',
                'discos',
                'liquido de frenos',
                'vibra al frenar',
                'vibra cuando freno',
                'freno fuerte',
                'cuando freno',
                'frenada larga',
                'olor a quemado al frenar'
            ],
            'recomendacion' => 'revisar pastillas, discos, liquido de frenos y posibles fugas.'
        ],
        [
            'clave' => 'embrague',
            'titulo' => 'problema de embrague',
            'keywords' => [
                'embrague',
                'patina',
                'embrague patina',
                'no entran marchas',
                'marchas entran mal',
                'las marchas entran mal',
                'cuesta meter marchas',
                'cuesta cambiar',
                'cambio duro',
                'rascan las marchas',
                'rasca al cambiar',
                'pedal duro',
                'pedal esta duro',
                'pedal de embrague duro',
                'olor quemado',
                'huele a quemado',
                'olor a embrague',
                'embrague quemado',
                'suben revoluciones y no anda',
                'revoluciones suben pero no acelera'
            ],
            'recomendacion' => 'revisar desgaste del embrague y sistema hidraulico.'
        ],
        [
            'clave' => 'neumaticos',
            'titulo' => 'neumaticos o alineacion incorrecta',
            'keywords' => [
                'neumaticos',
                'ruedas',
                'alineacion',
                'direccion',
                'volante',
                'vibra el volante',
                'volante vibra',
                'vibracion volante',
                'se va a un lado',
                'se va hacia un lado',
                'coche se va hacia un lado',
                'tira a la derecha',
                'tira a la izquierda',
                'desgaste irregular',
                'desgaste raro',
                'ruedas se desgastan raro',
                'desgasta una rueda',
                'presion ruedas',
                'presion baja',
                'equilibrado',
                'vibra en carretera',
                'vibra a velocidad',
                'direccion torcida'
            ],
            'recomendacion' => 'comprobar presion, equilibrado, alineacion y estado de los neumaticos.'
        ],
    ];

    // diagnosticar posibles causas basandose en las palabras clave encontradas en el texto del usuario
    public function diagnosticar(string $texto): array
    {
        $texto_normalizado = $this->normalizar($texto);
        $resultados = [];

        foreach ($this->causas as $causa) {
            $coincidencias = 0;

            foreach ($causa['keywords'] as $keyword) {
                if (str_contains($texto_normalizado, $this->normalizar($keyword))) {
                    $coincidencias++;
                }
            }

            if ($coincidencias > 0) {
                $confianza = min(100, (int) round(($coincidencias / 4) * 100));

                $resultados[] = [
                    'titulo' => t('diagnostico.causa.' . $causa['clave'] . '.titulo'),
                    'coincidencias' => $coincidencias,
                    'confianza' => $confianza,
                    'recomendacion' => t('diagnostico.causa.' . $causa['clave'] . '.recomendacion'),
                ];
            }
        }

        usort($resultados, function ($a, $b) {
            return $b['confianza'] <=> $a['confianza'];
        });

        return array_slice($resultados, 0, 3);
    }

    // normaliza el texto convirtiendolo a minusculas, eliminando acentos y espacios innecesarios para facilitar la comparacion con las palabras clave    
    private function normalizar(string $texto): string
    {
        $texto = mb_strtolower($texto, 'UTF-8');
        $texto = trim($texto);

        $acentos = ['á', 'é', 'í', 'ó', 'ú', 'ñ'];
        $sin_acentos = ['a', 'e', 'i', 'o', 'u', 'n'];

        return str_replace($acentos, $sin_acentos, $texto);
    }
}
