<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferencesOnContainers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->unsignedBigInteger('receiving_id')->nullable();
            $table->unsignedBigInteger('releasing_id')->nullable();
            $table->foreign('receiving_id')->references('id')->on('container_receivings');
            $table->foreign('releasing_id')->references('id')->on('container_releasings');
            $table->dropColumn('date_received');
            $table->dropColumn('date_released');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('containers', function (Blueprint $table) {
            $table->dropColumn('receiving_id');
            $table->dropColumn('releasing_id');
            $table->timestampTz('date_received')->nullable();
            $table->timestampTz('date_released')->nullable();
        });
    }
}
