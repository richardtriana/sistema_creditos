<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MainBoxFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'initial_balance' => $this->faker->randomNumber(),
      'current_balance' => $this->faker->randomNumber(),
      'input' => $this->faker->randomNumber(),
      'output' => $this->faker->randomNumber(),
      'history' => $this->faker->text(200),
      'last_update' => $this->faker->date('Y-m-d'),
      'last_editor'  => $this->faker->numberBetween(1, 10)
    ];
  }
}
