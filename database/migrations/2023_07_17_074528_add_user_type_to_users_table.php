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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->after('id');
            $table->boolean('is_approved')->default(0)->after('password');
            $table->boolean('is_active')->default(1)->after('is_approved');
            $table->boolean('is_deleted')->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_type');
            $table->dropColumn('is_approved');
            $table->dropColumn('is_active');
            $table->dropColumn('is_deleted');
        });
    }
};
