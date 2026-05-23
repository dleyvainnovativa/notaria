<?php

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;


if (!function_exists('ErrorResponse')) {
    function ErrorResponse($code = 400, $message = 'Mensaje de error por default', $location = null, $data = null)
    {
        $errorPayload = [
            'code' => $code,
            'message' => $message,
            'location' => $location,
            'data' => $data,
        ];
        Log::error('ErrorResponse', $errorPayload);
        return response()->json($errorPayload, $code);
    }
}
if (!function_exists('SuccessResponse')) {
    function SuccessResponse($code = 200, $message = 'Mensaje exitoso por default', $location = null, $data = null)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'location' => $location,
            'data' => $data,
        ], $code);
    }
}

function formatDatetime($datetime)
{
    Carbon::setLocale('es');
    $date = Carbon::parse($datetime);
    $formatted = $date->translatedFormat('j \d\e F \d\e Y \a \l\a\s g:i A');
    return $formatted;
}

function generateID($length = 8)
{
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $id = '';

    for ($i = 0; $i < $length; $i++) {
        $id .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $id;
}

if (!function_exists('getJsonData')) {
    function getJsonData($file = null)
    {
        $path = resource_path("json/$file");
        if (!File::exists($path)) {
            abort(404, 'Data file not found.');
        }
        $jsonContent = File::get($path);
        $data = json_decode($jsonContent, true);
        return $data;
    }
}

if (!function_exists('injectCatalogs')) {
    function injectCatalogs(array $schema, string $type): array
    {
        $catalogPath = storage_path("app/public/catalogs/$type/");
        $walk = function (&$item) use (&$walk, $catalogPath) {
            if (is_array($item) && isset($item['source'])) {
                $catalogName = $item['source'];
                $file = $catalogPath . $catalogName . '.json';
                if (file_exists($file)) {
                    $catalogContent = json_decode(
                        file_get_contents($file),
                        true
                    );
                    $item['allowed_values'] = $catalogContent;
                } else {
                    $item['allowed_values'] = [];
                }
            }
            if (is_array($item)) {
                foreach ($item as &$child) {
                    $walk($child);
                }
            }
        };
        $walk($schema);
        return $schema;
    }
}

if (!function_exists('fetchCatalog')) {
    function fetchCatalog(string $type, string $catalog): array
    {
        $catalogPath = storage_path("app/public/catalogs/$type/");
        $file = $catalogPath . $catalog . '.json';
        if (file_exists($file)) {
            $catalogContent = json_decode(
                file_get_contents($file),
                true
            );
        } else {
            $catalogContent = null;
        }
        return $catalogContent;
    }
}

if (!function_exists('read_json_file')) {

    function read_json_file($relativePath)
    {
        $path = storage_path($relativePath);

        if (!file_exists($path)) {
            throw new \Exception("JSON file not found: {$path}");
        }

        $content = file_get_contents($path);

        $json = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Invalid JSON in file: {$path}");
        }

        return $json;
    }
}
