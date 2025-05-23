<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'user_type',
        'descricao',
        'dados',
        'origem_type',
        'origem_id',
    ];

    protected $casts = [
        'dados' => 'array',
    ];

    protected $hidden = [
        'dados',
    ];

    public function user() {
        return $this->morphTo();
    }

    public function origem() {
        return $this->morphTo();
    }
}
