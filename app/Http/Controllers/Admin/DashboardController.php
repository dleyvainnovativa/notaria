<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\QRController;
use App\Http\Controllers\Controller;
use App\Models\Memorial;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, Memorial $memorial)
    {
        $user = $request->route('current_user');
        $memorial->load([
            'user',
            'qrCode',
            'mediaItems',
            'tributes',
            'timelineEvents',
            'paragraphs',
            'payment',
        ]);
        if (!$memorial->qrCode) {
            QRController::create($memorial->id);
            $memorial->load([
                'user',
                'qrCode',
                'mediaItems',
                'tributes',
                'timelineEvents',
                'paragraphs',
                'payment',
            ]);
        };
        return view('admin.pages.dashboard', [
            'memorial' => $memorial,
            'user' => $user,
            'memorial_slug' => $memorial->slug,
            'qr' => $memorial->qrCode->uuid,
            'tributes' => $memorial->tributes->count(),
            'visits' => $memorial->qrCode->visits->count()
        ]);
    }
}
