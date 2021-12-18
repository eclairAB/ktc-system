<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContReleasingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('container_releasings', function (Blueprint $table) {
            //
            $table->string('booking_no')->nullable()->change();
            $table->string('seal_no')->nullable()->change();
            $table->text('remarks')->nullable()->change();
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
        Schema::table('container_releasings', function (Blueprint $table) {
            //
            $table->dropColumn('booking_no');
            $table->dropColumn('seal_no');
            $table->dropColumn('remarks');
            $table->text('signature');
        });
    }
}
