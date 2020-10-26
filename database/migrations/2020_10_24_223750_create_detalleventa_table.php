<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleventaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalleventa', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad');
            $table->float('descuento', 8, 2);
            $table->boolean('estado')->default(1);
            $table->float('subtotal', 8, 2);

            $table->integer('idventa')->unsigned();
            $table->foreign('idventa')->references('id')->on('venta');
            
            $table->integer('idespacio')->unsigned();
            $table->foreign('idespacio')->references('id')->on('espacio');
            
            $table->integer('idproducto')->unsigned();
            $table->foreign('idproducto')->references('id')->on('prodcuto');
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
        Schema::dropIfExists('detalleventa');
    }
}
