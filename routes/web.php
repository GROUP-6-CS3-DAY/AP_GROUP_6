<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EquipmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('programs', ProgramController::class);
Route::resource('projects', ProjectController::class);
Route::resource('equipments', EquipmentController::class);
