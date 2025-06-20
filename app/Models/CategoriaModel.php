<?php

namespace App\Models;

use App\Support\TraitLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaModel extends Model
{
    use TraitLog, SoftDeletes;
    use HasFactory;

    protected $table = "categorias";

    protected $fillable = [
        'id',
        'nome',
        'idade_inicio',
        'idade_fim',
        'peso_inicio',
        'peso_fim',
        'sexo',
    ];

}
