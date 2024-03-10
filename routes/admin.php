<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware(['auth'])->name('admin.')->prefix('admin')->namespace("App\Http\Controllers\Admin")->group(function () {
	Route::get('/dashboard', [App\Http\Controllers\Admin\HomeController::class, 'dashboard'])->name('dashboard')->middleware('can:isAdmin');
    Route::resource('destinations', DestinationController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('agents', AgentController::class);
    Route::get('company-details', [App\Http\Controllers\HomeController::class, 'getCompanyDetails'])->name('company.details');
    Route::post('company-details', [App\Http\Controllers\HomeController::class, 'updateCompanyDetails'])->name('company.details.save');
    //Hotels part
    Route::resource('hotels', HotelController::class);
    Route::resource('hotels.rooms', RoomCategoryController::class);
    Route::resource('hotels.addons', HotelAddOnController::class);
    Route::resource('hotels.date-plans', DatePlanController::class);
    Route::get('get-date-plan-list/{id}', [App\Http\Controllers\Admin\DatePlanController::class, 'getDatePlanList'])->name('dateplan.list');
    Route::post('copy-packages', [App\Http\Controllers\Admin\DatePlanController::class, 'copyPackages'])->name('packages.copy');
    Route::resource('hotels.date-plans.packages', PackageController::class);
    Route::resource('hotels.date-plans.packages.rates', PackageRateController::class);
    //Vehicles part
    Route::resource('vehicles', VehicleController::class);
    Route::resource('vehicles.addons', VehicleAddOnController::class);
    //Quotation notes
    Route::resource('quotation-notes', QuotationNoteController::class);
    // Members/Users
    Route::resource('members', UserController::class);
    // Itinerary
    Route::resource('itinerary', ItineraryController::class);
    // Bank
    Route::resource('banks', BankController::class);
    Route::get('/pending-payments', [App\Http\Controllers\Admin\HomeController::class, 'getPendingpayments'])->name('pending.payments')->middleware('can:isAdmin');
    Route::post('approve-payment', [App\Http\Controllers\Admin\HomeController::class, 'approvePayment'])->name('payment.approve')->middleware('can:isAdmin');
    Route::post('reject-payment', [App\Http\Controllers\Admin\HomeController::class, 'rejectPayment'])->name('payment.reject')->middleware('can:isAdmin');
});
Route::get('/leads-report', [App\Http\Controllers\Admin\ReportController::class, 'leadsReport'])->name('leads.report');

