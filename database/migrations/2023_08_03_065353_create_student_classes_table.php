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
        Schema::create('student_classes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('student_package_id')->unsigned();
            $table->foreign('student_package_id')->references('id')->on('student_packages')->onDelete('cascade');

            $table->bigInteger('course_package_id')->unsigned();
            $table->foreign('course_package_id')->references('id')->on('course_packages')->onDelete('cascade');

            $table->bigInteger('class_id')->unsigned();
            $table->foreign('class_id')->references('id')->on('course_classes')->onDelete('cascade');

            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            
            $table->boolean('is_attended')->default(0);
            $table->boolean('is_active')->default(1);

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
        Schema::dropIfExists('student_classes');
    }
};
