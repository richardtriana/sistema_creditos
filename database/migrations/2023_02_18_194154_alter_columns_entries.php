<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class AlterColumnsEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->foreignId('credit_id')->nullable()->change();
        });

        Permission::create(['guard_name' => 'api', 'name' => 'entry.index', 'description' => 'Listar', 'component' => 'Ingreso']);
        Permission::create(['guard_name' => 'api', 'name' => 'entry.store', 'description' => 'Crear', 'component' => 'Ingreso']);
        Permission::create(['guard_name' => 'api', 'name' => 'entry.update', 'description' => 'Editar', 'component' => 'Ingreso']);
        Permission::create(['guard_name' => 'api', 'name' => 'entry.delete', 'description' => 'Eliminar', 'component' => 'Ingreso']);
        Permission::create(['guard_name' => 'api', 'name' => 'entry.status', 'description' => 'Activar', 'component' => 'Ingreso']);
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
