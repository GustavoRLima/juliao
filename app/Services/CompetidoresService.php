<?php 

namespace App\Services;

use App\Models\CompetidorModel;
use App\Repository\CategoriaRepository;
use App\Repository\CompetidoresRepository;
use App\Support\CategoriaSupport;
use Illuminate\Http\Request;

class CompetidoresService 
{
    use CategoriaSupport;
    protected CompetidoresRepository $competidoresRepository;

    public function __construct()
    {
        $this->competidoresRepository = new CompetidoresRepository();
    }

    public function getCompetidores($dados)
    {
        return $this->competidoresRepository->getCompetidores($dados)->paginate(15);
    }

    public function getBuscarCompetidores(Request $request)
    {
        $dados = $request->input();
        return $this->competidoresRepository->getCompetidores($dados)->get(30);
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
        $data = $this->competidoresRepository->getFaixas();
        
        foreach ($data as $key => $value) {
            $retorno[] = [
                'id' => $key,
                'name' => $value
            ];
        }

        return $retorno;
    }

    public function getCategoriasCompetidores(CompetidorModel $competidor)
    {
        $categoriaRepository = new CategoriaRepository;
        $idade = \Carbon\Carbon::parse($competidor->data_nascimento)->age;
        return $categoriaRepository->getCategoriasCompetidores($idade, $competidor->peso, $competidor->sexo == 1 ? 2 : 3)->get()->toArray();
    }
}