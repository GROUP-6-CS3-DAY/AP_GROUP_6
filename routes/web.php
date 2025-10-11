<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParticipantController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Participant routes
Route::resource('participants', ParticipantController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('participants/{participant}/add-project', [ParticipantController::class, 'addProject'])
        ->name('participants.add-project');
    Route::delete('participants/{participant}/remove-project/{project}', [ParticipantController::class, 'removeProject'])
        ->name('participants.remove-project');
});

require __DIR__.'/auth.php';
