<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerReleasingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_releasings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('booking_no')->nullable();
            $table->string('conglone')->nullable();
            $table->string('hauler')->nullable();
            $table->text('plate_no')->nullable();
            $table->text('seac_no')->nullable();
            $table->text('upload_photo')->nullable();
            $table->text('signature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('container_releasings');
    }
}
