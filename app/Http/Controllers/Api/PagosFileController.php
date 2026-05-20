<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;

use OpenAI;

class PagosFileController extends Controller
{
    public function extract()
    {
        try {
            $path = storage_path('app/public/pdfs/file2.pdf');
            if (!file_exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
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

                return response()->json([
                    'success' => false,
                    'message' => 'Unsupported file type'
                ], 400);
            }
            $text = preg_replace('/\s+/', ' ', $text);
            $text = trim($text);
            $json = $this->getAIJSON($text);
            return response()->json([
                'success' => true,
                'text' => $text,
                'data' => $json
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
        $basePath = storage_path('app/public/process/pagos/');
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
}
