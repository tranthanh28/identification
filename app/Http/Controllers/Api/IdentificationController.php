<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Filters\App\Monitaz\Identification\IdentificationFilter;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\Identification\Identification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ScanGroup as ScanGroupJob;

class IdentificationController extends Controller
{
    public function __construct(IdentificationFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index()
    {
        $data = Identification::filters($this->filter)
            ->with('identificationDetail')->paginate(10);

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $data
        ], 200);

    }
    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {

    }

}
