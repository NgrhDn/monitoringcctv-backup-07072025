<?php

namespace App\Http\Controllers;

use App\Models\CCTV;
use App\Http\Controllers\cctvController;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $cctv = CCTV::all();
        return view('welcome', compact('cctv'));
    }
}
