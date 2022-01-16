<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\HeadquarterController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\PrintTicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('/clients',  ClientController::class);
Route::post('/clients/{client}/change-status',  [ClientController::class, 'changeStatus']);
Route::get('/clients/{client}/credits', [ClientController::class, 'credits']);


Route::resource('/proveedores',  ProveedorController::class);
Route::post('/proveedores/{proveedor}/change-status',  [ProveedorController::class, 'changeStatus']);

Route::resource('/users',  UserController::class);
Route::post('/users/{user}/change-status',  [UserController::class, 'changeStatus']);

Route::resource('/headquarters',  HeadquarterController::class);
Route::post('/headquarters/{headquarter}/change-status',  [HeadquarterController::class, 'changeStatus']);

Route::get('/credits/amortization-table', [InstallmentController::class, 'printTable']);
Route::post('/credits/pay-credit-installments/{id}', [CreditController::class, 'payMultipleInstallments']);
Route::resource('/credits', CreditController::class);
Route::post('/credits/{credit}/change-status',  [CreditController::class, 'changeStatus']);
Route::get('/credits/{credit}/installments', [CreditController::class, 'installments']);


Route::get('/installments/calculate-installments', [InstallmentController::class, 'calcularInstallments']);
Route::post('/installment/{id}/pay-installment', [InstallmentController::class, 'payInstallment']);

Route::get('/print-installment', [PrintTicketController::class, 'printInstallment']);

Route::resource('/installments', InstallmentController::class);