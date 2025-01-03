<?php

namespace App\Http\Controllers\Api;

use App\Exports\ReactionExport;
use App\Http\Controllers\Controller;
use App\Models\Monitaz\FileMr\FileMr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class MRController extends Controller
{
    public function index()
    {
        $reaction = FileMr::all();
        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $reaction
        ], 200);

    }
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'model' => 'required',
        ]);

        $model = $request->get('model');

        $uploadedFile = $request->file('file');
        $uniqueFileName = time() . '_' . $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->storeAs('uploads', $uniqueFileName, 'public');

        $data = [
            'name' => $uniqueFileName,
            'status' => 1,
            'model' => $model,
        ];

        $fileMr = FileMr::create($data);

        $response = Http::post('http://localhost:5000/predict', [
            'file_name' => $uniqueFileName,
        ]);

        $data = $response->json();

        $fileMr->update(["status" => 2]);

        return response()->json([
            'status' => true,
            'message' => 'created successfully',
            'data' => $data
        ], 200);

    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $this->userRepository->update($user, $request->only('name', 'email'));

        return response()->json([
            'status' => true,
            'message' => 'User update Successfully',
            'data' => $user
        ], 200);
    }

    public function exportExcel(Request $request)
    {
        $this->validate($request, [
            'file_name' => 'required'
        ]);
        $path = $request->get('file_name');

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->download($path);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
