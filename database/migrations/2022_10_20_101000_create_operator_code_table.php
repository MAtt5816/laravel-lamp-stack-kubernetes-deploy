<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operator_codes', function (Blueprint $table) {
            $table->id();
            $table->string('name',20);
            $table->string('surname',25);
            $table->string('operator_code',8);
            $table->unsignedBigInteger('operator_id');
            $table->timestamps();

            $table->foreign('operator_id')->references('id')->on('operators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operator_codes');
    }
}
