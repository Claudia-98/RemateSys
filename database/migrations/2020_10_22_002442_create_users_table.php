<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('personas')->onDelete('cascade');

            $table->integer('idrol')->unsigned();
            $table->foreign('idrol')->references('id')->on('roles');
            
            $table->string('usuario', 45)->unique();
            $table->string('password');
            $table->string('foto');
            $table->boolean('estado')->default(1);

            $table->rememberToken();
        });

        DB::table('users')->insert(array('id'=>'1', 'idro'=>'1','usuario'=>'usuario','password'=>'$2y$10$D1ycqVz5Pf9sflroTimTre0rgU7PnYuJmp9Q9tpDtDisz8MxfeBDK','foto'=>'a','estado'=>'1'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
