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
        Schema::create('attendance_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attend_id')->unsigned();
            $table->foreign('attend_id')->references('id')->on('attendances')->onDelete('cascade');
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('status')->default(0)->comment('1-Attended, 0-Not Attended');
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
        Schema::dropIfExists('attendance_students');
    }
};
