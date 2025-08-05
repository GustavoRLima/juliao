<?php 


namespace App\Services;

use App\Models\CompeticaoModel;
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

    function getCompetidores($competicao, $filtros)
    {
        $competidoresRepository = new CompetidoresRepository();
        return $competidoresRepository->getCompetidoresCompeticao($competicao->id, $filtros);
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

    public function getChaveamentoCategoria(CompeticaoModel $competicao)
    {
        return $this->competicoesRepository->getCategorias($competicao);
    }

    public function gerarChaves(CompeticaoModel $competicao, $categorias)
    {        
        foreach ($categorias as $categoria) {

            $faixas = $this->competicoesRepository->getFaixasCategoria($competicao, $categoria);
            foreach ($faixas as $faixa) {

                $competidores = $this->competicoesRepository->getCompetidoresCompeticao($competicao, $categoria, $faixa->faixa)->shuffle();
                foreach ($competidores as $keyc => $competidor) {
                    
                    $grupo = 1;
                    if(is_int($keyc/2)){
                        $grupo = 2;
                    }
                    
                    $this->competicoesRepository->updateCompetidorCompeticao($competicao->id, $competidor->id, $categoria->id, ['grupo' => $grupo, 'ordem' => $keyc, 'vitorias' => 0,'derrota' => 0]);
                }
            }
        }

        $this->competicoesRepository->update($competicao, ['chave_gerada' => 1]);
    }

    public function getCompetidoresCategoria($competicao, $categoria, $faixa, $grupo)
    {
        return $this->competicoesRepository->getCompetidoresCategoria($competicao, $categoria, $faixa, $grupo);
    }

    public function vencedorRetorno($dados)
    {
        if($dados['tipo']){
            $dados['vitorias'] += 1;
        }else{
            $dados['vitorias'] -= 1;
        }

        $this->competicoesRepository->updateCompetidorCompeticao($dados['competicao_id'], $dados['competidor_id'], $dados['categoria_id'], ['vitorias' => $dados['vitorias']]);
        
        return $this->competicoesRepository->firstCompetidoresCategoria($dados['competicao_id'], $dados['competidor_id'], $dados['categoria_id']);
    }
    
    public function setDerrotaCompetidor($dados)
    {
        if($dados['derrota']){
            $dados['derrota'] = 1;
        }else{
            $dados['derrota'] = 0;
        }

        $this->competicoesRepository->updateCompetidorCompeticao($dados['competicao_id'], $dados['competidor_id'], $dados['categoria_id'], ['derrota' => $dados['derrota']]);
        
        return $this->competicoesRepository->firstCompetidoresCategoria($dados['competicao_id'], $dados['competidor_id'], $dados['categoria_id']);
    }
}