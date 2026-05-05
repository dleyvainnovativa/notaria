<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Memorial;
use App\Models\QrCode as QrCodeModel;
use App\Models\Visit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class QRController extends Controller
{

    public function index(Request $request, QrCodeModel $qr)
    {
        $memorial = $qr->memorial;

        if (!$memorial) {
            abort(404);
        }

        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // Prevent duplicate visits (last 10 minutes)
        $alreadyVisited = Visit::where('qr_code_id', $qr->id)
            ->where('ip_address', $ip)
            ->where('user_agent', $userAgent)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->exists();

        if (!$alreadyVisited) {
            Visit::create([
                'qr_code_id' => $qr->id,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ]);
        }

        $memorial->load([
            'user',
            'qrCode',
            'mediaItems',
            'tributes',
            'timelineEvents',
            'paragraphs',
            'payment',
        ]);

        return redirect()->route('memory', $memorial->slug);
    }

    public function download(QrCodeModel $qr)
    {
        $url = route('qr.index', $qr->uuid);

        $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(400)
            ->generate($url);

        $memorial = $qr->memorial;
        $now = date("yy_m_d_ss");
        $fileName = "QR_{$memorial->deceased_name}-{$now}.svg";

        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', "attachment; filename=\"{$fileName}\"");
    }

    public static function create($memorial_id)
    {
        $qr = QrCodeModel::create([
            'memorial_id'       => $memorial_id,
            'uuid'          => (string) Str::uuid(),
            'status'        => 'active',
        ]);
        return $qr;
    }
}
