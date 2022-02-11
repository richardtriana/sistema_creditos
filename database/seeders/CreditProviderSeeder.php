<?php

namespace Database\Seeders;

use App\Models\CreditProvider;
use Illuminate\Database\Seeder;

class CreditProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CreditProvider::factory()
            ->count(1)
            ->create();
    }
}
