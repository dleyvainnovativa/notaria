<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use OpenAI;

class DeclaranotController extends Controller
{
    public function extract()
    {
        $path = storage_path('app/public/results/declaranot.json');
        $data = json_decode(file_get_contents($path), true);
        if (!isset($data['annexed'])) {
            abort(404, 'Annexed documents not available');
        }
        sleep(2);
        return response()->json([
            'success' => true,
            'data' => $data,
            // 'ai_result' => $result
        ]);
    }


    public function extract_prod()
    {
        try {
            $path = storage_path('app/public/words/file3.docx');
            if (!file_exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }
            $phpWord = IOFactory::load($path);
            $text = '';
            foreach ($phpWord->getSections() as $section) {
                $elements = $section->getElements();
                foreach ($elements as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                    if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                        foreach ($element->getRows() as $row) {
                            foreach ($row->getCells() as $cell) {
                                foreach ($cell->getElements() as $cellElement) {
                                    if (method_exists($cellElement, 'getText')) {
                                        $text .= $cellElement->getText() . ' ';
                                    }
                                }
                            }
                            $text .= "\n";
                        }
                    }
                }
            }
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);
            // $result = self::getAIJSON($text);
            return response()->json([
                'success' => true,
                'text' => $text,
                // 'ai_result' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    private function getAIJSON($text)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));
        $basePath = storage_path('app/public/process/declaranot/');
        $promptPath = $basePath . 'prompt.txt';
        $schemaPath = $basePath . 'schema.json';
        $outputPath = $basePath . 'output.json';
        if (
            !file_exists($promptPath) ||
            !file_exists($schemaPath) ||
            !file_exists($outputPath)
        ) {
            throw new \Exception('Missing AI process files');
        }
        $systemPrompt = file_get_contents($promptPath);
        $schema = file_get_contents($schemaPath);
        $outputExample = file_get_contents($outputPath);
        $userPrompt = "
        SCHEMA:
        $schema

        EXPECTED OUTPUT EXAMPLE:
        $outputExample

        DOCUMENT:
        $text
        ";
        // dd($userPrompt);
        $response = $client->chat()->create([
            'model' => 'gpt-4.1-mini',
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => $userPrompt
                ]
            ]
        ]);
        $content = $response->choices[0]->message->content ?? '';
        $content = trim($content);
        $content = str_replace('```json', '', $content);
        $content = str_replace('```', '', $content);
        $content = trim($content);
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {

            throw new \Exception(
                'Invalid JSON returned from AI: ' .
                    json_last_error_msg()
            );
        }

        Log::info($decoded);
        return $decoded;
    }
    public function generate()
    {
        $jsonPath = storage_path('app/public/process/declaranot/result.json');
        if (!file_exists($jsonPath)) {
            return response()->json([
                'success' => false,
                'message' => 'JSON file not found'
            ], 404);
        }
        $jsonContent = file_get_contents($jsonPath);
        $json = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid JSON in file: ' . json_last_error_msg()
            ], 500);
        }
        $txtContent = $this->generateDeclaranotTXT($json);
        $fileName = 'declaranot_' . now()->format('Ymd_His') . '.txt';
        $tempPath = storage_path('app/temp/' . $fileName);
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }
        file_put_contents($tempPath, $txtContent);
        return response()->download(
            $tempPath,
            $fileName,
            [
                'Content-Type' => 'text/plain'
            ]
        )->deleteFileAfterSend(true);
    }

    private function generateDeclaranotTXT($json)
    {
        $lines = [];
        $tipoInmuebleMap = [
            'casa_habitacion' => 1,
            'comercial' => 2,
            'industrial' => 3,
            'terrenos' => 4,
            'otros' => 5,
        ];

        $tipoPagoMap = [
            'efectivo' => 1,
            'cheque' => 2,
            'transferencia' => 3,
            'otro' => 4,
        ];

        $siNoMap = [
            'si' => 1,
            'no' => 2,
        ];
        $v = function ($value) {
            return $value ?? '';
        };
        $date = function ($value) {
            if (!$value) {
                return '';
            }
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        };
        $lines[] =
            'Configuracion:2025|001|035|24|' .
            $date($json['fecha_firma_escritura']);
        $lines[] =
            '900001-DatosOperacion:' .
            $v($json['numero_escritura']) . '|' .
            $date($json['fecha_firma_escritura']) . '|' .
            ($tipoInmuebleMap[$json['tipo_inmueble']] ?? '') . '|' .
            $v($json['especifica_inmueble']) . '|' .
            $v($json['avaluo_inmueble']);
        foreach ($json['pagos_inmueble'] as $index => $pago) {
            $lines[] =
                '900002-DatosPago-grid:' .
                round($v($pago['monto'])) . '|' .
                ($tipoPagoMap[$pago['tipo']] ?? '') . '||' .
                ($index + 1) . '|';
        }
        foreach ($json['enajenantes'] as $enajenante) {

            $lines[] =
                '900003-DatosEnajenante-grid:' .
                ($enajenante['tipo'] === 'nacional' ? '1' : '2') . '|' .
                $v($enajenante['rfc']) . '|' .
                $v($enajenante['nombre']) . '|' .
                $v($enajenante['apellido_paterno']) . '|' .
                $v($enajenante['apellido_materno']) . '|' .
                $v($enajenante['curp']) .
                '|||||';
        }
        if (count($json['datos_informativos']) > 0) {
            $info = $json['datos_informativos'][0];
            $lines[] =
                '900004-DatosInformativos:' .
                ($info['ingresos_exentos'] === 'si' ? '1' : '2') . '|' .
                $v($info['monto']) . '|' .
                $v($info['impuesto']);
        } else {
            $lines[] = '900004-DatosInformativos:2||';
        }
        foreach ($json['adquirientes'] as $adquiriente) {
            $lines[] =
                '900005-DatosAdquiriente-grid:' .
                ($adquiriente['tipo'] === 'nacional' ? '1' : '2') . '|' .
                $v($adquiriente['rfc']) . '|' .
                $v($adquiriente['nombre']) . '|' .
                $v($adquiriente['apellido_paterno']) . '|' .
                $v($adquiriente['apellido_materno']) . '|' .
                $v($adquiriente['curp']) .
                '|||||';
        }
        foreach ($json['pago'] as $pago) {
            $lines[] =
                '900006-DatosPago-grid:' .
                $v($pago['ingresos_enajenacion']) . '|' .
                $v($pago['ingresos_exentos']) . '|' .
                $v($pago['ingreso_sismo_2017']) . '|' .
                $v($pago['deducciones_autorizadas']) . '|' .
                $v($pago['ganancia_perdida']) . '|' .
                $v($pago['years_adquisicion_venta']) . '|' .
                $v($pago['ganancia_acumulable']) . '|' .
                $v($pago['ganancia_no_acumulable']) . '|' .
                $v($pago['isr_federacion']) . '|' .
                $v($pago['numero_operacion_federacion']) . '|' .
                $date($pago['fecha_pago_federacion']) . '|' .
                $v($pago['isr_entidad']) . '|' .
                $v($pago['numero_operacion_entidad']) . '|' .
                $date($pago['fecha_pago_entidad']) . '|' .
                $v($pago['total_isr_pagado']);
        }

        $copropiedad = $json['copropiedad'][0] ?? null;
        if ($copropiedad) {
            $lines[] =
                '900010-IngresoCopropiedadOSucesion:' .
                ($copropiedad['existe'] === 'si' ? '1' : '2');
            $lines[] =
                '900013-PreguntaExisteRepresentanteLegal:' .
                ($copropiedad['representante_comun'] === 'si' ? '1' : '2');
            $lines[] =
                '900009-RepresentanteLegal:' .
                $v($copropiedad['rfc_representante']);
            if (!empty($copropiedad['integrantes'])) {
                $totalPorcentaje = 0;
                foreach ($copropiedad['integrantes'] as $integrante) {
                    $totalPorcentaje += $integrante['porcentaje'];
                    $lines[] =
                        '900007-DatosCopropiedad-grid:' .
                        $v($integrante['rfc']) . '|' .
                        $v($integrante['porcentaje']) . '|' .
                        $v($integrante['ingresos_enajenacion']) . '|' .
                        $v($integrante['deducciones_autorizadas']) . '|' .
                        $v($integrante['ganancia_perdida']) . '|' .
                        $v($integrante['ganancia_acumulable']) . '|' .
                        $v($integrante['ganancia_no_acumulable']) . '|' .
                        $v($integrante['isr_federacion']) . '|' .
                        $v($integrante['isr_entidad']);
                }
                $lines[] =
                    '900011-TotalPorcentajeCopropiedad:' .
                    $totalPorcentaje;
            } else {
                $lines[] = '900007-DatosCopropiedad-grid:||||||||';
                $lines[] = '900011-TotalPorcentajeCopropiedad:';
            }
        }
        return implode("\n", $lines);
    }
}
