<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {

            /*$table->unsignedBigInteger('trip_id')->nullable();                      // if want to add Program without Trip
            $table->unsignedBigInteger('admin_id')->nullable();*/
            $table->id();
            $table->string('photo')->default('program_default.jpg');
            $table->tinyInteger('status')->default(1)->comment('1 => Active , 0  => NotActive');
            $table->timestamps();

            /*$table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('SET NULL')->onUpdate('SET NULL');      //cause tip_id in nullable*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programs');
    }
}
