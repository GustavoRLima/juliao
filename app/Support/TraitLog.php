<?php

namespace App\Support;

use App\Models\LogModel;
use Illuminate\Database\Eloquent\Model;

trait TraitLog 
{
    public static function bootTraitLog()
    {        
        $usuario_logado = request()->user();

        static::created(function (Model $model) use($usuario_logado) {
            $model->criarLogCadastro($usuario_logado);
        });

        static::updated(function (Model $model) use($usuario_logado) {
            $model->criarLogEdicao($usuario_logado);
        });

        static::deleted(function (Model $model) use($usuario_logado) {
            $model->criarLogExclusao($usuario_logado);
        });
    }

    public function logs() {
        return $this->morphMany(LogModel::class, "origem");
    }

    public function criarLog($usuario, $descricao, $dados = null) {
        return $this->logs()->create([
            'user_id' => $usuario->id,
            'user_type' => get_class($usuario),
            'descricao' => $descricao,
            'dados' => $dados,
        ]);
    }

    public function criarLogCadastro($usuario) {
        return $this->criarLog($usuario, 'Cadastro', $this->getAttributes());
    }

    public function criarLogEdicao($usuario) {
        return $this->criarLog($usuario, 'Edição', $this->getChanges());
    }

    public function criarLogExclusao($usuario) {
        return $this->criarLog($usuario, 'Exclusão', $this->getChanges());
    }
}