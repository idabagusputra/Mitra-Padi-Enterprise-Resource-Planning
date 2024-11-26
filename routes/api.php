<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebitController;
use App\Http\Controllers\GilingController;
use App\Http\Controllers\KreditController;
use App\Http\Controllers\PembayaranKreditController;
use App\Http\Controllers\PetaniController;


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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('giling', GilingController::class);
    Route::apiResource('kredit', KreditController::class);
    Route::apiResource('petani', PetaniController::class);
    Route::apiResource('pembayaran-kredit', PembayaranKreditController::class);
});


// Special route for submitting giling data
Route::post('/submit/giling', [GilingController::class, 'store']);
Route::get('/petani/search', [PetaniController::class, 'search']);