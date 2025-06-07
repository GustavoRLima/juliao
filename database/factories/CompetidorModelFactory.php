<?php

namespace Database\Factories;

use App\Models\CompetidorModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CompetidorModelFactory extends Factory
{

    protected $model = CompetidorModel::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $dataNascimento = $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d');
        $peso = $this->faker->numberBetween(60, 120);
        $idade = \Carbon\Carbon::parse($dataNascimento)->age;
        $faixa = ['branca', 'azul', 'roxa', 'marrom', 'preta', 'coral_vermelha'];

        return [
            'nome' => $this->faker->name(),
            'idade' => $idade,
            'data_nascimento' => $dataNascimento,
            'sexo' => 1,
            'peso' => $peso,
            'faixa' => $this->faker->randomElement($faixa),
            'categoria' => $this->getCategoria($idade)
        ];
    }

    public function getCategoria($idade) {
        if(in_array($idade, [4,5])) return 'PRÉ-MIRIM';
        if(in_array($idade, [6,7])) return 'MIRIM 1';
        if(in_array($idade, [8,9])) return 'MIRIM 2';
        if(in_array($idade, [10,11])) return 'INFANTIL 1';
        if(in_array($idade, [12,13])) return 'INFANTIL 2';
        if(in_array($idade, [14,15])) return 'INFANTO-JUVENIL';
        if(in_array($idade, [16,17])) return 'JUVENIL';
        if($idade >= 18 && $idade <= 29) return 'ADULTOS';
        if($idade >= 30) return 'MASTERS';
    }
}
