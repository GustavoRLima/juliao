<?php

namespace App\Models;

use App\Support\TraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetidorModel extends Model
{
    use TraitLog, SoftDeletes;
    use HasFactory;

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

    protected $appends = [
        'faixa_name'
    ];

    protected const faixas = [
        "branca" => "Branca" ,
        'amarela' => "Amarela",
        'laranja' => "Laranja",
        'verde' => "Verde",
        'azul' => "Azul",
        'roxa' => "Roxa",
        'marrom' => "Marrom",
        'preta' => "Preta",
        'coral_vermelha' => "Coral e vermelha",
    ];

    public static function getFaixas()
    {
        return self::faixas;
    }

    public function getFaixaNameAttribute() : string
    {
        return self::faixas[$this->faixa];
    }

}
