<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client');
})->name('home');

Route::get('/expert', function () {
    return view('expert');
})->name('expert');

Route::get('/expert-detail', function () {
    return view('expert_detail');
})->name('expert_detail');
