<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContReceivingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('container_receivings', function (Blueprint $table) {
            //
            $table->date('manufactured_date')->nullable()->change();
            $table->dropColumn('signature');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('container_receivings', function (Blueprint $table) {
            //
            $table->timestampTz('manufactured_date');
            $table->text('signature');
        });
    }
}
