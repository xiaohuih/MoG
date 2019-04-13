<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('begin_relativetime')->nullable();
            $table->string('end_relativetime')->nullable();
            $table->date('begin_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('begin_time')->nullable();
            $table->string('duration')->nullable();
            $table->string('interval')->nullable();
            $table->string('wdays')->nullable();
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
        Schema::dropIfExists('schedules');
    }
}
