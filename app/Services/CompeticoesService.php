<?php 


namespace App\Services;

use App\Repository\CompeticoesRepository;
use App\Repository\CompetidoresRepository;
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
        $competidoresRepository = new CompetidoresRepository();
        return $competidoresRepository->getCompetidoresCompeticao($competicao->id);
    }

    function excluirCompetidor($competicao, $competidor, $categoria)
    {
        if($this->competicoesRepository->excluirCompetidor($competicao->id, $competidor->id, $categoria->id)){
            $this->competicoesRepository->logText($competicao, "exclusao de competidor", [
                "competidor_id" => $competicao->id
            ]);
            return true;
        }

        return false;
    }

    public function addCompetidores($dados, $competicao)
    {
        if(count($dados) == 0) return true;

        $competidores = collect($dados['competidores'])
        ->unique(fn ($item) => $item['competidor_id'] . '|' . $item['categoria_id'])
        ->values()
        ->all();
        
        foreach ($competidores as $key => $value) {
            if(!empty($this->verificaExisteCompetidor($value, $competicao))){
                unset($competidores[$key]);
                continue;
            }
        }
        
        if(count($dados) == 0) return true;
        
        $competicao->competidores()->syncWithoutDetaching($competidores);

        $this->competicoesRepository->logText($competicao, 'Adicionando competidores', $dados);
    }

    public function verificaExisteCompetidor($dados, $competicao)
    {
        return $this->competicoesRepository->verificaExisteCompetidor($dados, $competicao);
    }
}