<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProjectController;

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

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard.overview');
})->name('dashboard.overview');

// Project Routes
Route::prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index');
    Route::get('/create', [ProjectController::class, 'create'])->name('create');
    Route::post('/', [ProjectController::class, 'store'])->name('store');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
    Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
    Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
});

// Program Routes
Route::prefix('programs')->name('programs.')->group(function () {
    Route::get('/', [App\Http\Controllers\ProgramController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\ProgramController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\ProgramController::class, 'store'])->name('store');
    Route::get('/{program}', [App\Http\Controllers\ProgramController::class, 'show'])->name('show');
    Route::get('/{program}/edit', [App\Http\Controllers\ProgramController::class, 'edit'])->name('edit');
    Route::put('/{program}', [App\Http\Controllers\ProgramController::class, 'update'])->name('update');
    Route::delete('/{program}', [App\Http\Controllers\ProgramController::class, 'destroy'])->name('destroy');
});

// Facility Routes
Route::prefix('facilities')->name('facilities.')->group(function () {
    Route::get('/', [FacilityController::class, 'index'])->name('index');
    Route::get('/create', [FacilityController::class, 'create'])->name('create');
    Route::post('/', [FacilityController::class, 'store'])->name('store');
    Route::get('/{facility}', [FacilityController::class, 'show'])->name('show');
    Route::get('/{facility}/edit', [FacilityController::class, 'edit'])->name('edit');
    Route::put('/{facility}', [FacilityController::class, 'update'])->name('update');
    Route::delete('/{facility}', [FacilityController::class, 'destroy'])->name('destroy');
});

// Service Routes
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/create', [ServiceController::class, 'create'])->name('create');
    Route::post('/', [ServiceController::class, 'store'])->name('store');
    Route::get('/{service}', [ServiceController::class, 'show'])->name('show');
    Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('edit');
    Route::put('/{service}', [ServiceController::class, 'update'])->name('update');
    Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');
    Route::get('/facility/{facility}', [ServiceController::class, 'getByFacility'])->name('by-facility');
});

// Equipment Routes
Route::prefix('equipment')->name('equipment.')->group(function () {
    Route::get('/', [EquipmentController::class, 'index'])->name('index');
    Route::get('/create', [EquipmentController::class, 'create'])->name('create');
    Route::post('/', [EquipmentController::class, 'store'])->name('store');
    Route::get('/{equipment}', [EquipmentController::class, 'show'])->name('show');
    Route::get('/{equipment}/edit', [EquipmentController::class, 'edit'])->name('edit');
    Route::put('/{equipment}', [EquipmentController::class, 'update'])->name('update');
    Route::delete('/{equipment}', [EquipmentController::class, 'destroy'])->name('destroy');
    Route::get('/facility/{facility}', [EquipmentController::class, 'getByFacility'])->name('by-facility');
});
