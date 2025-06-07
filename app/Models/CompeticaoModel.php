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
}
