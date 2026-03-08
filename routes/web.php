<?php

use App\Http\Controllers\PdfController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Route::get('/invoices', 'App\Http\Controllers\InvoiceController@getInvoices');
Route::get('/users', [UserController::class, 'index']);

Route::get('/pdf', [PdfController::class, 'index']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
