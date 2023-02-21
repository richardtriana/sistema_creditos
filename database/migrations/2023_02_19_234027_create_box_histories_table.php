<?php

use App\Models\Box;
use App\Models\BoxHistory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('box_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('box_id')->nullable();
            $table->string('user')->nullable();
            $table->string('date')->nullable();
            $table->float('value')->nullable();
            $table->string('description')->nullable();
            $table->float('cash')->nullable();
            $table->float('consignment_to_client')->nullable();
            $table->float('payment_to_provider')->nullable();
            $table->string('status')->nullable();
            $table->string('observations')->nullable();
            $table->timestamps();

            $table->foreign('box_id')
                ->references('id')
                ->on('boxes');
        });

        $boxes = Box::all();

        foreach ($boxes as $box) {
            $history = (array) json_decode($box->history);
            foreach ($history as $h) {
                $boxHistory = new BoxHistory();
                $boxHistory->box_id       = $box->id;
                $boxHistory->user       = $h->user;
                $boxHistory->date       = $h->date;
                $boxHistory->value      = $h->value;
                $boxHistory->description = $h->description;
                $boxHistory->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('box_histories');
    }
}
