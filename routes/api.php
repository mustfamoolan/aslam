<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AppointmentController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_middleware')
])->group(function () {
    Route::get('/patients/{patient}/appointments', function(App\Models\Patient $patient) {
        return response()->json([
            'success' => true,
            'appointments' => $patient->appointments()->with('patient')->get()
        ]);
    });

    Route::get('/appointments/{appointment}/edit', function(App\Models\Appointment $appointment) {
        return response()->json([
            'success' => true,
            'appointment' => $appointment
        ]);
    });

    Route::post('/appointments', [App\Http\Controllers\AppointmentController::class, 'store']);
    Route::delete('/appointments/{appointment}', [App\Http\Controllers\AppointmentController::class, 'destroy']);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_middleware')
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
