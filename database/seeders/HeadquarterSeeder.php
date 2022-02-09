<?php

namespace Database\Seeders;

use App\Models\Box;
use App\Models\Client;
use App\Models\Headquarter;
use App\Models\User;
use Illuminate\Database\Seeder;

class HeadquarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Headquarter::factory()
            // ->has(User::factory()->count(1))
            ->count(1)
            ->create();
    }
}
