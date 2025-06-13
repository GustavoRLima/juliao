<?php 


namespace App\Services;

use App\Repository\CompeticoesRepository;
use Illuminate\Http\Request;

class CompeticoesService 
{

    protected CompeticoesRepository $competicoesRepository;

    public function __construct()
    {
        $this->competicoesRepository = new CompeticoesRepository();
    }

    public function getCompeticoes($dados)
    {
        return $this->competicoesRepository->getCompeticoes($dados)->paginate(15);
    }

    public function store(Request $request)
    {
        $dados = $request->validated();
        $this->competicoesRepository->create($dados);
        return true;
    }

    public function update(Request $request, $competicao)
    {
        $dados = $request->validated();
        $this->competicoesRepository->update($competicao, $dados);
        return true;
    }

    public function excluir($competicao)
    {
        $this->competicoesRepository->excluir($competicao);
        return true;
    }

    function getCompetidores($competicao)
    {
        $this->competicoesRepository->getCompetidores($competicao);
        return $competicao->competidores;
    }
}