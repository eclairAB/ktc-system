<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('account_id');
            $table->text('id_no')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->text('user_id')->nullable();
            $table->string('contact_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkers');
    }
}
