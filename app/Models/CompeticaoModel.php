<?php

namespace App\Models;

use App\Support\TraitLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompeticaoModel extends Model
{
    use TraitLog, SoftDeletes;

    protected $table = "competicoes";

    protected $fillable = [
        'descricao',
        'data_evento',
        'chave_gerada',
    ];

    protected $casts = [
        'chave_gerada' => 'boolean'
    ];

    function competidores()
    {
        return $this->belongsToMany(CompetidorModel::class, 'competicao_has_competidores', 'competicao_id', 'competidor_id')->withPivot('categoria_id', 'faixa', 'grupo', 'ordem', 'vitorias');
    }

    function categorias()
    {
        return $this->belongsToMany(CategoriaModel::class, 'competicao_has_competidores', 'competicao_id', 'categoria_id')->withPivot('competidor_id', 'faixa', 'grupo', 'ordem', 'vitorias');
    }

    function categoriasWithOutPivot()
    {
        return $this->belongsToMany(CategoriaModel::class, 'competicao_has_competidores', 'competicao_id', 'categoria_id');
    }
}
