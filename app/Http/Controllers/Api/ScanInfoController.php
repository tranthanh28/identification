<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Filters\App\Monitaz\Reaction\ReactionFilter;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\ScanInfo\ScanInfo;
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
        $reaction = ScanInfo::filters($this->filter)
            ->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $reaction
        ], 200);

    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'list_fb_ids' => 'required|string',
            'pass_day' => 'required|numeric',
        ]);
        $postIds = preg_split("/\r\n|\n|\r/", $request->list_fb_ids);
        $fileName = $request->name . "_" . now()->timestamp.'.xlsx';
        $data = [
            'name' => $request->name,
            'list_fb_ids' => json_encode($postIds),
            'file_name' => $fileName,
            'pass_day' => $request->pass_day,
        ];

        $reaction = ScanInfo::create($data);
        $result = array_map(function ($item) {
            return [$item];
        }, $postIds);
        Excel::store(new ReactionExport($result), $fileName);
        return response()->json([
            'status' => true,
            'message' => 'created successfully',
            'data' => $reaction
        ], 200);

    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'list_fb_ids' => 'required|string',
            'pass_day' => 'required|numeric',
        ]);

        $postIds = preg_split("/\r\n|\n|\r/", $request->list_fb_ids);
        $fileName = $request->name . "_" . now()->timestamp.'.xlsx';
        $data = [
            'name' => $request->name,
            'list_fb_ids' => json_encode($postIds),
            'file_name' => $fileName,
            'pass_day' => $request->pass_day,
            'status' => 0,
        ];

        $reaction = ScanInfo::findorFail($id);
        $reaction->update($data);

        $result = array_map(function ($item) {
            return [$item];
        }, $postIds);
        Excel::store(new ReactionExport($result), $fileName);
        return response()->json([
            'status' => true,
            'message' => 'update successfully',
            'data' => $reaction
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
