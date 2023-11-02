<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('clients', ClientController::class, ['except' => ['create', 'edit', 'show', 'update']]);
Route::post('/clients/{id}', [ClientController::class, 'update']);
Route::get('/addressTypes', [ClientController::class, 'getAddressTypes']);

Route::controller(DashboardController::class)->group(function () {
    Route::get('/getClientsByMonth', 'clientsByMonth');
    Route::get('/getActiveClientsAndAddresses', 'activeClientsAndAddresses');
    Route::get('/getaddressesByType', 'addressesByType');
});
