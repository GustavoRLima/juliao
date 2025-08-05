<?php

namespace App\Http\Controllers;

use App\Http\Requests\Competicoes\AddCompeditidorRequest;
use App\Http\Requests\Competicoes\CompetidorVencedorRequest;
use App\Http\Requests\CompeticoesRequest;
use App\Models\CategoriaModel;
use App\Models\CompeticaoModel;
use App\Models\CompetidorModel;
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
    
    public function listaCompetidores(Request $request, CompeticaoModel $competicao)
    {
        $dados = $request->input();
        $filtros = [
            'search' => $dados['search'] ?? ''
        ];
        $competidores = $this->competicoesService->getCompetidores($competicao, $filtros);
        
        return Inertia::render('Competicoes/Competidores', [
            'filtros' => $filtros,
            'competidores' => $competidores,
            'competicao' => $competicao,
        ]);
    }
    
    public function addCompetidores(CompeticaoModel $competicao)
    {
        return Inertia::render('Competicoes/AddCompetidores', [
            'competicao' => $competicao
        ]);
    }
    
    public function excluirCompetidores(CompeticaoModel $competicao, CompetidorModel $competidor, CategoriaModel $categoria)
    {
        try {
            DB::transaction(function() use($competicao, $competidor, $categoria){
                $this->competicoesService->excluirCompetidor($competicao, $competidor, $categoria);
            });
            return redirect()->back()->withInput()->with('message', ['success' => 'Competidor removido com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao excluir competidor.');
        }
    }

    public function salvarCompetidores(AddCompeditidorRequest $request, CompeticaoModel $competicao)
    {
        try {
            DB::transaction(function() use($request, $competicao){
                $this->competicoesService->addCompetidores($request->validated(), $competicao);
            });
            return redirect()->route('competicao.lista-competidores', [$competicao])->with('message', ['success' => 'Competidores adicionado com sucesso']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao inserir competidores.');
        }
    }

    public function gerarTabelaCompeticao(Request $request, CompeticaoModel $competicao)
    {
        $categorias = $this->competicoesService->getChaveamentoCategoria($competicao);
        if(!$competicao->chave_gerada || $request->boolean('gerar_nova_chave')){
            DB::transaction(function() use($competicao, $categorias){
                return $this->competicoesService->gerarChaves($competicao, $categorias);
            });
        }

        if($request->boolean('gerar_nova_chave')){
            return response()->json([
                'categorias' => $categorias
            ]);
        }

        return Inertia::render('Competicoes/ListaChaveamento', [
            'categorias' => $categorias,
            'competicao' => $competicao
        ]);
    }

    public function verTabelaCompeticao(CompeticaoModel $competicao, CategoriaModel $categoria, string $faixa)
    {
        $competidores1 = $this->competicoesService->getCompetidoresCategoria($competicao, $categoria, $faixa, 1);
        $competidores2 = $this->competicoesService->getCompetidoresCategoria($competicao, $categoria, $faixa, 2);

        return Inertia::render('Competicoes/Tabela', [
            'competidores1' => $competidores1,
            'competidores2' => $competidores2,
            'competicao' => $competicao
        ]);
    }

    public function competidorVencedorRetorno(CompetidorVencedorRequest $request)
    {
        $dados = $request->validated();

        $competidor = DB::transaction(function() use($dados, $request){
            if(isset($dados['tipo'])){
                $dados['tipo'] = $request->boolean('tipo');
                return $this->competicoesService->vencedorRetorno($dados);
            }else{
                $dados['derrota'] = $request->boolean('derrota');
                return $this->competicoesService->setDerrotaCompetidor($dados);
            }
        });
        
        return response()->json([
            'competidor' => $competidor
        ]);
    }
}
