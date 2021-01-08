<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100);
            $table->string('direccion', 100)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('email', 200)->nullable();


            $table->integer('idtipo')->unsigned();
            $table->foreign('idtipo')->references('id')->on('tipopersona');

            $table->boolean('estado')->default(1);
            $table->timestamps();
        });

        DB::table('personas')->insert(array('id'=>'1', 'nombre'=>'usuario', 'direccion'=>'QT', 'telefono'=>'123', 'email'=>'mail', 'idtipo'=>'1', 'estado'=>'1'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
