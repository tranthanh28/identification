<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Filters\App\Monitaz\Reaction\ReactionFilter;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\ScanGroup\ScanGroup;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ScanGroup as ScanGroupJob;

class ScanGroupController extends Controller
{
    public function __construct(ReactionFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index()
    {
        $reaction = ScanGroup::filters($this->filter)
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
            'content_file' => 'required|string',
            'pass_day' => 'required|numeric',
        ]);
        $keywords = preg_split("/\r\n|\n|\r/", $request->content_file);
        $fileName = $request->name . "_" . now()->timestamp.'.xlsx';
        $data = [
            'name' => $request->name,
            'content_file' => json_encode($keywords),
            'file_name' => $fileName,
            'pass_day' => $request->pass_day,
        ];

        $reaction = ScanGroup::create($data);

        ScanGroupJob::dispatch($keywords, $fileName, $reaction);

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
            'content_file' => 'required|string',
            'pass_day' => 'required|numeric',
        ]);

        $keywords = preg_split("/\r\n|\n|\r/", $request->content_file);
        $fileName = $request->name . "_" . now()->timestamp.'.xlsx';
        $data = [
            'name' => $request->name,
            'content_file' => json_encode($keywords),
            'file_name' => $fileName,
            'pass_day' => $request->pass_day,
            'status' => 0,
        ];

        $reaction = ScanGroup::findorFail($id);
        $reaction->update($data);

        ScanGroupJob::dispatch($keywords, $fileName, $reaction);

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
        return Storage::download('/groups_xlsx/' . $request->file_name);
    }
}
