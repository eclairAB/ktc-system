<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReceivingDamagesFields extends Migration
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
            $table->string('length')->nullable()->change();
            $table->string('width')->nullable()->change();
            $table->string('quantity')->nullable()->change();

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
            $table->integer('length');
            $table->integer('width');
            $table->integer('quantity');
        });
    }
}
