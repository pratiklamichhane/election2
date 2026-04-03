<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\ElectionPartiesController;
use App\Http\Controllers\LeaderController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\VoteController;
use App\Http\Middleware\isAdmin;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/vote', [VoteController::class, 'vote'])->name('vote');
});

require __DIR__.'/auth.php';


//ADMIN ROUTES WITH NAME PREFIX AND NAMESPACES
Route::middleware(['auth', isAdmin::class])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users/approve/{user}', [UserController::class, 'approve'])->name('users.approve');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::resource('elections', ElectionController::class);
    Route::resource('partys', PartyController::class);
    Route::resource('election_parties', ElectionPartiesController::class);
    Route::resource('leaders', LeaderController::class);



});

Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
Route::get('/vote', [VoteController::class, 'index'])->name('vote.index');
Route::post('/vote/store', [VoteController::class, 'store'])->name('voting.store');
Route::get('/vote/results', [VoteController::class, 'results'])->name('vote.results');
Route::get('/vote/predict', [VoteController::class, 'predict'])->name('vote.predict');


