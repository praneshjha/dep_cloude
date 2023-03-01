<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoldDepartureSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hold_departure_seats', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('departure_id');
            $table->integer('hold_seat');
            $table->integer('hold_duration');
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
        Schema::dropIfExists('hold_departure_seats');
    }
}
