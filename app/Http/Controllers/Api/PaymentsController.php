<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $payments = [];
        $payments = $user->payments()
            ->with(['memorial']) // you can also add 'user' if needed
            ->get();
        return response()->json($payments);
    }

    public static function buildFolio($id)
    {
        $folio = "$id";
        for ($i = strlen($id); $i < 4; $i++) {
            $folio = "0" . $folio;
        }
        return "R-$folio";
    }
}
