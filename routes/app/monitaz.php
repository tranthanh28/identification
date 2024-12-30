<?php

use App\Http\Controllers\Monitaz\ReactionController;
use App\Http\Controllers\Monitaz\MrController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reaction'], function () {
    Route::get('/', [ReactionController::class, 'index'])->name('reaction.index');
});

Route::group(['prefix' => 'mr'], function () {
    Route::get('/', [MrController::class, 'index'])->name('reaction.index');
});
