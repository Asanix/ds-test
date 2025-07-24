<?php

use App\Http\Controllers\DataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::prefix('data')->controller(DataController::class)->group(function () {
    Route::get('{type}/fetch', [DataController::class, 'fetch']);
    Route::get('{type}/show', [DataController::class, 'show']);
    Route::delete('{type}/delete', [DataController::class, 'delete']);

    Route::get('show-table', 'showTable');
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
