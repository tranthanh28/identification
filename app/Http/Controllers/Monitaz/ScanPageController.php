<?php

namespace App\Http\Controllers\Monitaz;

use App\Http\Controllers\Controller;

class ScanPageController extends Controller
{
    public function index()
    {
        return view('monitaz.scan-page');
    }
}
