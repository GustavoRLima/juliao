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
        return $this->query->select('id', 'nome', 'faixa')
            ->when(isset($dados['nome']), function($q) use($dados){
                $q->where(function($q) use($dados){
                    $q->where('id', $dados['nome'])
                    ->orWhere('nome', $dados['nome']);
                });
            });
    }
}