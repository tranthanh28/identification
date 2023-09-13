<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\Reaction\Reaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ReactionController extends Controller
{
    public function index()
    {
        $reaction = Reaction::orderBy('created_at', 'DESC')->paginate(10);

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
            'post_ids' => 'required|string',
        ]);
        $postIds = preg_split("/\r\n|\n|\r/", $request->post_ids);
        $fileName = $request->name . "_" . now()->timestamp.'.xlsx';
        $data = [
            'name' => $request->name,
            'post_ids' => json_encode($postIds),
            'file_name' => $fileName,
        ];

        $reaction = Reaction::create($data);
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
            'post_ids' => 'required|string',
        ]);

        $postIds = preg_split("/\r\n|\n|\r/", $request->post_ids);
        $fileName = $request->name . "_" . now()->timestamp.'.xlsx';
        $data = [
            'name' => $request->name,
            'post_ids' => json_encode($postIds),
            'file_name' => $fileName,
            'status' => 0,
        ];

        $reaction = Reaction::findorFail($id);
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
