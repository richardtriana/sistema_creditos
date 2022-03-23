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
      'initial_balance' => 0,
      'current_balance' => 0,
      'input' => 0,
      'output' => 0,
      'history' => $this->faker->text(200),
      'last_update' => $this->faker->date('Y-m-d'),
      'last_editor'  => 1
    ];
  }
}
