<?php

use App\Models\MainBox;
use App\Models\MainBoxHistory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainBoxHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('main_box_histories')) {
            Schema::create('main_box_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('main_box_id')->nullable();
                $table->string('user')->nullable();
                $table->string('date')->nullable();
                $table->float('value', 25, 2)->nullable();
                $table->string('description')->nullable();
                $table->float('cash', 25, 2)->nullable();
                $table->float('consignment_to_client', 25, 2)->nullable();
                $table->float('payment_to_provider', 25, 2)->nullable();
                $table->string('status')->nullable();
                $table->string('observations')->nullable();
                $table->timestamps();

                $table->foreign('main_box_id')
                    ->references('id')
                    ->on('main_boxes');
            });


            $main_boxes = MainBox::all();

            foreach ($main_boxes as $main_box) {
                $history = (array) json_decode($main_box->history);
                foreach ($history as $h) {
                    $mainBoxHistory = new MainBoxHistory();
                    $mainBoxHistory->main_box_id       = $main_box->id;
                    $mainBoxHistory->user       = $h->user;
                    $mainBoxHistory->date       = $h->date;
                    $mainBoxHistory->value      = $h->value;
                    $mainBoxHistory->description = $h->description;
                    $mainBoxHistory->save();
                }
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
        Schema::dropIfExists('main_box_histories');
    }
}
