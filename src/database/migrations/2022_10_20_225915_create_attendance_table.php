<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->unsignedBigInteger('user_id')->nullable();
            //$table->bigInteger('user_id')->nullable()->unsigned();
            $table->String('attendance_day_of_week');
            $table->timestamp('attendance_start_time');
            $table->timestamp('attendance_end_time')->nullable();
            $table->time('attendance_break_time')->nullable();
            $table->time('attendance_actualWork_time')->nullable();
            $table->String('attendance_remarks')->nullable();
            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance');
    }
};
