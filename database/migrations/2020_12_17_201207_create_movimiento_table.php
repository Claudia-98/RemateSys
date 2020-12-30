<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('idusuario')->unsigned();
            $table->foreign('idusuario')->references('id')->on('users');

            $table->integer('idespacio_1')->unsigned();
            $table->foreign('idespacio_1')->references('id')->on('espacio');
            
            $table->integer('idespacio_2')->unsigned();
            $table->foreign('idespacio_2')->references('id')->on('espacio');

            $table->integer('no_recibo');
            $table->text('observaciones');
            $table->date('fecha');
            $table->boolean('estado')->default(1);
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
        Schema::dropIfExists('movimiento');
    }
}
