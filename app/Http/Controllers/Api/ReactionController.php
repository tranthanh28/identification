<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\Reaction\Reaction;
use App\Filters\App\Monitaz\Reaction\ReactionFilter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ReactionController extends Controller
{


    public function __construct(ReactionFilter $filter)
    {
        $this->filter = $filter;
    }

    public function index()
    {
        $reaction = Reaction::filters($this->filter)
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
            'post_ids' => 'required|string',
        ]);
        $postIds = preg_split("/\r\n|\n|\r/", $request->post_ids);
        $data = [
            'name' => $request->name,
            'post_ids' => json_encode($postIds),
            'file_name' => '',
        ];


        $reaction = Reaction::create($data);
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

        $reaction = Reaction::findorFail($id);
        if ($reaction->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Update failed (status == 1)'
            ], 400);
        }

        $postIds = preg_split("/\r\n|\n|\r/", $request->post_ids);
        $data = [
            'name' => $request->name,
            'post_ids' => json_encode($postIds),
            'file_name' => '',
            'status' => 0,
        ];

        $reaction->update($data);
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
