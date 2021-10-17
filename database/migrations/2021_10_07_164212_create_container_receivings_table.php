<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContainerReceivingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('container_receivings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestampTz('inspected_date')->nullable();
            $table->bigInteger('inspected_by')->nullable();    
            $table->bigInteger('client_id')->nullable();
            $table->bigInteger('size_type')->nullable();
            $table->bigInteger('class')->nullable();
            $table->bigInteger('height')->nullable();
            $table->string('container_no')->nullable();
            $table->string('empty_loaded')->nullable();
            $table->string('type')->nullable();
            $table->timestampTz('manufactured_date')->nullable();
            $table->string('yard_loacation')->nullable();
            $table->string('acceptance_no')->nullable();
            $table->string('consignee')->nullable();
            $table->string('hauler')->nullable();
            $table->string('plate_no')->nullable();
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
        Schema::dropIfExists('container_receivings');
    }
}
