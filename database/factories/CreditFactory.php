<?php

namespace Database\Factories;

use App\Models\Credit;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreditFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Credit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => $this->faker->numberBetween(1, 10),
            'deudor_id' => $this->faker->numberBetween(1, 10),
            'sede_id' => $this->faker->numberBetween(1, 3),
            'cant_cuotas' => $this->faker->randomNumber(),
            'cant_cuotas_pagadas' => $this->faker->randomNumber(),
            'dia_limite' => $this->faker->randomNumber(),
            'deudor' => $this->faker->boolean(),
            'status' => $this->faker->boolean(),
            'fecha_inicio' => $this->faker->date('Y-m-d'),
            'interes' => $this->faker->randomNumber(),
            'porcentaje_interes_anual' => $this->faker->randomNumber(),
            'interes' => $this->faker->randomNumber(),
            'usu_crea' => $this->faker->numberBetween(1, 3),
            'valor_cuota' => $this->faker->randomNumber(),
            'valor_credit' => $this->faker->randomNumber(),
            'valor_abonado' => $this->faker->randomNumber(),
            'capital_value' => $this->faker->randomNumber(),
            'interest_value' => $this->faker->randomNumber(),
        ];
    }
}
