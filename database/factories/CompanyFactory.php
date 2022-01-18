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
            'name' => 'Tecnoplus',
            'legal_representative' => 'Richard Arturo Peña',
            'nit' => '123459789-0',
            'address' => 'Añadir direccion',
            'email' => 'empresa@nombredominio.com',
            'logo' => 'images/logo.jpeg'
        ];
    }
}
