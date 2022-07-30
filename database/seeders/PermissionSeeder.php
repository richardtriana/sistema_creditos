<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // $admin = Role::create(['guard_name' => 'api', 'name' => 'Admin']);

    Permission::create(['guard_name' => 'api', 'name' => 'installment.reverse', 'description' => 'Rerversar pago de cuota', 'component' => 'Cuotas']);
  }
}
