<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Memorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemorialParagraphController extends Controller
{
    /**
     * GET /api/{memorial}/life
     */
    public function index(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'id' => $memorial->id,
            'paragraphs' => $memorial->paragraphs()
                ->orderBy('position')
                ->get(['id', 'content', 'position']),
        ]);
    }

    /**
     * POST /api/{memorial}/life
     */
    public function update(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $request->validate([
            'life_text' => ['nullable', 'string'],
        ]);

        // Split textarea into paragraphs (blank-line friendly)
        $paragraphs = collect(
            preg_split("/\r\n\s*\r\n|\n\s*\n/", trim($request->life_text ?? ''))
        )->filter()->values();

        DB::transaction(function () use ($memorial, $paragraphs) {
            $memorial->paragraphs()->delete();

            foreach ($paragraphs as $index => $text) {
                $memorial->paragraphs()->create([
                    'content' => $text,
                    'position' => $index,
                ]);
            }
        });

        return response()->json([
            'message' => 'Historia actualizada correctamente',
            'paragraphs' => $memorial->paragraphs()
                ->orderBy('position')
                ->get(['id', 'content', 'position']),
        ]);
    }
}
