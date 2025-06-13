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
    ];

    function competidores()
    {
        return $this->belongsToMany(CompetidorModel::class, 'competicao_has_competidores', 'competicao_id', 'competidor_id')->withPivot('categoria_competicao');
    }
}
