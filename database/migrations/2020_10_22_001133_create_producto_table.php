<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100);
            $table->string('alias', 50)->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->double('precio_venta');
            $table->double('precio_mayorista')->nullable();
            $table->double('precio_compra')->nullable();
            // $table->double('notify')->nullable();

            $table->integer('idtipo')->unsigned();
            $table->foreign('idtipo')->references('id')->on('tipoproducto');

            $table->integer('idmedida')->unsigned();
            $table->foreign('idmedida')->references('id')->on('medidaproducto');
            
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
        Schema::dropIfExists('producto');
    }
}
