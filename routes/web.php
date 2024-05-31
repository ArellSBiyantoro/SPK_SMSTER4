<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesertaController;

Route::get('/', function () {
    return view('home');
});

Route::post('/calculate', [PesertaController::class, 'calculate']);