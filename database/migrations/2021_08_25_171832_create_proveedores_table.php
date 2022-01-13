<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->char('name', 100);
            $table->char('last_name', 100);
            $table->char('type_document', 40)->default('Cédula de ciudadanía');
            $table->double('document', 20, 0);
            $table->char('gender', 20)->nullable();
            $table->char('phone_1', 20);
            $table->char('phone_2', 20)->nullable();
            $table->char('address', 200)->nullable();
            $table->string('email')->unique();
            $table->tinyInteger('status')->default(1);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proveedores');
    }
}
