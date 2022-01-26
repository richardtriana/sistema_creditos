<?php

namespace Database\Seeders;

use App\Models\MainBox;
use Illuminate\Database\Seeder;

class MainBoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MainBox::factory()
			->count(10)
			->create();
    }
}
