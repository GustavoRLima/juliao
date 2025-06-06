<?php

namespace App\Models;

use App\Support\TraitLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetidorModel extends Model
{
    use TraitLog, SoftDeletes;

    protected $table = "competidores";

    protected $fillable = [
        'nome',
        'sexo',
        'data_nascimento',
        'idade',
        'faixa',
        'peso',
        'categoria_filter',
        'categoria'
    ];
}
