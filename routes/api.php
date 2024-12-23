<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AmenityController;
use App\Http\Controllers\ContentManagementController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PaymentConfimationController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/amenities', [AmenityController::class, 'getAmenities']);
Route::get('/accommodations', [AccommodationController::class, 'getAccommodations']);
Route::post('/confirmPayment/{id}', [PaymentConfimationController::class, 'confirm'])->name('payment.confirm');
Route::get('/testimonials', [TestimonialController::class, 'getTestimonials']);
Route::post('/submitForm', [FormController::class, 'handleForm']);
Route::get('/getFirstSection', [ContentManagementController::class, 'getFirstSection']);