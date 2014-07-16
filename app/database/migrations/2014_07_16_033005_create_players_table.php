<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('players', function($table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->integer('playerId')->unique();
                $table->string('name');
                $table->text('bio');
                $table->string('firstName');
                $table->string('lastName');
                $table->string('hometown');
                $table->string('facebookURL')->nullable();
                $table->string('twitterURL')->nullable();
                $table->integer('teamId');
                $table->string('profileURL');
                $table->string('role');
                $table->integer('roleId');
                $table->string('photoURL');

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
	    Schema::drop('players');
	}

}
