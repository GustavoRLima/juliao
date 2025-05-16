<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CompeticoesController extends Controller
{
    public function competicaoTabela()
    {
        return Inertia::render('Competicao/Tabela');
    }
}
