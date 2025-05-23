<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    protected Model $model;
    protected $query;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->query = $model->newQuery();
    }

    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        $this->model->create($data);
    }

    public function update(Model|int $id, array $dados)
    {
        $model = $id instanceof Model ? $id : $this->model->findOrFail($id);

        $model->fill($dados);

        if ($model->isDirty()) {
            $model->save();
        }

        return $model;
    }

    public function excluir(Model|int $id)
    {
        $model = $id instanceof Model ? $id : $this->model->findOrFail($id);

        return $model->delete();
    }

    public function logText($model, $texto, $dados)
    {        
        return $model->criarLog(request()->user(), $texto, $dados);
    }
}