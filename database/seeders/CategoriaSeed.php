<?php

namespace Database\Seeders;

use App\Models\CategoriaModel;
use App\Support\CategoriaSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class CategoriaSeed extends Seeder
{
    use CategoriaSupport;

    protected $categorias = [
        'galo',
        'pluma',
        'pena',
        'leve',
        'médio',
        'meio_pesado',
        'pesado',
        'super_pesado',
        'pesadíssimo',
        'super_pesadíssimo',
    ];


    protected $idades = [
        [4, 5],
        [6, 7],
        [8, 9],
        [10, 11],
        [12, 13],
        [14, 15],
        [16, 17, 3],
        [16, 17, 2],
        [18, 29, 3],
        [18, 29, 2],
        [30, 100, 3],
        [30, 100, 2],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (count(CategoriaModel::get()) == 0) {
            $this->salvarCategoriasPeso($this->pesoCategorias);
        }
    }

    function salvarCategoriasPeso(array $categoriasPeso)
    {
        foreach ($categoriasPeso as $faixaEtaria => $grupos) {

            foreach ($grupos as $faixaIdade => $dados) {

                // Para Juvenil e Adulto/Masters (onde tem separação de sexo)
                if (in_array($faixaEtaria, ['juvenil', 'adultos', 'masters'])) {
                    foreach ($dados as $sexo => $categorias) {
                        if(!is_array($categorias)) dd($faixaEtaria, $dados, $grupos, $categorias);
                        foreach ($categorias as $categoriaNome => $pesoMax) {
                            if (is_null($pesoMax)) continue;
                            $next = $this->getNext($categoriaNome);
                            $pesoPosterior = 200;
                            if($next && isset($categorias[$next])){
                                $pesoPosterior = $categorias[$next];
                            }

                            $data[] = [
                                'nome' => Str::title($categoriaNome) . ' ' . Str::title(str_replace('_', ' ', $faixaEtaria)),
                                'idade_inicio' => (int) explode('-', $faixaIdade)[0],
                                'idade_fim' => (int) explode('-', $faixaIdade)[1],
                                'peso_inicio' => $pesoMax,
                                'peso_fim' => $pesoPosterior,
                                'sexo' => match ($sexo) {
                                    'feminino' => 3,
                                    'masculino' => 2,
                                    default => 1,
                                },
                            ];

                        }
                    }
                } else {
                    // Para faixas sem separação de sexo (ambos = 1)
                    $pesoPosterior = 0;
                    foreach ($dados as $categoriaNome => $pesoMax) {
                        if (is_null($pesoMax)) continue;
                        $next = $this->getNext($categoriaNome);
                        $pesoPosterior = 200;
                        if($next && isset($dados[$next])){
                            $pesoPosterior = $dados[$next];
                        }

                        $data[] = [
                            'nome' => Str::title($categoriaNome) . ' ' . Str::title(str_replace('_', ' ', $faixaEtaria)),
                            'idade_inicio' => (int) explode('-', $faixaIdade)[0],
                            'idade_fim' => (int) explode('-', $faixaIdade)[1],
                            'peso_inicio' => $pesoMax,
                            'peso_fim' => $pesoPosterior,
                            'sexo' => 1, // Ambos
                        ];
                    }
                }
            }
        }

        $data[] = [
            'nome' => "Absoluto Juvenil F",
            'idade_inicio' => 16,
            'idade_fim' => 17,
            'peso_inicio' => 60.5,
            'peso_fim' => 200,
            'sexo' => 3,
        ];
        $data[] = [
            'nome' => "Absoluto Juvenil M",
            'idade_inicio' => 16,
            'idade_fim' => 17,
            'peso_inicio' => 74,
            'peso_fim' => 200,
            'sexo' => 2,
        ];
        $data[] = [
            'nome' => "Absoluto Adultos F",
            'idade_inicio' => 18,
            'idade_fim' => 29,
            'peso_inicio' => 69,
            'peso_fim' => 200,
            'sexo' => 3,
        ];
        $data[] = [
            'nome' => "Absoluto Adultos M",
            'idade_inicio' => 18,
            'idade_fim' => 29,
            'peso_inicio' => 82.3,
            'peso_fim' => 200,
            'sexo' => 2,
        ];
        $data[] = [
            'nome' => "Absoluto Masters F",
            'idade_inicio' => 30,
            'idade_fim' => 100,
            'peso_inicio' => 69,
            'peso_fim' => 200,
            'sexo' => 3,
        ];
        $data[] = [
            'nome' => "Absoluto Masters M",
            'idade_inicio' => 30,
            'idade_fim' => 100,
            'peso_inicio' => 82.3,
            'peso_fim' => 200,
            'sexo' => 2,
        ];

        
        CategoriaModel::insert($data);
        
    }

    function getNext($position)
    {
        $key = array_search($position, $this->categorias);
        if(isset($this->categorias[$key+1])){
            return $this->categorias[$key+1];
        }

        return false;
    }
}
