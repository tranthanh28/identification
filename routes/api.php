<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

use App\Http\Controllers\Api\ReactionController;
use App\Http\Controllers\Api\ScanInfoController;
use App\Http\Controllers\Api\ScanGroupController;
use App\Http\Controllers\Api\ScanPageController;
use App\Http\Controllers\Api\IdentificationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reaction'], function () {
    Route::get('/', [ReactionController::class, 'index']);
    // Route::get('/{id}', [ReactionController::class, 'show']);
    Route::put('/{id}', [ReactionController::class, 'update']);
    Route::post('/', [ReactionController::class, 'store']);
    Route::post('/export-excel', [ReactionController::class, 'exportExcel']);
    // Route::post('/dowload', [ReactionController::class, 'index']);
});

Route::group(['prefix' => 'scan-info'], function () {
    Route::get('/', [ScanInfoController::class, 'index']);
    // Route::get('/{id}', [ScanInfoController::class, 'show']);
    Route::put('/{id}', [ScanInfoController::class, 'update']);
    Route::post('/', [ScanInfoController::class, 'store']);
    Route::post('/export-excel', [ScanInfoController::class, 'exportExcel']);
    // Route::post('/dowload', [ScanInfoController::class, 'index']);
});

Route::group(['prefix' => 'scan-page'], function () {
    Route::get('/', [ScanPageController::class, 'index']);
    // Route::get('/{id}', [ScanPageController::class, 'show']);
    Route::put('/{id}', [ScanPageController::class, 'update']);
    Route::post('/', [ScanPageController::class, 'store']);
    Route::post('/export-excel', [ScanPageController::class, 'exportExcel']);
    // Route::post('/dowload', [ScanPageController::class, 'index']);
});

Route::group(['prefix' => 'scan-group'], function () {
    Route::get('/', [ScanGroupController::class, 'index']);
    // Route::get('/{id}', [ScanGroupController::class, 'show']);
    Route::put('/{id}', [ScanGroupController::class, 'update']);
    Route::post('/', [ScanGroupController::class, 'store']);
    Route::post('/export-excel', [ScanGroupController::class, 'exportExcel']);
    // Route::post('/dowload', [ScanGroupController::class, 'index']);
});

Route::group(['prefix' => 'identification'], function () {
    Route::get('/', [IdentificationController::class, 'index']);
    // Route::get('/{id}', [IdentificationController::class, 'show']);
    Route::put('/{id}', [IdentificationController::class, 'update']);
    Route::post('/', [IdentificationController::class, 'store']);
    Route::post('/export-excel', [IdentificationController::class, 'exportExcel']);
    // Route::post('/dowload', [ScanGroupController::class, 'index']);
});

