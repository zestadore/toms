<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware(['auth'])->name('admin.')->prefix('admin')->namespace("App\Http\Controllers\Admin")->group(function () {
	Route::get('/dashboard', [App\Http\Controllers\Admin\HomeController::class, 'dashboard'])->name('dashboard')->middleware('can:isAdmin');
    Route::resource('destinations', DestinationController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('agents', AgentController::class);
    //Hotels part
    Route::resource('hotels', HotelController::class);
    Route::resource('hotels.rooms', RoomCategoryController::class);
    Route::resource('hotels.addons', HotelAddOnController::class);
    Route::resource('hotels.date-plans', DatePlanController::class);
    Route::get('get-date-plan-list/{id}', [App\Http\Controllers\Admin\DatePlanController::class, 'getDatePlanList'])->name('dateplan.list');
    Route::post('copy-packages', [App\Http\Controllers\Admin\DatePlanController::class, 'copyPackages'])->name('packages.copy');
    Route::resource('hotels.date-plans.packages', PackageController::class);
    Route::resource('hotels.date-plans.packages.rates', PackageRateController::class);
});