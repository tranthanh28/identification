<?php

namespace App\Http\Controllers\Monitaz;

use App\Http\Controllers\Controller;

class ScanGroupController extends Controller
{
    public function index()
    {
        return view('monitaz.scan-group');
    }
}
