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
        'peso'
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

    public function getCompetidoresCompeticao()
    {
        return $this->belongsToMany(CompeticaoModel::class, 'competicao_has_competidores', 'competidor_id', 'competicao_id')->withPivot('categoria_id', 'faixa');
    }

    public function categoria()
    {
        return $this->belongsToMany(CategoriaModel::class, 'competicao_has_competidores', 'competidor_id', 'categoria_id')->withPivot('competicao_id', 'faixa');
    }

}
