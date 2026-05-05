<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Memorial;
use App\Models\MediaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * GET /api/{memorial}/gallery
     */
    public function index(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'images' => $memorial->mediaItems()
                ->where('type', 'photo')
                ->orderBy('position', 'asc')
                ->get(),
        ]);
    }

    /**
     * POST /api/{memorial}/gallery
     */
    public function store(Request $request, Memorial $memorial)
    {
        $request->validate([
            'images'   => ['required', 'array'],
            'images.*' => ['image', 'max:5120'], // 5MB
        ]);
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        DB::transaction(function () use ($request, $memorial) {
            foreach ($request->file('images') as $image) {

                $path = $image->store(
                    "memorials/{$memorial->id}/photos",
                    'public'
                );

                $memorial->mediaItems()->create([
                    'type'          => 'photo',
                    'url'           => 'storage/' . $path,
                    'thumbnail_url' => 'storage/' . $path, // later you can generate thumbs
                    'caption'       => null,
                ]);
            }
        });

        return response()->json([
            'message' => 'Images uploaded successfully',
        ], 201);
    }

    /**
     * DELETE /api/{memorial}/gallery/{mediaItem}
     */
    public function destroy(Request $request, Memorial $memorial, MediaItem $mediaItem)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($mediaItem->memorial_id !== $memorial->id) {
            abort(403);
        }

        // Delete physical file
        $this->deleteFileFromUrl($mediaItem->url);

        if ($mediaItem->thumbnail_url) {
            $this->deleteFileFromUrl($mediaItem->thumbnail_url);
        }

        $mediaItem->delete();

        return response()->json([
            'message' => 'Image deleted successfully',
        ]);
    }

    public function updateOrder(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        foreach ($request->items as $item) {
            MediaItem::where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Helper to delete storage file using full URL
     */
    private function deleteFileFromUrl(string $url): void
    {
        $path = str_replace(asset('storage') . '/', '', $url);
        Storage::disk('public')->delete($path);
    }
}
