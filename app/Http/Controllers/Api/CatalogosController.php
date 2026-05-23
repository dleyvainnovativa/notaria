<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class CatalogosController extends Controller
{


    public function catalogos()
    {
        $response = read_json_file('app/private/catalogos.json');
        $catalogs = [];
        if (!isset($response['Xml']) || !is_array($response['Xml'])) {
            return [];
        }
        foreach ($response['Xml'] as $index => $item) {
            if (empty($item) || !is_string($item)) {
                continue;
            }
            $parsed = $this->parseSATXmlItem($item);
            if ($parsed !== null) {
                $catalogs[] = [
                    'index' => $index,
                    'data' => $parsed
                ];
            }
        }
        $all_catalogs = [];
        $source = $catalogs[4]['data']['catalogo'] ?? [];
        foreach ($source as $catalog) {
            $catalogId = $catalog['@attributes']['id'] ?? null;
            $catalogName = $catalog['@attributes']['nombre'] ?? null;
            $items = [];
            $elements = $catalog['elemento'] ?? [];
            if (isset($elements['@attributes'])) {
                $elements = [$elements];
            }
            foreach ($elements as $element) {
                $attributes = $element['@attributes'] ?? [];
                $texto = $attributes['texto'] ?? '';
                if ($attributes['valor'] == "0") {
                    $texto = "Seleccionar opción";
                }
                if (strlen($attributes["valor"]) > 0) {
                    $items[] = [
                        'label'  => $texto,
                        'value' => $attributes['valor'] ?? '',
                    ];
                }
            }
            $catalogMap = [
                '141' => 'catalogo_inmuebles',
                '142' => 'catalogo_tipos_pago',
                '143' => 'catalogo_nacionalidad',
                '145' => 'catalogo_bancos',
                '156' => 'catalogo_datos_informativos',
                '158' => 'catalogo_tipos_domicilio',
            ];

            if (isset($catalogMap[$catalogId])) {

                $filename = $catalogMap[$catalogId];

                // Save JSON file
                Storage::disk('public')->put(
                    "catalogs/declaranot/{$filename}.json",
                    json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                );

                $all_catalogs[] = [
                    'catalog' => [
                        'id'       => $catalogId,
                        'name'     => $catalogName,
                        'filename' => $catalogMap[$catalogId],
                    ],
                    'items' => $items
                ];
            }
        }
        // dd($all_catalogs);
        return $all_catalogs;
    }

    private function parseSATXmlItem(string $value): mixed
    {
        $value = trim($value);
        $json = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        }
        $decoded = base64_decode($value, true);

        if ($decoded !== false) {
            $decoded = trim($decoded);
            $json = json_decode($decoded, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $json;
            }
            if (str_starts_with($decoded, '<')) {

                libxml_use_internal_errors(true);

                $xml = simplexml_load_string(
                    $decoded,
                    'SimpleXMLElement',
                    LIBXML_NOCDATA
                );

                if ($xml !== false) {

                    return json_decode(
                        json_encode($xml),
                        true
                    );
                }
            }
        }
        if (str_starts_with($value, '<')) {

            libxml_use_internal_errors(true);

            $xml = simplexml_load_string(
                $value,
                'SimpleXMLElement',
                LIBXML_NOCDATA
            );

            if ($xml !== false) {

                return json_decode(
                    json_encode($xml),
                    true
                );
            }
        }
        return null;
    }


    public function exportCatalogsToExcel()
    {
        $catalogs = read_json_file('app/private/all_catalogs.json');
        $exportPath = storage_path('app/public/catalogs');

        if (!file_exists($exportPath)) {
            mkdir($exportPath, 0777, true);
        }

        foreach ($catalogs as $catalog) {

            $catalogName = $catalog['catalog']['name'] ?? 'Catalog';
            $catalogId   = $catalog['catalog']['id'] ?? '0';

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('A1', 'Name');
            $sheet->setCellValue('B1', 'Value');

            $row = 2;

            foreach ($catalog['items'] as $item) {

                $sheet->setCellValue('A' . $row, $item['name']);
                $sheet->setCellValue('B' . $row, $item['value']);

                $row++;
            }

            $filename = $catalogId . '_' .
                preg_replace('/[^A-Za-z0-9_\-]/', '_', $catalogName)
                . '.xlsx';

            $writer = new Xlsx($spreadsheet);

            $writer->save($exportPath . '/' . $filename);
        }

        return 'Done';
    }
}
