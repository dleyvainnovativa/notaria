<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Api\QRController;
use App\Http\Controllers\Controller;
use App\Models\Memorial;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.pages.dashboard');
    }
}
