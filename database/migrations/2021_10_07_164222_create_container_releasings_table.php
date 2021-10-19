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
            $table->timestampTz('inspected_date')->nullable();
            $table->bigInteger('inspected_by')->nullable();
            $table->string('container_no')->nullable();
            $table->string('booking_no')->nullable();
            $table->string('consignee')->nullable();
            $table->string('hauler')->nullable();
            $table->string('plate_no')->nullable();
            $table->string('seal_no')->nullable();
            $table->text('upload_photo')->nullable();
            $table->text('signature')->nullable();
            $table->text('remarks')->nullable();
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
