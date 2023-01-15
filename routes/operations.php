<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware(['auth'])->name('operations.')->prefix('operations')->group(function () {
    //Quotation part
	Route::namespace("App\Http\Controllers\Operations\Quotations")->group(function () {
        Route::resource('quotations', QuotationController::class);
        Route::get('create-quote-revision/{quote_id}', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'createQuoteRevision'])->name('quote-revisions.create');
    });
});
