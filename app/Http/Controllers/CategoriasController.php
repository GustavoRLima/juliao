<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest\CriarCategoriaRequest;
use App\Models\CategoriaModel;
use App\Services\CategoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CategoriasController extends Controller
{
    public function __construct(
        protected CategoriaService $categoriaService
    )
    {
    }
    
    function index(Request $request)
    {
        $dados = $request->input();
        $categoriaes = $this->categoriaService->getCategorias($dados);
        $filtros = [
            'search' => $dados['search'] ?? ''
        ];

        return Inertia::render('Categorias/Index', [
            'filtros' => $filtros,
            'categorias' => $categoriaes
        ]);
    }
    
    function create()
    {
        return Inertia::render('Categorias/Form');
    }

    function store(CriarCategoriaRequest $request)
    {
        try {
            DB::transaction(function() use($request){
                $this->categoriaService->store($request);
            });
            return redirect()->route('categorias.index')->with('message', ['success' => 'Categoria cadastrado com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao cadastrar categoria.');
        }
    }
    
    function edit(CategoriaModel $categoria)
    {
        return Inertia::render('Categorias/Form', [
            'categoria' => $categoria,
        ]);
    }

    function update(CriarCategoriaRequest $request, CategoriaModel $categoria)
    {
        try {
            DB::transaction(function() use($request, $categoria){
                $this->categoriaService->update($request, $categoria);
            });
            return redirect()->route('categorias.edit', [$categoria])->with('message', ['success' => 'Categoria editado com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao editar categoria.');
        }
    }

    function destroy(CategoriaModel $categoria)
    {
        try {
            DB::transaction(function() use($categoria){
                $this->categoriaService->excluir($categoria);
            });
            return redirect()->back()->withInput()->with('message', ['success' => 'Categoria removida com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao excluir categoria.');
        }
    }

}
