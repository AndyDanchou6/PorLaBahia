<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\AmenityController;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
});
Route::get('/about', [AboutController::class, 'index']);
Route::get('/accommodation', [AccommodationController::class, 'viewAccommodations']);
Route::get('/amenities', [AmenityController::class, 'viewAmenities']);

// Route::get('/test', function () {
//     $recipient = auth()->user();

//     \Filament\Notifications\Notification::make()
//         ->title('Test Notification')
//         ->sendToDatabase($recipient)
//         ->broadcast($recipient);

//     // event(new \App\Events\NotificationEvent($recipient));
//     dd(env('PUSHER_APP_KEY'));
// });
