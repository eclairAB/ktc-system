<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateToNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receiving_damages', function (Blueprint $table) {
            //
            $table->integer('length')->nullable()->change();
            $table->integer('width')->nullable()->change();
            $table->integer('quantity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receiving_damages', function (Blueprint $table) {
            //
            $table->dropColumn('length');
            $table->dropColumn('width');
            $table->dropColumn('quantity');
        });
    }
}
