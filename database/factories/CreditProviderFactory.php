<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CreditProviderFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition()
	{
		return [
			'credit_id' => 1,
			'provider_id' => 1,
			'headquarter_id' => 1,
			'last_editor' => 1,
			'credit_value' => 0,
			'paid_value' => 0,
			'pending_value' => 0,
			'last_paid' => $this->faker->date('Y-m-d'),
			'history' => json_encode(
				[
					'house',
					'flat',
					'apartment',
					'room', 'shop',
					'lot', 'garage'
				]

			)
		];
	}
}
