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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->bigInteger('teacher_id')->unsigned();
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('module_id')->unsigned();
            $table->foreign('module_id')->references('id')->on('course_divisions')->onDelete('cascade');

            $table->bigInteger('slot_id')->unsigned();
            $table->foreign('slot_id')->references('id')->on('teacher_slots')->onDelete('cascade');

            $table->string('booking_date');
            $table->boolean('is_cancelled')->default(0);
            $table->integer('cancelled_by')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_deleted')->default(0);
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
        Schema::dropIfExists('bookings');
    }
};
