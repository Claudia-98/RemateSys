<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallemovimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallemovimiento', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('idmovimiento')->unsigned();
            $table->foreign('idmovimiento')->references('id')->on('movimiento');

            $table->integer('idproducto')->unsigned();
            $table->foreign('idproducto')->references('id')->on('producto');

            $table->integer('idespacio')->unsigned();
            $table->foreign('idespacio')->references('id')->on('espacio');

            $table->decimal('cantidad',10,2)->nullable();
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
        Schema::dropIfExists('detallemovimiento');
    }
}
