<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product');
            $table->boolean('state')->default(1);
            $table->timestamps();
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['guard_name' => 'api', 'name' => 'product.index', 'description' => 'Listar', 'component' => 'Producto']);
        Permission::create(['guard_name' => 'api', 'name' => 'product.store', 'description' => 'Crear', 'component' => 'Producto']);
        Permission::create(['guard_name' => 'api', 'name' => 'product.update', 'description' => 'Editar', 'component' => 'Producto']);
        Permission::create(['guard_name' => 'api', 'name' => 'product.delete', 'description' => 'Eliminar', 'component' => 'Producto']);
        Permission::create(['guard_name' => 'api', 'name' => 'product.status', 'description' => 'Activar', 'component' => 'Producto']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
