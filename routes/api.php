<?php

use App\Http\Controllers\Api\AresController;
use App\Http\Controllers\Api\CollectController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\InvoiceController;

Route::apiResource('/invoices', InvoiceController::class);
Route::apiResource('/contacts', ContactController::class);

Route::get('/invoices/id/{invoiceId}', [InvoiceController::class, 'getById']);

Route::get('/invoices/{invoice}/preview-pdf', [InvoiceController::class, 'pdfPreview']);
Route::post('/invoices/{invoice}/create-pdf', [InvoiceController::class, 'pdfProcess']);

Route::post('/ares/search', [AresController::class, 'search']);
Route::get('/collect', [CollectController::class, 'index']);
