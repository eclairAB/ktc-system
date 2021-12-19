<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEirNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eir_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('eir_no')->nullable();
            $table->unsignedBigInteger('container_id')->nullable();
            $table->foreign('container_id')->references('id')->on('containers');
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
        Schema::dropIfExists('eir_numbers');
    }
}
