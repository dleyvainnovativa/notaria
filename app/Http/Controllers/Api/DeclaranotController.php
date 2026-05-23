<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;
use OpenAI;

class DeclaranotController extends Controller
{
    public function export(Request $request)
    {
        try {
            $request->validate([
                'data' => 'required'
            ]);


            $json = $request->input('data');
            $txtContent = $this->generateDeclaranotTXT($json);

            $fileName = 'declaranot_' . now()->format('Ymd_His') . '.txt';
            return response()->json([
                'success' => true,
                'data' => [
                    // "text" => $text,
                    "text" => $txtContent,
                    "fileName" => $fileName,
                ]
            ]);
            // return response($txtContent)
            //     ->header('Content-Type', 'text/plain')
            //     ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function extract(Request $request)
    {
        try {
            $request->validate([
                'escritura' => 'required|file|mimes:pdf,doc,docx|max:5120',
                'calculo' => 'required|file|mimes:pdf,doc,docx|max:5120',
            ]);
            $escritura = self::buildFile($request->file('escritura'), "declaranot");
            $calculo = self::buildFile($request->file('calculo'), "pagos");

            $schema_raw = self::getSchema();
            $schema = self::buildFormSchema();
            $json = array_merge($escritura, $calculo);

            return response()->json([
                'success' => true,
                'data' => [
                    // "text" => $text,
                    "json" => $json,
                    "schema" => $schema,
                    "schema_raw" => $schema_raw,
                ]
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private static function buildFile($file, $type)
    {
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());
        $text = '';
        if ($extension === 'docx') {
            $phpWord = IOFactory::load($path);
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
        } elseif ($extension === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();
        } else {
            throw new Exception("Unsupported file type");
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Unsupported file type'
            // ], 400);
        }
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        // $json = self::getAIJSON($text, $type);
        $json = read_json_file("app/public/results/$type.json");
        return $json;
    }

    private static function getSchema()
    {
        $basePath = storage_path('app/public/process/declaranot/');
        $schemaPath = $basePath . 'schema.json';
        if (
            !file_exists($schemaPath)
        ) {
            throw new \Exception('Missing AI process files');
        }
        $schema_json = (file_get_contents($schemaPath));
        $schema = injectCatalogs(json_decode($schema_json, true), "declaranot");
        return $schema;
    }


    private function buildFormSchema(): array
    {
        // Pre-fetch all catalogs used across the schema (one query each)
        $catalogInmuebles       = fetchCatalog("declaranot", 'catalogo_inmuebles');
        $catalogTiposPago       = fetchCatalog("declaranot", 'catalogo_tipos_pago');
        $catalogBancos          = fetchCatalog("declaranot", 'catalogo_bancos');
        $catalogTiposDomicilio  = fetchCatalog("declaranot", 'catalogo_tipos_domicilio');
        $catalogDatosInformativos = fetchCatalog("declaranot", 'catalogo_datos_informativos');

        return [

            // ── Scalar fields ─────────────────────────────────────────────────────

            'numero_escritura' => [
                'label'    => 'Número de Escritura',
                'type'     => 'text',
                'required' => true,
            ],

            'fecha_firma_escritura' => [
                'label'    => 'Fecha de Firma',
                'type'     => 'date',
                'required' => true,
            ],

            'tipo_inmueble' => [
                'label'    => 'Tipo de Inmueble',
                'type'     => 'select',
                'required' => true,
                'options'  => $catalogInmuebles,
            ],

            'especifica_inmueble' => [
                'label'       => 'Especificar Inmueble',
                'type'        => 'text',
                // Shown/required only when tipo_inmueble = "otros"
                'required_if' => ['tipo_inmueble' => 'otros'],
            ],

            'avaluo_inmueble' => [
                'label'    => 'Avalúo del Inmueble',
                'type'     => 'number',
                'required' => true,
            ],

            // ── Array: Pagos del inmueble ─────────────────────────────────────────

            'pagos_inmueble' => [
                'label'      => 'Pagos del Inmueble',
                'type'       => 'array',
                'itemSchema' => [
                    'monto' => [
                        'label'    => 'Monto',
                        'type'     => 'number',
                        'required' => true,
                    ],
                    'tipo' => [
                        'label'    => 'Tipo de Pago',
                        'type'     => 'select',
                        'required' => true,
                        'options'  => $catalogTiposPago,
                    ],
                    'institucion_financiera' => [
                        'label'       => 'Institución Financiera',
                        'type'        => 'select',
                        'options'     => $catalogBancos,
                        // Required when tipo is cheque or transferencia
                        'required_if' => ['tipo' => ['cheque', 'transferencia']],
                    ],
                    'numero_cuenta' => [
                        'label'       => 'Número de Cuenta',
                        'type'        => 'text',
                        'required_if' => ['tipo' => ['cheque', 'transferencia']],
                    ],
                    'otro' => [
                        'label'       => 'Otro (especificar)',
                        'type'        => 'text',
                        // Required when institucion_financiera = "999" (valor "Otro" en catálogo)
                        'required_if' => ['institucion_financiera' => ['999']],
                    ],
                ],
            ],

            // ── Array: Enajenantes ────────────────────────────────────────────────

            'enajenantes' => [
                'label'      => 'Enajenantes',
                'type'       => 'array',
                'itemSchema' => [
                    'tipo' => [
                        'label'    => 'Tipo',
                        'type'     => 'select',
                        'required' => true,
                        'options'  => $catalogTiposDomicilio,
                    ],
                    'rfc' => [
                        'label'      => 'RFC',
                        'type'       => 'text',
                        'required'   => true,
                        'validation' => ['format' => 'rfc'],
                    ],
                    'nombre' => [
                        'label'    => 'Nombre',
                        'type'     => 'text',
                        'required' => true,
                    ],
                    'apellido_paterno' => [
                        'label' => 'Apellido Paterno',
                        'type'  => 'text',
                    ],
                    'apellido_materno' => [
                        'label' => 'Apellido Materno',
                        'type'  => 'text',
                    ],
                    'curp' => [
                        'label'       => 'CURP',
                        'type'        => 'text',
                        'required_if' => ['tipo' => 'nacional'],
                        'validation'  => ['format' => 'curp'],
                    ],
                ],
            ],

            // ── Object: Datos informativos ────────────────────────────────────────
            // type=object → rendered as a single flat group (no Add / Remove)

            'datos_informativos' => [
                'label'      => 'Datos Informativos',
                'type'       => 'object',
                'itemSchema' => [
                    'ingresos_exentos' => [
                        'label'                  => 'Ingresos Exentos',
                        'type'                   => 'select',
                        'options'                => $catalogDatosInformativos,
                        'default_if_missing_label' => 'No',
                    ],
                    'monto' => [
                        'label'       => 'Monto',
                        'type'        => 'number',
                        'required_if' => ['ingresos_exentos' => '1'],
                    ],
                    'impuesto' => [
                        'label'       => 'Impuesto',
                        'type'        => 'number',
                        'required_if' => ['ingresos_exentos' => '1'],
                    ],
                ],
            ],

            // ── Array: Adquirientes ───────────────────────────────────────────────

            'adquirientes' => [
                'label'      => 'Adquirientes',
                'type'       => 'array',
                'itemSchema' => [
                    'tipo' => [
                        'label'   => 'Tipo',
                        'type'    => 'select',
                        'options' => $catalogTiposDomicilio,
                    ],
                    'rfc' => [
                        'label'      => 'RFC',
                        'type'       => 'text',
                        'required'   => true,
                        'validation' => ['format' => 'rfc'],
                    ],
                    'nombre' => [
                        'label' => 'Nombre',
                        'type'  => 'text',
                    ],
                    'apellido_paterno' => [
                        'label' => 'Apellido Paterno',
                        'type'  => 'text',
                    ],
                    'apellido_materno' => [
                        'label' => 'Apellido Materno',
                        'type'  => 'text',
                    ],
                    'curp' => [
                        'label'       => 'CURP',
                        'type'        => 'text',
                        'required_if' => ['tipo' => 'nacional'],
                        'validation'  => ['format' => 'curp'],
                    ],
                ],
            ],

            // ── Array: Pago ───────────────────────────────────────────────────────
            // total_isr_pagado is type=computed; JS evaluates formula with sibling values.

            'pago' => [
                'label'      => 'Pago',
                'type'       => 'array',
                'itemSchema' => [
                    'ingresos_enajenacion' => [
                        'label' => 'Ingresos por Enajenación',
                        'type'  => 'number',
                    ],
                    'ingresos_exentos' => [
                        'label' => 'Ingresos Exentos',
                        'type'  => 'number',
                    ],
                    'ingreso_sismo_2017' => [
                        'label' => 'Ingreso Sismo 2017',
                        'type'  => 'number',
                    ],
                    'deducciones_autorizadas' => [
                        'label' => 'Deducciones Autorizadas',
                        'type'  => 'number',
                    ],
                    'ganancia_perdida' => [
                        'label' => 'Ganancia / Pérdida',
                        'type'  => 'number',
                    ],
                    'years_adquisicion_venta' => [
                        'label' => 'Años entre Adquisición y Venta',
                        'type'  => 'number',
                    ],
                    'ganancia_acumulable' => [
                        'label' => 'Ganancia Acumulable',
                        'type'  => 'number',
                    ],
                    'ganancia_no_acumulable' => [
                        'label' => 'Ganancia No Acumulable',
                        'type'  => 'number',
                    ],
                    'isr_federacion' => [
                        'label' => 'ISR Federación',
                        'type'  => 'number',
                    ],
                    'numero_operacion_federacion' => [
                        'label' => 'Número de Operación Federación',
                        'type'  => 'number',
                    ],
                    'fecha_pago_federacion' => [
                        'label' => 'Fecha de Pago Federación',
                        'type'  => 'date',
                    ],
                    'isr_entidad' => [
                        'label' => 'ISR Entidad',
                        'type'  => 'number',
                    ],
                    'numero_operacion_entidad' => [
                        'label' => 'Número de Operación Entidad',
                        'type'  => 'number',
                    ],
                    'fecha_pago_entidad' => [
                        'label' => 'Fecha de Pago Entidad',
                        'type'  => 'date',
                    ],
                    'total_isr_pagado' => [
                        'label'   => 'Total ISR Pagado',
                        'type'    => 'computed',
                        'formula' => 'isr_federacion + isr_entidad',
                    ],
                ],
            ],

            // ── Scalar: Existe copropiedad ────────────────────────────────────────
            // Scalar select that can also be auto-derived from copropiedad.integrantes length.

            'existe_copropiedad' => [
                'label'                  => '¿Existe Copropiedad?',
                'type'                   => 'select',
                'options'                => $catalogDatosInformativos,
                'default_if_missing_label' => 'No',
                // JS hint: if copropiedad.integrantes.length > 0 → auto-select the "Sí" option
                'derive_from_array_length' => [
                    'path'        => 'copropiedad.integrantes',
                    'if_gt'       => 0,
                    'true_label'  => 'Sí',
                    'false_label' => 'No',
                ],
            ],

            // ── Object: Copropiedad ───────────────────────────────────────────────
            // type=object → single flat group.
            // integrantes is a nested array enabled when existe_copropiedad = "1".

            'copropiedad' => [
                'label'      => 'Copropiedad',
                'type'       => 'object',
                'itemSchema' => [
                    'integrantes' => [
                        'label'      => 'Integrantes',
                        'type'       => 'array',
                        // JS hint: show/enable this section only when existe_copropiedad = "1"
                        'enabled_if' => ['existe_copropiedad' => '1'],
                        'itemSchema' => [
                            'rfc' => [
                                'label' => 'RFC',
                                'type'  => 'text',
                            ],
                            'porcentaje' => [
                                'label' => 'Porcentaje (%)',
                                'type'  => 'number',
                            ],
                            'ingresos_enajenacion' => [
                                'label' => 'Ingresos por Enajenación',
                                'type'  => 'number',
                            ],
                            'deducciones_autorizadas' => [
                                'label' => 'Deducciones Autorizadas',
                                'type'  => 'number',
                            ],
                            'ganancia_perdida' => [
                                'label' => 'Ganancia / Pérdida',
                                'type'  => 'number',
                            ],
                            'ganancia_acumulable' => [
                                'label' => 'Ganancia Acumulable',
                                'type'  => 'number',
                            ],
                            'ganancia_no_acumulable' => [
                                'label' => 'Ganancia No Acumulable',
                                'type'  => 'number',
                            ],
                            'isr_federacion' => [
                                'label' => 'ISR Federación',
                                'type'  => 'number',
                            ],
                            'isr_entidad' => [
                                'label' => 'ISR Entidad',
                                'type'  => 'number',
                            ],
                        ],
                    ],
                ],
            ],

            // ── Object: Representante común ───────────────────────────────────────
            // type=object → single flat group (no Add / Remove).

            'representante_comun' => [
                'label'      => 'Representante Común',
                'type'       => 'object',
                'itemSchema' => [
                    'existe_representante_comun' => [
                        'label'                  => '¿Existe Representante Común?',
                        'type'                   => 'select',
                        'options'                => $catalogDatosInformativos,
                        'default_if_missing_label' => 'No',
                    ],
                    'rfc_representante' => [
                        'label'       => 'RFC del Representante',
                        'type'        => 'text',
                        'required_if' => ['existe_representante_comun' => '1'],
                    ],
                ],
            ],

        ];
    }

    private static function getAIJSON($text, $type)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));
        $basePath = storage_path("app/public/process/$type/");
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
        $schema_json = file_get_contents($schemaPath);
        $schema = json_encode(injectCatalogs(json_decode($schema_json, true), "$type"));
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


    private function generateDeclaranotTXT(array $json): string
    {
        $lines = [];

        // ── Helpers ───────────────────────────────────────────────────────────────

        // Return value or empty string if null
        $v = fn($value) => $value ?? '';

        // Format a date string to dd/mm/YYYY (returns '' if null/empty)
        $date = function ($value) {
            if (!$value) return '';
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        };

        // ── Line: Configuracion ───────────────────────────────────────────────────
        // Format: Configuracion:{year}|{clave_notaria}|{entidad}|{numero_notaria}|{fecha_firma}
        $lines[] =
            'Configuracion:2025|001|035|24|' .
            $date($json['fecha_firma_escritura']);

        // ── Line: 900001 — Datos de la operación ──────────────────────────────────
        // tipo_inmueble is already a numeric string (1–9) from the new schema
        $lines[] =
            '900001-DatosOperacion:' .
            $v($json['numero_escritura'])       . '|' .
            $date($json['fecha_firma_escritura']) . '|' .
            $v($json['tipo_inmueble'])          . '|' .   // e.g. "4" = Terreno
            $v($json['especifica_inmueble'])    . '|' .
            $v($json['avaluo_inmueble']);

        // ── Lines: 900002 — Pagos del inmueble (grid) ─────────────────────────────
        // tipo is already a numeric string (1–4); monto rounded to integer
        // institucion_financiera and numero_cuenta added; index 1-based
        foreach ($json['pagos_inmueble'] as $index => $pago) {
            $lines[] =
                '900002-DatosPago-grid:' .
                round($v($pago['monto']))               . '|' .
                $v($pago['tipo'])                        . '|' .   // "1"=Efectivo,"2"=Cheque,"3"=Transferencia,"9"=Otro
                $v($pago['institucion_financiera'])      . '|' .
                ($index + 1)                             . '|' .
                $v($pago['numero_cuenta']);
        }

        // ── Lines: 900003 — Enajenantes (grid) ────────────────────────────────────
        // tipo is already "1" (nacional) or "2" (extranjero)
        foreach ($json['enajenantes'] as $enajenante) {
            $lines[] =
                '900003-DatosEnajenante-grid:' .
                $v($enajenante['tipo'])              . '|' .
                $v($enajenante['rfc'])               . '|' .
                $v($enajenante['nombre'])            . '|' .
                $v($enajenante['apellido_paterno'])  . '|' .
                $v($enajenante['apellido_materno'])  . '|' .
                $v($enajenante['curp'])              .
                '|||||';
        }

        // ── Line: 900004 — Datos informativos ─────────────────────────────────────
        // datos_informativos is now a plain object (not an array)
        // ingresos_exentos: "1"=Sí, "2"=No
        $info = $json['datos_informativos'] ?? null;
        if ($info && isset($info['ingresos_exentos'])) {
            $lines[] =
                '900004-DatosInformativos:' .
                $v($info['ingresos_exentos']) . '|' .   // "1" or "2"
                $v($info['monto'])            . '|' .
                $v($info['impuesto']);
        } else {
            $lines[] = '900004-DatosInformativos:2||';
        }

        // ── Lines: 900005 — Adquirientes (grid) ───────────────────────────────────
        foreach ($json['adquirientes'] as $adquiriente) {
            $lines[] =
                '900005-DatosAdquiriente-grid:' .
                $v($adquiriente['tipo'])             . '|' .
                $v($adquiriente['rfc'])              . '|' .
                $v($adquiriente['nombre'])           . '|' .
                $v($adquiriente['apellido_paterno']) . '|' .
                $v($adquiriente['apellido_materno']) . '|' .
                $v($adquiriente['curp'])             .
                '|||||';
        }

        // ── Lines: 900006 — Datos de pago / ISR (grid) ───────────────────────────
        foreach ($json['pago'] as $pago) {
            $lines[] =
                '900006-DatosPago-grid:' .
                $v($pago['ingresos_enajenacion'])        . '|' .
                $v($pago['ingresos_exentos'])            . '|' .
                $v($pago['ingreso_sismo_2017'])          . '|' .
                $v($pago['deducciones_autorizadas'])     . '|' .
                $v($pago['ganancia_perdida'])            . '|' .
                $v($pago['years_adquisicion_venta'])     . '|' .
                $v($pago['ganancia_acumulable'])         . '|' .
                $v($pago['ganancia_no_acumulable'])      . '|' .
                $v($pago['isr_federacion'])              . '|' .
                $v($pago['numero_operacion_federacion']) . '|' .
                $date($pago['fecha_pago_federacion'])    . '|' .
                $v($pago['isr_entidad'])                 . '|' .
                $v($pago['numero_operacion_entidad'])    . '|' .
                $date($pago['fecha_pago_entidad'])       . '|' .
                $v($pago['total_isr_pagado']);
        }

        // ── Lines: 900010 / 900013 / 900009 / 900007 / 900011 — Copropiedad ───────
        // existe_copropiedad is now a top-level scalar ("1"=Sí, "2"=No)
        // copropiedad is now an object with integrantes[] nested inside
        // representante_comun is now its own top-level object

        $existeCopropiedad  = $json['existe_copropiedad'] ?? '2';
        $copropiedadObj     = $json['copropiedad']        ?? null;
        $integrantes        = $copropiedadObj['integrantes'] ?? [];
        $representanteObj   = $json['representante_comun'] ?? null;

        $lines[] =
            '900010-IngresoCopropiedadOSucesion:' .
            $v($existeCopropiedad);                          // "1" or "2"

        $existeRepresentante = $representanteObj['existe_representante_comun'] ?? '2';
        $rfcRepresentante    = $representanteObj['rfc_representante'] ?? null;

        $lines[] =
            '900013-PreguntaExisteRepresentanteLegal:' .
            $v($existeRepresentante);                        // "1" or "2"

        $lines[] =
            '900009-RepresentanteLegal:' .
            $v($rfcRepresentante);

        if (!empty($integrantes)) {
            $totalPorcentaje = 0;
            foreach ($integrantes as $integrante) {
                $totalPorcentaje += (float) ($integrante['porcentaje'] ?? 0);
                $lines[] =
                    '900007-DatosCopropiedad-grid:' .
                    $v($integrante['rfc'])                   . '|' .
                    $v($integrante['porcentaje'])            . '|' .
                    $v($integrante['ingresos_enajenacion'])  . '|' .
                    $v($integrante['deducciones_autorizadas']) . '|' .
                    $v($integrante['ganancia_perdida'])      . '|' .
                    $v($integrante['ganancia_acumulable'])   . '|' .
                    $v($integrante['ganancia_no_acumulable']) . '|' .
                    $v($integrante['isr_federacion'])        . '|' .
                    $v($integrante['isr_entidad']);
            }
            $lines[] = '900011-TotalPorcentajeCopropiedad:' . $totalPorcentaje;
        } else {
            $lines[] = '900007-DatosCopropiedad-grid:||||||||';
            $lines[] = '900011-TotalPorcentajeCopropiedad:';
        }

        return implode("\n", $lines);
    }
}
