<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParticipantController; // ✅ add this line
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Participant CRUD routes
    Route::resource('participants', ParticipantController::class);
    Route::post('/participants/{participant}/projects', [ParticipantController::class, 'addProject'])
        ->name('participants.add-project');
    Route::delete('/participants/{participant}/projects/{project}', [ParticipantController::class, 'removeProject'])
        ->name('participants.remove-project');
});

require __DIR__.'/auth.php';
