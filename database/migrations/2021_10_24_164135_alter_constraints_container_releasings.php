<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConstraintsContainerReleasings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('container_releasings', function (Blueprint $table) {
            $table->dropColumn('container_photo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('container_releasings', function (Blueprint $table) {
            $table->dropColumn('container_photo_id');
        });
    }
}
