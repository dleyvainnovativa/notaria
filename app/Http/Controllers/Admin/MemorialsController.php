<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Memorial;
use App\Models\User;
use Illuminate\Http\Request;

class MemorialsController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(session('user_id'));
        $owned = $user->memorials()->get();
        $invited = Memorial::whereHas('invitations', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        $memories = $owned->merge($invited)->unique('id')->values();


        $views = 0;
        foreach ($memories as $key => $memory) {
            if (isset($memory->qrCode)) {
                $views += $memory->qrCode->visits->count() ?? 0;
            }
        }
        $data["visits"] = $views;
        $data["memorials"] = $memories->count();
        return view('admin.pages.memorials', $data);
    }
}
