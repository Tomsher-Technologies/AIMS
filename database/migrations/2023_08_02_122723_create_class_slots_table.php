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
        Schema::create('teacher_slots', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('assigned_id')->unsigned();
            $table->foreign('assigned_id')->references('id')->on('assign_teachers')->onDelete('cascade');
            $table->string('slot')->nullable();
            $table->boolean('is_booked')->default(0);
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
        Schema::dropIfExists('teacher_slots');
    }
};
