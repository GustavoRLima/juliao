<?php

use App\Http\Controllers\CategoriasController;
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
    Route::get('/get-buscar-competidores', [CompetidoresController::class, 'getBuscarCompetidores'])->name('competidores.get-buscar-competidores');
    Route::get('/get-categorias-competidores/{competidor}', [CompetidoresController::class, 'getCategoriasCompetidores'])->name('competidores.get-categorias-competidor');

    Route::resource('/competicoes', CompeticoesController::class)->names('competicoes')->parameters([
        'competicoes' => 'competicao'
    ]);

    Route::get('/competicao-tabela', [CompeticoesController::class, 'competicaoTabela'])->name('competicao.tabela');
    Route::get('/competicao/{competicao}/add-competidores', [CompeticoesController::class, 'addCompetidores'])->name('competicao.add-competidores');
    Route::get('/competicao/{competicao}/lista-competidores', [CompeticoesController::class, 'listaCompetidores'])->name('competicao.lista-competidores');
    Route::delete('/competicao/{competicao}/excluir-competidores/{competidor}/categoria/{categoria}', [CompeticoesController::class, 'excluirCompetidores'])->name('competicao.excluir-competidores');
    Route::post('/competicao/{competicao}/salvar-competidores', [CompeticoesController::class, 'salvarCompetidores'])->name('competicao.salvar-competidores');
    Route::get('/competicao/{competicao}/gerar-tabela-competicao', [CompeticoesController::class, 'gerarTabelaCompeticao'])->name('competicao.gerar-tabela-competicao');
    Route::get('/competicao/{competicao}/categoria/{categoria}/faixa/{faixa}', [CompeticoesController::class, 'verTabelaCompeticao'])->name('competicao.ver-tabela-competicao');
    Route::get('competicao-competidor-vencedor-retorno', [CompeticoesController::class, 'competidorVencedorRetorno'])->name('competicao.competidor-vencedor-retorno');

    Route::resource('/categorias', CategoriasController::class)->names('categorias');
});

require __DIR__.'/auth.php';
