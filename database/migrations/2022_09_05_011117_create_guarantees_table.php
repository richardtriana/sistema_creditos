<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class CreateGuaranteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guarantees', function (Blueprint $table) {
            $table->id();
            $table->string('guarantee');
            $table->boolean('state')->default(1);
            $table->timestamps();
        });

        Permission::create(['guard_name' => 'api', 'name' => 'guarantee.index', 'description' => 'Listar', 'component' => 'Garantía']);
        Permission::create(['guard_name' => 'api', 'name' => 'guarantee.store', 'description' => 'Crear', 'component' => 'Garantía']);
        Permission::create(['guard_name' => 'api', 'name' => 'guarantee.update', 'description' => 'Editar', 'component' => 'Garantía']);
        Permission::create(['guard_name' => 'api', 'name' => 'guarantee.delete', 'description' => 'Eliminar', 'component' => 'Garantía']);
        Permission::create(['guard_name' => 'api', 'name' => 'guarantee.status', 'description' => 'Activar', 'component' => 'Garantía']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guarantees');
    }
}
