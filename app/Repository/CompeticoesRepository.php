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
        return $this->query->select('id', 'descricao', DB::raw('DATE_FORMAT(data_evento, "%d/%m/%Y" ) as data_evento'), 'chave_gerada')
            ->when(isset($dados['search']), function($q) use($dados){
                $q->where(function($q) use($dados){
                    $q->where('id', $dados['search'])
                    ->orWhere('descricao', 'like', "{$dados['search']}%");
                });
            })
            ->with('competidores')
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

    public function getCategorias($competicao)
    {
        return DB::table('competicao_has_competidores as cc')
            ->select('cc.faixa', DB::raw("COUNT(cc.competidor_id) as qtd_competidores"), 'c.nome', 'c.id')
            ->join('categorias as c', 'c.id', '=', 'cc.categoria_id')
            ->where('competicao_id', $competicao->id)
            ->whereNotNull('grupo')
            ->groupBy('cc.faixa', 'c.nome', 'c.id')
            ->get();
    }

    public function getFaixasCategoria($competicao, $categoria)
    {
        return DB::table('competicao_has_competidores')
        ->select('faixa')
        ->where('competicao_id', $competicao->id)
        ->where('categoria_id', $categoria->id)
        ->distinct()
        ->get();
    }

    public function updateCompetidorCompeticao($competicao_id, $competidor_id, $categoria_id, $data)
    {
        return DB::table('competicao_has_competidores')
        ->where('competicao_id', $competicao_id)
        ->where('categoria_id', $categoria_id)
        ->where('competidor_id', $competidor_id)
        ->update($data);
    }

    public function getCompetidoresCompeticao($competicao, $categoria, $faixa)
    {   
        return $competicao->competidores()->wherePivot('categoria_id', $categoria->id)->wherePivot('faixa', $faixa)->get();
    }

    public function getCompetidoresCategoria($competicao, $categoria, $faixa, $grupo)
    {
        return DB::table('competicao_has_competidores as cc')
            ->select('cc.faixa', 'c.nome', 'c.id', DB::raw("COALESCE(cc.vitorias, '0') as vitorias") , 'cc.grupo', 'cc.categoria_id', 'cc.competicao_id', DB::raw("COALESCE(cc.derrota, '0') as derrota"))
            ->join('competidores as c', 'c.id', '=', 'cc.competidor_id')
            ->where('competicao_id', $competicao->id)
            ->where('categoria_id', $categoria->id)
            ->where('cc.faixa', $faixa)
            ->where('cc.grupo', $grupo)
            ->orderBy('cc.ordem', 'ASC')
            ->get();
    }
   
    public function firstCompetidoresCategoria($competicao_id, $competidor_id, $categoria_id)
    {
        return DB::table('competicao_has_competidores as cc')
            ->select('cc.faixa', 'c.nome', 'c.id', DB::raw("COALESCE(cc.vitorias, '0') as vitorias") , 'cc.grupo', 'cc.categoria_id', 'cc.competicao_id', DB::raw("COALESCE(cc.derrota, '0') as derrota"))
            ->join('competidores as c', 'c.id', '=', 'cc.competidor_id')
            ->where('competicao_id', $competicao_id)
            ->where('categoria_id', $categoria_id)
            ->where('competidor_id', $competidor_id)
            ->orderBy('cc.ordem', 'ASC')
            ->first();
    }
}