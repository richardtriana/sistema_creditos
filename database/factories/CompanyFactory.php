<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company' => $this->faker->company(),
            'status' => $this->faker->boolean(),
            'address' => $this->faker->address(),
            'nit' => $this->faker->ean13(),
            'email' => $this->faker->email(),
            'legal_representative' => $this->faker->firstNameFemale(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
