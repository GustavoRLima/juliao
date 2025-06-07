<?php

namespace App\Repository;

use App\Models\CompeticaoModel;
use Illuminate\Support\Facades\DB;

class CompeticoesRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new CompeticaoModel());
    }

    public function getCompeticoes($dados)
    {
        return $this->query->select('id', 'descricao', DB::raw('DATE_FORMAT(data_evento, "%d/%m/%Y" ) as data_evento'))
            ->when(isset($dados['search']), function($q) use($dados){
                $q->where(function($q) use($dados){
                    $q->where('id', $dados['search'])
                    ->orWhere('descricao', 'like', "{$dados['search']}%");
                });
            })
            ->orderBy('data_evento', 'DESC');
    }
}