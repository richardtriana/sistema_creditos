<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->default(null)->nullable();
            $table->string('phone')->default(null)->nullable();
            $table->string('address')->default(null)->nullable();
            $table->string('type_document')->default(null)->nullable();
            $table->string('document')->default(null)->nullable();
            $table->string('photo')->default(null)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->foreignId('rol_id')->default(1);
            $table->foreignId('headquarter_id')->default(1)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
