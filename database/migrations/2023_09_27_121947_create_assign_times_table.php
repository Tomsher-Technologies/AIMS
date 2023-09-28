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
        Schema::create('assign_times', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assign_id')->unsigned();
            $table->foreign('assign_id')->references('id')->on('assign_teachers')->onDelete('cascade');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
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
        Schema::dropIfExists('assign_times');
    }
};
