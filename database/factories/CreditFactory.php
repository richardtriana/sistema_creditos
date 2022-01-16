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
            'user_id' => $this->faker->numberBetween(1, 10),
            'debtor_id' => $this->faker->numberBetween(1, 10),
            'headquarter_id' => $this->faker->numberBetween(1, 3),
            'number_installments' => $this->faker->randomNumber(1,36),
            'number_paid_installments' => $this->faker->randomNumber(1, 36),
            'day_limit' => $this->faker->randomNumber(),
            'debtor' => $this->faker->boolean(),
            'status' => $this->faker->boolean(),
            'start_date' => $this->faker->date('Y-m-d'),
            'interest' => $this->faker->randomNumber(),
            'annual_interest_percentage' => $this->faker->randomNumber(),
            'interest' => $this->faker->randomNumber(),
            'installment_value' => $this->faker->randomNumber(),
            'credit_value' => $this->faker->randomNumber(),
            'paid_value' => $this->faker->randomNumber(),
            'capital_value' => $this->faker->randomNumber(),
            'interest_value' => $this->faker->randomNumber(),
        ];
    }
}
