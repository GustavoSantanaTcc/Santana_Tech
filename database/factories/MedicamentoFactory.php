<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MedicamentoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => $this->faker->name,
            'fabricante' => $this->faker->name,
            'lote' => $this->faker->randomNumber(5),
            'validade' => $this->faker->date(),
            'quantidade' => $this->faker->randomNumber(2),
            'tipo' => $this->faker->name,
            'descricao' => $this->faker->name,
        ];
    }
}