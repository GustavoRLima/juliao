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
            'faixa' => $this->faker->randomElement($faixa)
        ];
    }
}
