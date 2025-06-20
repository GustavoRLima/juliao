<?php

namespace App\Support;

use Exception;
use Illuminate\Database\Eloquent\Model;

trait CategoriaSupport
{

    protected $pesoCategorias = [
        'pré_mirim' => [
            '4-5' => [
                'galo' => null,
                'pluma' => 17.900,
                'pena' => 20.000,
                'leve' => 23.000,
                'médio' => 26.000,
                'meio_pesado' => 29.000,
                'pesado' => 32.000,
                'super_pesado' => 35.000,
                'pesadíssimo' => 35.000,
            ],
        ],
        'mirim_1' => [
            '6-7' => [
                'galo' => null,
                'pluma' => 21,
                'pena' => 24,
                'leve' => 27.2,
                'médio' => 30.2,
                'meio_pesado' => 33.2,
                'pesado' => 36.2,
                'super_pesado' => 39.3,
                'pesadíssimo' => 39.3,
            ],
        ],
        'mirim_2' => [
            '8-9' => [
                'galo' => 24,
                'pluma' => 27,
                'pena' => 30.2,
                'leve' => 33.2,
                'médio' => 36.2,
                'meio_pesado' => 39.3,
                'pesado' => 42.3,
                'super_pesado' => 45.3,
                'pesadíssimo' => 45.3,
            ],
        ],
        'infantil_1' => [
            '10-11' => [
                'galo' => 32.2,
                'pluma' => 36.2,
                'pena' => 40.3,
                'leve' => 44.3,
                'médio' => 48.3,
                'meio_pesado' => 52.5,
                'pesado' => 56.5,
                'super_pesado' => 60.5,
                'pesadíssimo' => 60.5,
            ],
        ],
        'infantil_2' => [
            '12-13' => [
                'galo' => 36.2,
                'pluma' => 40.3,
                'pena' => 44.3,
                'leve' => 48.3,
                'médio' => 52.5,
                'meio_pesado' => 56.5,
                'pesado' => 60.5,
                'super_pesado' => 65,
                'pesadíssimo' => 65,
            ],
        ],
        'infanto_juvenil' => [
            '14-15' => [
                'galo' => 44.3,
                'pluma' => 48.3,
                'pena' => 52.3,
                'leve' => 56.5,
                'médio' => 60.5,
                'meio_pesado' => 65,
                'pesado' => 69,
                'super_pesado' => 73,
                'pesadíssimo' => 73,
            ],
        ],
        'juvenil' => [
            '16-17' => [
                'feminino' => [
                    'galo' => 44.3,
                    'pluma' => 48.3,
                    'pena' => 52.3,
                    'leve' => 56.5,
                    'médio' => 60.5,
                    'meio_pesado' => 65,
                    'pesado' => 69,
                    'super_pesado' => 73,
                    'pesadíssimo' => 77,
                    'super_pesadíssimo' => 77,
                ],
                'masculino' => [
                    'galo' => 53.5,
                    'pluma' => 58.5,
                    'pena' => 64,
                    'leve' => 69,
                    'médio' => 74,
                    'meio_pesado' => 79.3,
                    'pesado' => 84.3,
                    'super_pesado' => 89.3,
                    'pesadíssimo' => 94.3,
                    'super_pesadíssimo' => 94.3,
                ],
            ],
        ],
        'adultos' => [
            '18-29' => [
                'feminino' => [
                    'galo' => 48.5,
                    'pluma' => 53.5,
                    'pena' => 58.5,
                    'leve' => 64,
                    'médio' => 69,
                    'meio_pesado' => 74,
                    'pesado' => 79.3,
                    'super_pesado' => 84.3,
                    'pesadíssimo' => 100.5,
                    'super_pesadíssimo' => 100.5,
                ],
                'masculino' => [
                    'galo' => 57.5,
                    'pluma' => 64,
                    'pena' => 70,
                    'leve' => 76,
                    'médio' => 82.3,
                    'meio_pesado' => 88.3,
                    'pesado' => 94.3,
                    'super_pesado' => 100.5,
                    'pesadíssimo' => 116.5,
                    'super_pesadíssimo' => 116.5,
                ],
            ],
        ],
        'masters' => [
            '30-100' => [
                'feminino' => [
                    'galo' => 48.5,
                    'pluma' => 53.5,
                    'pena' => 58.5,
                    'leve' => 64,
                    'médio' => 69,
                    'meio_pesado' => 74,
                    'pesado' => 79.3,
                    'super_pesado' => 84.3,
                    'pesadíssimo' => 100.5,
                    'super_pesadíssimo' => 100.5,
                ],
                'masculino' => [
                    'galo' => 57.5,
                    'pluma' => 64,
                    'pena' => 70,
                    'leve' => 76,
                    'médio' => 82.3,
                    'meio_pesado' => 88.3,
                    'pesado' => 94.3,
                    'super_pesado' => 100.5,
                    'pesadíssimo' => 116.5,
                    'super_pesadíssimo' => 116.5,
                ],
            ]
        ],
    ];



    // function getCategoria($faixa_etaria, $categoria, $idade, $sexo = null)
    // {
    //     $categoria_use = $categoria == "adulto"
    //     if($sexo){
    //         return $this->pesoCategorias[$faixa_etaria][$idade][$sexo][$categoria_use];
    //     }
    //     return $this->pesoCategorias[$faixa_etaria][$idade][$categoria_use];
    // }
}
