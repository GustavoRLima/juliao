<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompeticoesRequest;
use App\Models\CompeticaoModel;
use App\Services\CompeticoesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CompeticoesController extends Controller
{
    public function __construct(
        protected CompeticoesService $competicoesService
    )
    {
    }
    
    function index(Request $request)
    {
        $dados = $request->input();
        $competicaoes = $this->competicoesService->getCompeticoes($dados);
        $filtros = [
            'search' => $dados['search'] ?? ''
        ];

        return Inertia::render('Competicoes/Index', [
            'filtros' => $filtros,
            'competicoes' => $competicaoes
        ]);
    }
    
    function create()
    {
        return Inertia::render('Competicoes/Form');
    }

    function store(CompeticoesRequest $request)
    {
        try {
            DB::transaction(function() use($request){
                $this->competicoesService->store($request);
            });
            return redirect()->route('competicoes.index')->with('message', ['success' => 'Competição cadastrado com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao cadastrar competição.');
        }
    }
    
    function edit(CompeticaoModel $competicao)
    {
        return Inertia::render('Competicoes/Form', [
            'competicao' => $competicao,
        ]);
    }

    function update(CompeticoesRequest $request, CompeticaoModel $competicao)
    {
        try {
            DB::transaction(function() use($request, $competicao){
                $this->competicoesService->update($request, $competicao);
            });
            return redirect()->route('competicoes.edit', [$competicao])->with('message', ['success' => 'Competição editado com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao editar competição.');
        }
    }

    function destroy(CompeticaoModel $competicao)
    {
        try {
            DB::transaction(function() use($competicao){
                $this->competicoesService->excluir($competicao);
            });
            return redirect()->back()->withInput()->with('message', ['success' => 'Competição removida com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao excluir competição.');
        }
    }
    
    public function competicaoTabela()
    {
        return Inertia::render('Competicoes/Tabela');
    }
}
