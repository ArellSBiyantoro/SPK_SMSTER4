<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantController;

// Route::get('/', function () {
//     return view('create');
// });

Route::get('/', [ParticipantController::class, 'index'])->name('participants.create');
Route::post('/participants', [ParticipantController::class, 'store'])->name('participants.store');