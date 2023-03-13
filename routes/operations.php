<?php

use Illuminate\Support\Facades\Route;

Auth::routes();
Route::middleware(['auth'])->name('operations.')->prefix('operations')->group(function () {
    //Quotation part
	Route::namespace("App\Http\Controllers\Operations\Quotations")->group(function () {
        Route::resource('quotations', QuotationController::class);
        Route::get('create-quote-revision/{quote_id}', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'createQuoteRevision'])->name('quote-revisions.create');
        Route::post('save-quote-revision', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'saveQuoteRevison'])->name('quote-revisions.save');
        Route::get('revision-calculation/{rev_id}', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'revisionCalculation'])->name('revision.calculation');
        Route::get('get-hotels-with-destination/{destination_id}', [App\Http\Controllers\Operations\Quotations\RateController::class, 'getHotelList'])->name('hotels.list');
        Route::get('get-packages-with-hotel/{hotel_id}/{date}', [App\Http\Controllers\Operations\Quotations\RateController::class, 'getPackageList'])->name('packages.list');
        Route::get('get-rooms-with-hotel/{hotel_id}', [App\Http\Controllers\Operations\Quotations\RateController::class, 'getRoomCategories'])->name('rooms.list');
        Route::get('get-rates-with-room/{room_id}/{package_id}/{date}', [App\Http\Controllers\Operations\Quotations\RateController::class, 'getRatesWithRoom'])->name('rates.list');
        Route::get('get-vehicle-rates/{total_km}/{days}/{vehicle_id}', [App\Http\Controllers\Operations\Quotations\RateController::class, 'getVehicleRate'])->name('vehicle-rate.get');
        Route::post('save-quote-details', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'saveQuoteRevisonDetails'])->name('quote-revisions-details.save');
        Route::get('revision-calculation-view/{rev_id}', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'revisionCalculationView'])->name('revision.calculation.view');
        Route::get('revision-calculation-mailable-view/{rev_id}', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'revisionCalculationMailableView'])->name('revision.calculation.mailable_view');
        Route::post('copy-revision/{rev_id}', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'copyRevision'])->name('revision.copy');
        Route::post('save-transport-revision', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'saveTransportationRevision'])->name('transportation-revision.save');
    });
    //Availability
    Route::post('ask-availability', [App\Http\Controllers\Operations\AvailabilityController::class, 'askAvailability'])->name('availability.ask');
    Route::post('report-availability', [App\Http\Controllers\Operations\AvailabilityController::class, 'reportAvailability'])->name('availability.report');
    Route::resource('availabilities', App\Http\Controllers\Operations\AvailabilityController::class);
    //Booking
    Route::post('create-booking/{rev_id}', [App\Http\Controllers\Operations\Quotations\QuotationController::class, 'createBooking'])->name('booking.create');
    Route::get('bookings', [App\Http\Controllers\Operations\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{id}', [App\Http\Controllers\Operations\BookingController::class, 'show'])->name('bookings.show');
    Route::post('forward-bookings/{rev_id}', [App\Http\Controllers\Operations\BookingController::class, 'forwardBookings'])->name('booking.forward');
});
