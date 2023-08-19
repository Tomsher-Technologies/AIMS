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
        Schema::create('mock_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_date')->nullable();
            $table->bigInteger('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('listening_a')->nullable();
            $table->string('listening_b')->nullable();
            $table->string('listening_c')->nullable();
            $table->string('listening_total')->nullable();
            $table->string('reading_a')->nullable();
            $table->string('reading_b')->nullable();
            $table->string('reading_c')->nullable();
            $table->string('reading_total')->nullable();
            $table->boolean('is_bulk')->default(0);
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
        Schema::dropIfExists('mock_tests');
    }
};
