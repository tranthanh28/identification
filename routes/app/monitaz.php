<?php

use App\Http\Controllers\Monitaz\ReactionController;
use App\Http\Controllers\Monitaz\ScanGroupController;
use App\Http\Controllers\Monitaz\ScanPageController;
use App\Http\Controllers\Monitaz\ScanInfoController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reaction'], function () {
    Route::get('/', [ReactionController::class, 'index'])->name('reaction.index');
});

Route::group(['prefix' => 'scan-info'], function () {
    Route::get('/', [ScanInfoController::class, 'index'])->name('info.index');
});

Route::group(['prefix' => 'scan-group'], function () {
    Route::get('/', [ScanGroupController::class, 'index'])->name('group.index');
});

Route::group(['prefix' => 'scan-page'], function () {
    Route::get('/', [ScanPageController::class, 'index'])->name('page.index');
});

Route::group(['prefix' => 'identification'], function () {
    Route::get('/', [\App\Http\Controllers\Monitaz\IdentificationController::class, 'index'])->name('identification.index');
});
