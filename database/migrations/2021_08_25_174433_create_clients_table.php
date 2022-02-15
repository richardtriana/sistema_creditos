<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('clients', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('last_name');
      $table->char('type_document')->default('CC');
      $table->string('document', 20);
      $table->string('phone_1')->default(null)->nullable();
      $table->string('phone_2')->default(null)->nullable();
      $table->string('address')->default(null)->nullable();
      $table->string('email')->default(null)->nullable();
      $table->date('birth_date')->default(null)->nullable();
      $table->tinyText('gender')->default(null)->nullable();
      $table->tinyInteger('status')->default(1);
      $table->string('civil_status')->default(null)->nullable();
      $table->string('workplace')->default(null)->nullable();
      $table->string('occupation')->default(null)->nullable();
      $table->tinyInteger('independent')->default(null)->nullable();
      $table->string('photo')->default(null)->nullable();
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
    Schema::dropIfExists('clients');
  }
}
