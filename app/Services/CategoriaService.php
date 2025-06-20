<?php 

namespace App\Services;

use App\Repository\CategoriaRepository;
use Illuminate\Http\Request;

class CategoriaService 
{
    protected CategoriaRepository $categoriaRepository;

    public function __construct()
    {
        $this->categoriaRepository = new CategoriaRepository();
    }

    public function getCategorias($dados)
    {
        return $this->categoriaRepository->getCategorias($dados)->paginate(15);
    }

    public function store(Request $request)
    {
        $dados = $request->validated();
        $this->categoriaRepository->create($dados);
        return true;
    }

    public function update(Request $request, $competidor)
    {
        $dados = $request->validated();
        $this->categoriaRepository->update($competidor, $dados);
        return true;
    }

    public function excluir($competidor)
    {
        $this->categoriaRepository->excluir($competidor);
        return true;
    }
}