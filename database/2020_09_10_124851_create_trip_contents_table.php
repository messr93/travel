<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->char('lang', '5');
            $table->string('name');
            $table->text('description');
            $table->tinyInteger('status')->default(1)->comment('1 => Active , 0  => NotActive');
            $table->timestamps();

            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_contents');
    }
}
