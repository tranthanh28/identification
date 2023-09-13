<?php

namespace App\Http\Controllers\Monitaz;

use App\Http\Controllers\Controller;

class ScanInfoController extends Controller
{
    public function index()
    {
        return view('monitaz.scan-info');
    }
}
