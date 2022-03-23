<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('legal_representative',);
            $table->string('nit', 15);
            $table->string('address', 150)->default('Sin dirección');
            $table->string('email', 150)->nullable();
            $table->string('telephone', 15)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('condition_order', 300)->nullable();
            $table->string('condition_quotation', 300)->nullable();
            $table->string('whatsapp_msg', 300)->nullable();
            $table->text('logo')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
