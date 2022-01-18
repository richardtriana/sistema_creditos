<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompanySeeder::class);
        $this->call(HeadquarterSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(ProveedorSeeder::class);
        $this->call(CreditSeeder::class);
    }
}
