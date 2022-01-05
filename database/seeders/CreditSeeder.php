<?php

namespace Database\Seeders;

use App\Models\Credit;
use App\Models\Fee;
use Database\Factories\FeeFactory;
use Illuminate\Database\Seeder;

class CreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Credit::factory()
        ->has(Fee::factory()->count(3))
        ->count(10)
        ->create();
    }
}
