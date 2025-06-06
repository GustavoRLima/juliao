<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompetidoresRequest\CriarCompetidorRequest;
use App\Models\CompetidorModel;
use App\Repository\CompetidoresRepository;
use App\Services\CompetidoresService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CompetidoresController extends Controller
{
    public function __construct(
        protected CompetidoresService $competidoresService
    )
    {
    }
    
    function index(Request $request)
    {
        $competidores = $this->competidoresService->getCompetidores($request->input());

        return Inertia::render('Competidores/Index', [
            'competidores' => $competidores
        ]);
    }
    
    function create()
    {
        $optionsSexo = $this->competidoresService->getSexo();
        $optionsFaixas = $this->competidoresService->getFaixas();

        return Inertia::render('Competidores/Form', [
            'OptionsSexo' => $optionsSexo,
            'OptionsFaixas' => $optionsFaixas,
        ]);
    }

    function store(CriarCompetidorRequest $request)
    {
        try {
            DB::transaction(function() use($request){
                $this->competidoresService->store($request);
            });
            return redirect()->route('competidores.index')->with('message', ['success' => 'Competidor cadastrado com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao cadastrar competidor.');
        }
    }
    
    function edit(CompetidorModel $competidor)
    {
        $optionsSexo = $this->competidoresService->getSexo();
        $optionsFaixas = $this->competidoresService->getFaixas();

        return Inertia::render('Competidores/Form', [
            'OptionsSexo' => $optionsSexo,
            'OptionsFaixas' => $optionsFaixas,
            'competidor' => $competidor,
        ]);
    }

    function update(CriarCompetidorRequest $request, CompetidorModel $competidor)
    {
        try {
            DB::transaction(function() use($request, $competidor){
                $this->competidoresService->update($request, $competidor);
            });
            return redirect()->route('competidores.edit', [$competidor])->with('message', ['success' => 'Competidor editado com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao editar competidor.');
        }
    }

    function destroy(CompetidorModel $competidor)
    {
        try {
            DB::transaction(function() use($competidor){
                $this->competidoresService->excluir($competidor);
            });
            return redirect()->back()->withInput()->with('message', ['success' => 'Competidor removida com sucesso!']);
        } catch (\Exception $e) {
            return $this->retornoErrorBack($e, 'Erro ao excluir competidor.');
        }
    }
}
