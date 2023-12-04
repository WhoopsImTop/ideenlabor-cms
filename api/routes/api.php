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
use App\Http\Controllers\RecurringInvoiceController;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\UploadController;
use App\Http\Controllers\CustomerCenterController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\FolderController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    /* Route::apiResource('customers', customerController::class); */
    Route::apiResource('invoices', invoiceController::class);
    Route::apiResource('sites', SiteController::class);
    Route::apiResource('company', FirmaController::class);
    Route::apiResource('images', ImageController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('offers', OfferController::class);
    Route::apiResource('recurringInvoices', RecurringInvoiceController::class);
    Route::post('invoices/send/{invoice}', [invoiceMailController::class, 'sendInvoiceEmail']);
    Route::post('offers/send/{offer}', [invoiceMailController::class, 'sendOfferEmail']);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('customers', customerController::class);
Route::apiResource('uploads', UploadController::class);
Route::apiResource('customerCenters', CustomerCenterController::class);
Route::apiResource('applications', ApplicationController::class);
Route::apiResource('folders', FolderController::class);

Route::post('customerCenter/updateCustomerInformation/{id}', [CustomerCenterController::class, 'updateCustomerInformation']);
Route::post('customerCenter/login', [CustomerCenterController::class, 'customerLogin']);
Route::post('customerCenter/validatePassword/{id}', [CustomerCenterController::class, 'validateCustomerPassword']);
