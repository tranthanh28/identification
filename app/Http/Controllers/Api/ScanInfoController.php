<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Filters\App\Monitaz\Reaction\ReactionFilter;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\ScanInfo\ScanInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ScanInfoController extends Controller
{
    public function __construct(ReactionFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index()
    {
        $scanInfo = ScanInfo::filters($this->filter)
            ->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $scanInfo
        ], 200);

    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'content_file' => 'required|string',
            'pass_day' => 'required|numeric',
        ]);
        $postIds = preg_split("/\r\n|\n|\r/", $request->content_file);
        $date = Carbon::now()->format("Y_m_d_H_i_s");
        $fileName = $request->name . "_" . $date .'.xlsx';
        $data = [
            'name' => $request->name,
            'content_file' => json_encode($postIds),
            'file_name' => $fileName,
            'pass_day' => $request->pass_day,
        ];

        $scanInfo = ScanInfo::create($data);
        $result = array_map(function ($item) {
            return [$item];
        }, $postIds);
        Excel::store(new ReactionExport($result), $fileName);
        return response()->json([
            'status' => true,
            'message' => 'created successfully',
            'data' => $scanInfo
        ], 200);

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'content_file' => 'required|string',
            'pass_day' => 'required|numeric',
        ]);
        $scanInfo = ScanInfo::findorFail($id);
        if ($scanInfo->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Update failed (status == 1)'
            ], 400);
        }

        $postIds = preg_split("/\r\n|\n|\r/", $request->content_file);
        $date = Carbon::now()->format("Y_m_d_H_i_s");
        $fileName = $request->name . "_" . $date .'.xlsx';
        $data = [
            'name' => $request->name,
            'content_file' => json_encode($postIds),
            'file_name' => $fileName,
            'pass_day' => $request->pass_day,
            'status' => 0,
        ];

        $scanInfo->update($data);

        $result = array_map(function ($item) {
            return [$item];
        }, $postIds);
        Excel::store(new ReactionExport($result), $fileName);
        return response()->json([
            'status' => true,
            'message' => 'update successfully',
            'data' => $scanInfo
        ], 200);

    }

    public function exportExcel(Request $request)
    {
        $this->validate($request, [
            'file_name' => 'required'
        ]);
        return Storage::download($request->file_name);
    }
}
