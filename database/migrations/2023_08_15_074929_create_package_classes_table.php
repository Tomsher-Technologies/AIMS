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
        Schema::create('package_classes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_id')->unsigned();
            $table->foreign('package_id')->references('id')->on('course_packages')->onDelete('cascade');

            $table->bigInteger('class_id')->unsigned();
            $table->foreign('class_id')->references('id')->on('course_classes')->onDelete('cascade');
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
        Schema::dropIfExists('package_classes');
    }
};
