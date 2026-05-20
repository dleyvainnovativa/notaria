<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DeclaranotController extends Controller
{
    public function index()
    {
        $user = User::find(session('user_id'));
        $data["user"] = $user;
        return view('admin.pages.declaranot', $data);
    }
}
