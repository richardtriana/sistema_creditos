<?php

namespace Database\Factories;

use App\Models\Installment;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstallmentFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Installment::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'credit_id' => $this->faker->numberBetween(1, 10),
      'installment_number' => $this->faker->numberBetween(1, 10),
      'value' => $this->faker->randomFloat(4, 10, 60000),
      'payment_date' => $this->faker->date('Y-m-d'),
      'days_past_due' => $this->faker->numberBetween(1, 10),
      'late_interests_value' =>  $this->faker->randomFloat(4, 10, 60000),
      'interest_value' => $this->faker->randomFloat(4, 10, 60000),
      'capital_value' => $this->faker->randomFloat(4, 10, 60000),
      'payment_register' => $this->faker->date('Y-m-d', 'now'),
      'status' => $this->faker->boolean()
    ];
  }
}
