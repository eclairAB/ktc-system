<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivingDamagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receiving_damages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('damage_id');
            $table->bigInteger('component_id');
            $table->bigInteger('repair_id');
            $table->bigInteger('receiving_id');
            $table->string('location');
            $table->integer('length');
            $table->integer('width');
            $table->integer('quantity');
            $table->text('description');
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
        Schema::dropIfExists('receiving_damages');
    }
}
