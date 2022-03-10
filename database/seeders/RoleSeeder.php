<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['guard_name' => 'api','name' => 'Admin']); 

        Permission::create(['guard_name' => 'api', 'name' => 'credit.index', 'description' => 'Listar', 'component' => 'Créditos'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'credit.store', 'description' => 'Crear', 'component' => 'Créditos'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'credit.update', 'description' => 'Editar', 'component' => 'Créditos'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'credit.delete', 'description' => 'Eliminar', 'component' => 'Créditos'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'credit.status', 'description' => 'Activar', 'component' => 'Créditos'])->syncRoles([$admin]);

        Permission::create(['guard_name' => 'api', 'name' => 'expense.index', 'description' => 'Listar', 'component' => 'Egreso'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'expense.store', 'description' => 'Crear', 'component' => 'Egreso'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'expense.update', 'description' => 'Editar', 'component' => 'Egreso'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'expense.delete', 'description' => 'Eliminar', 'component' => 'Egreso'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'expense.status', 'description' => 'Activar', 'component' => 'Egreso'])->syncRoles([$admin]);
       
        Permission::create(['guard_name' => 'api', 'name' => 'client.index', 'description' => 'Listar', 'component' => 'Clientes'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'client.store', 'description' => 'Crear', 'component' => 'Clientes'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'client.update', 'description' => 'Editar', 'component' => 'Clientes'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'client.delete', 'description' => 'Eliminar', 'component' => 'Clientes'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'client.status', 'description' => 'Activar', 'component' => 'Clientes'])->syncRoles([$admin]);

        Permission::create(['guard_name' => 'api', 'name' => 'provider.index', 'description' => 'Listar', 'component' => 'Proveedores'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'provider.store', 'description' => 'Crear', 'component' => 'Proveedores'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'provider.update', 'description' => 'Editar', 'component' => 'Proveedores'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'provider.delete', 'description' => 'Eliminar', 'component' => 'Proveedores'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'provider.status', 'description' => 'Activar', 'component' => 'Proveedores'])->syncRoles([$admin]);
        
        Permission::create(['guard_name' => 'api', 'name' => 'headquarter.index', 'description' => 'Listar', 'component' => 'Sedes'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'headquarter.store', 'description' => 'Crear', 'component' => 'Sedes'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'headquarter.update', 'description' => 'Editar', 'component' => 'Sedes'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'headquarter.status', 'description' => 'Activar', 'component' => 'Sedes'])->syncRoles([$admin]);
        
        Permission::create(['guard_name' => 'api', 'name' => 'user.index', 'description' => 'Listar', 'component' => 'Usuarios'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'user.store', 'description' => 'Crear', 'component' => 'Usuarios'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'user.update', 'description' => 'Editar', 'component' => 'Usuarios'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'user.delete', 'description' => 'Eliminar', 'component' => 'Usuarios'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'user.status', 'description' => 'Activar', 'component' => 'Usuarios'])->syncRoles([$admin]);

        Permission::create(['guard_name' => 'api', 'name' => 'rol.index', 'description' => 'Listar', 'component' => 'Roles'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'rol.store', 'description' => 'Crear', 'component' => 'Roles'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'rol.update', 'description' => 'Editar', 'component' => 'Roles'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'rol.delete', 'description' => 'Eliminar', 'component' => 'Roles'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'rol.status', 'description' => 'Activar', 'component' => 'Roles'])->syncRoles([$admin]);

        Permission::create(['guard_name' => 'api', 'name' => 'report', 'description' => 'Generar reportes', 'component' => 'Reportes'])->syncRoles([$admin]);    
        Permission::create(['guard_name' => 'api', 'name' => 'configuration', 'description' => 'Configuración', 'component' => 'Configuracion'])->syncRoles([$admin]);
        
        Permission::create(['guard_name' => 'api', 'name' => 'box.index', 'description' => 'Acceso caja', 'component' => 'Caja'])->syncRoles([$admin]);
        Permission::create(['guard_name' => 'api', 'name' => 'box.update', 'description' => 'Editar', 'component' => 'Caja'])->syncRoles([$admin]);
        
    }
}
