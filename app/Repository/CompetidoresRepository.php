<?php

namespace App\Repository;

use App\Models\CompetidorModel;
use Illuminate\Support\Facades\DB;

class CompetidoresRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new CompetidorModel());
    }

    public function getCompetidores($dados)
    {
        $search = null;
        if(count($dados) > 0 && (isset($dados['search']) || isset($dados['query']))){
            $search = $dados['search'] ?? $dados['query'];
        }

        return $this->query->select('id', 'nome', 'faixa', 'idade', 'peso')
            ->when(!empty($search), function($q) use($search){
                $q->where(function($q) use($search){
                    $q->where('id', $search)
                    ->orWhere('nome', 'like', "{$search}%");
                });
            })
            ->orderBy('nome', 'ASC');
    }

    public function getCompetidoresCompeticao($competicao_id, $filtros)
    {
        $faixas = CompetidorModel::getFaixas();

        return DB::table('competicao_has_competidores as ch')
        ->join('competidores as c', 'c.id', '=', 'ch.competidor_id')
        ->join('categorias as cat', 'cat.id', '=', 'ch.categoria_id')
        ->where('ch.competicao_id', $competicao_id)
        ->select(
            'c.id as competidor_id',
            'c.nome',
            'c.idade',
            'c.peso',
            'ch.faixa as faixa_pivot',
            'cat.id as categoria_id',
            'cat.nome as categoria_nome'
        )
        ->when(isset($filtros['search']), function ($q) use($filtros){
            $q->where('c.nome', 'like', "{$filtros['search']}%");
        })
        ->orderBy('c.nome', 'ASC')
        ->paginate(15)
        ->through(function ($item) use ($faixas) {
            $item->faixa_name = $faixas[$item->faixa_pivot] ?? 'Faixa desconhecida';
            return $item;
        });
    }

    public function getFaixas() 
    {
        return $this->model->getFaixas();
    }
}