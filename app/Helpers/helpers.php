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
