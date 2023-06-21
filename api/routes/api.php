<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\invoiceMailController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OfferController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('customers', customerController::class);
Route::apiResource('invoices', invoiceController::class);
Route::apiResource('sites', SiteController::class);
Route::apiResource('company', FirmaController::class);
Route::apiResource('images', ImageController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('offers', OfferController::class);
Route::post('invoices/send/{invoice}', [invoiceMailController::class, 'sendInvoiceEmail']);
Route::post('offers/send/{offer}', [invoiceMailController::class, 'sendOfferEmail']);
