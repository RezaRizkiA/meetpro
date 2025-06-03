<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/auth/google/redirect', [AuthController::class, 'google_redirect'])->name('google_redirect');
Route::get('/auth/google/callback', [AuthController::class, 'google_callback'])->name('google_callback');

Route::get('profile', function () {
    return view('profile');
})->name('profile')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('register-expert', [AuthController::class, 'register'])->name('register_expert');

    Route::get('register-client', [AuthController::class, 'register'])->name('register_client');
    Route::post('register-client', [AuthController::class, 'register_client_post'])->name('register_client_post');



    Route::post('renew-password', [AuthController::class, 'renew_password'])->name('renew_password');
    Route::post('renew-picture', [AuthController::class, 'renew_picture'])->name('renew_picture');
    Route::post('renew-profile', [AuthController::class, 'renew_profile'])->name('renew_profile');
});

Route::get('make-appointment', function () {
    return view('appointment');
})->name('appointment');

Route::get('/', function () {
    return view('client');
})->name('home');

Route::get('/expert', function () {
    return view('expert');
})->name('expert');

Route::get('/expert-detail', function () {
    return view('expert_detail');
})->name('expert_detail');

Route::get('/login', function () {
    return view('authentication/login');
})->name('login')->middleware('guest');
