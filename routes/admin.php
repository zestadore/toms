<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware(['auth'])->name('admin.')->prefix('admin')->namespace("App\Http\Controllers\Admin")->group(function () {
	Route::get('/dashboard', [App\Http\Controllers\Admin\HomeController::class, 'dashboard'])->name('dashboard')->middleware('can:isAdmin');
    Route::resource('destinations', DestinationController::class);
});