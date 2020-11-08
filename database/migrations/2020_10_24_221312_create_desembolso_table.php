<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesembolsoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desembolso', function (Blueprint $table) {
            $table->increments('id');
            $table->text('descripcion');
            $table->float('total', 8, 2);
            $table->dateTime('fecha');
            $table->boolean('estado')->default(1);


            $table->integer('idcaja')->unsigned();
            $table->foreign('idcaja')->references('id')->on('caja');
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
        Schema::dropIfExists('desembolso');
    }
}
