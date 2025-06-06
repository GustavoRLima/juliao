<?php 

namespace App\Services;

use App\Repository\CompetidoresRepository;
use Illuminate\Http\Request;

class CompetidoresService 
{
    protected CompetidoresRepository $competidoresRepository;

    public function __construct()
    {
        $this->competidoresRepository = new CompetidoresRepository();
    }

    public function getCompetidores($dados)
    {
        return $this->competidoresRepository->getCompetidores($dados)->paginate(15);
    }

    public function store(Request $request)
    {
        $dados = $request->validated();
        $this->competidoresRepository->create($dados);
        return true;
    }

    public function update(Request $request, $competidor)
    {
        $dados = $request->validated();
        $this->competidoresRepository->update($competidor, $dados);
        return true;
    }

    public function excluir($competidor)
    {
        $this->competidoresRepository->excluir($competidor);
        return true;
    }

    public function getSexo()
    {
        return [
            [
                'id' => 1,
                'name' => "Masculino"
            ],
            [
                'id' => 2,
                'name' => "Feminino"
            ]
        ];
    }
    
    public function getFaixas()
    {
        return [
            [ 'id' => "branca", 'name' => "Branca" ],
            [ 'id' => 'amarela', 'name' => "Amarela"],
            [ 'id' => 'laranja', 'name' => "Laranja"],
            [ 'id' => 'verde', 'name' => "Verde"],
            [ 'id' => 'azul', 'name' => "Azul"],
            [ 'id' => 'roxa', 'name' => "Roxa"],
            [ 'id' => 'marrom', 'name' => "Marrom"],
            [ 'id' => 'preta', 'name' => "Preta"],
            [ 'id' => 'coral_vermelha', 'name' => "Coral e vermelha"],
        ];
    }

    // public function getCategorias()
    // {
    //     return [
    //         'GALO' => [
    //             'MIRIM 1' => 
    //             'MIRIM 2' => 
    //             'INFANTIL 1' => 
    //             'INFANTIL 2' => 
    //             'INFANTO-JUVENIL' => 
    //             'JUVENIL' => 
    //             'ADULTOS' => 
    //             'MASTERS' => 
    //         ]
    //     ]
    // }
}