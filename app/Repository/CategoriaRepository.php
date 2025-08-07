<?php

namespace App\Repository;

use App\Models\CategoriaModel;

class CategoriaRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new CategoriaModel());
    }

    public function getCategorias($dados)
    {
        return $this->query->select('id', 'nome', 'idade_inicio', 'idade_fim', 'peso_inicio', 'peso_fim', 'sexo')
            ->when(isset($dados['search']), function($q) use($dados){
                $q->where(function($q) use($dados){
                    if (is_numeric($dados['search'])) {
                        $q->where('id', $dados['search']);
                    }
                    $q->orWhere('nome', 'like', "{$dados['search']}%");
                });
            })
            ->orderBy('nome', 'ASC');
    }

    public function getCategoriasCompetidores($idade, $peso, $sexo)
    {
        return $this->query->select('id', 'nome as name', 'idade_inicio', 'idade_fim', 'peso_inicio', 'peso_fim', 'sexo')
            ->where('idade_inicio', '<=', $idade)
            ->where('idade_fim', '>=', $idade)
            ->where('peso_inicio', '<=', $peso)
            ->where('peso_fim', '>=', $peso)
            ->where(function($q) use($sexo){
                $q->where('sexo', $sexo)
                ->orWhere('sexo', 1);
            })
            ->orderBy('name', 'ASC');
    }

}