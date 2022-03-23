<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    User::create([
      'id' => 1,
      'name' => 'Richard',
      'last_name' => 'Peña',
      'email' => 'richardpen90@gmail.com',
      'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
      'type_document' => 'CC',
      'document' => 000001,
      'headquarter_id' => 1
    ]);

    User::find(1)->assignRole('admin');
  }
}
