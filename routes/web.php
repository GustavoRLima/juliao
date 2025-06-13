<?php

use App\Http\Controllers\CompeticoesController;
use App\Http\Controllers\CompetidoresController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    
    Route::resource('/competidores', CompetidoresController::class)->names('competidores')->parameters([
        'competidores' => 'competidor'
    ]);

    Route::resource('/competicoes', CompeticoesController::class)->names('competicoes')->parameters([
        'competicoes' => 'competicao'
    ]);

    Route::get('/competicao-tabela', [CompeticoesController::class, 'competicaoTabela'])->name('competicao.tabela');
    Route::get('/competicao-add-competidores/{competicao}', [CompeticoesController::class, 'addCompetidores'])->name('competicao.add-competidores');
});

require __DIR__.'/auth.php';
