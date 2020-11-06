<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallecompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallecompra', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad');
            $table->boolean('estado')->default(1);
            $table->float('subtotal', 8, 2);

            $table->integer('idcompra')->unsigned();
            $table->foreign('idcompra')->references('id')->on('compra');
            
            $table->integer('idproducto')->unsigned();
            $table->foreign('idproducto')->references('id')->on('producto');
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
        Schema::dropIfExists('detallecompra');
    }
}
