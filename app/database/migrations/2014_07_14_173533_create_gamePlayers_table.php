<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamePlayersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('gamePlayers', function($table)
            {

                $table->engine = 'InnoDB';

                $table->increments('id');

                $table->integer('gameId');
                $table->integer('playerId');
                $table->integer('teamId');
                $table->string('name');
                $table->string('photoURL');

                $table->integer('championId');
                $table->integer('endLevel');
                $table->integer('kills');
                $table->integer('deaths');
                $table->integer('assists');
                $table->double('kda', 5, 3);

                $table->integer('item0Id')->nullable();
                $table->integer('item1Id')->nullable();
                $table->integer('item2Id')->nullable();
                $table->integer('item3Id')->nullable();
                $table->integer('item4Id')->nullable();
                $table->integer('item5Id')->nullable();
                $table->integer('item6Id')->nullable();

                $table->integer('spell0Id');
                $table->integer('spell1Id');

                $table->integer('totalGold');
                $table->integer('minionsKilled');


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
	    Schema::drop('gamePlayers');
	}

}
