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

    public function update(Request $request, $competidor)
    {
        $dados = $request->validated();
        $this->competicoesRepository->update($competidor, $dados);
        return true;
    }

    public function excluir($competidor)
    {
        $this->competicoesRepository->excluir($competidor);
        return true;
    }

}