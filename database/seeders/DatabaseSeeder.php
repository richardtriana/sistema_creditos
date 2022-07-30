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
    // $this->call(CompanySeeder::class);
    // $this->call(HeadquarterSeeder::class);
    // $this->call(RoleSeeder::class);
    // $this->call(UserSeeder::class);
    // $this->call(MainBoxSeeder::class);
    // $this->call(BoxSeeder::class);
    $this->call(PermissionSeeder::class);

  }
}
