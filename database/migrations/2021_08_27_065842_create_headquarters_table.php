<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeadquartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headquarters', function (Blueprint $table) {
            $table->id();
            $table->string('headquarter');
            $table->tinyInteger('status')->default(1);
            $table->string('address')->nullable();
            $table->string('nit')->nullable();
            $table->string('email')->nullable();
            $table->string('legal_representative')->nullable();
            $table->string('phone')->nullable();
            $table->string('pos_printer')->default('POS-80');
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
        Schema::dropIfExists('headquarters');
    }
}
