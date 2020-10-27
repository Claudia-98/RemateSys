<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('estado')->default(1);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_cierre');
            $table->float('total', 8, 2);
            $table->text('observaciones');

            $table->integer('idespacio')->unsigned();
            $table->foreign('idespacio')->references('id')->on('espacio');
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
        Schema::dropIfExists('caja');
    }
}
