<?php

use App\Http\Controllers\{Api\IdentificationController};

Route::group(['prefix' => 'identification'], function () {
    Route::get('/', [IdentificationController::class, 'index']);
    Route::get('/{id}', [IdentificationController::class, 'show']);
    Route::put('/{id}', [IdentificationController::class, 'update']);
    Route::delete('/{id}', [IdentificationController::class, 'delete']);
    Route::post('/', [IdentificationController::class, 'store']);
    Route::post('/export-excel', [IdentificationController::class, 'exportExcel']);
    // Route::post('/dowload', [ScanGroupController::class, 'index']);
});