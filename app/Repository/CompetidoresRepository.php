<?php

namespace App\Repository;

use App\Models\CompetidorModel;

class CompetidoresRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new CompetidorModel());
    }

    public function getCompetidores($dados)
    {
        return $this->query->select('id', 'nome', 'faixa', 'idade', 'peso')
            ->when(isset($dados['search']), function($q) use($dados){
                $q->where(function($q) use($dados){
                    $q->where('id', $dados['search'])
                    ->orWhere('nome', 'like', "{$dados['search']}%");
                });
            })
            ->orderBy('nome', 'ASC');
    }

    public function getFaixas() 
    {
        return $this->model->getFaixas();
    }
}