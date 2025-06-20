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

    public function getCompetidores($competicao)
    {
        return $competicao->loadMissing(['competidores' => function($q) {
            $q->select("nome", "id", "peso", "faixa", "categoria", "sexo")
            ->withPivot('categoria_id');
        }]);
    }

    public function excluirCompetidor($competicao_id, $competidor_id, $categoria_id)
    {
        return DB::table('competicao_has_competidores')
        ->where('competicao_id', $competicao_id)
        ->where('competidor_id', $competidor_id)
        ->where('categoria_id', $categoria_id)
        ->delete();
    }

    public function verificaExisteCompetidor($dados, $competicao)
    {
        return DB::table('competicao_has_competidores')
        ->where('competicao_id', $competicao->id)
        ->where('competidor_id', $dados['competidor_id'])
        ->where('categoria_id', $dados['categoria_id'])
        ->first();
    }

    public function addCompetidores($dados, $competicao)
    {
        return DB::table('competicao_has_competidores')
        ->where('competicao_id', $competicao->id)
        ->where('competidor_id', $dados['competidor_id'])
        ->where('categoria_id', $dados['categoria_id'])
        ->first();
    }
}