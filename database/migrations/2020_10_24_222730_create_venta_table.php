<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('fecha');
            $table->string('n_factura', 25);
            $table->boolean('estado')->default(1);
            $table->boolean('estado_factura')->default(1);
            $table->float('total', 8, 2);
            $table->float('descuento', 8, 2);

            $table->integer('idcaja')->unsigned();
            $table->foreign('idcaja')->references('id')->on('caja');
            $table->integer('idusuario')->unsigned();
            $table->foreign('idusuario')->references('id')->on('users');
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
        Schema::dropIfExists('venta');
    }
}
