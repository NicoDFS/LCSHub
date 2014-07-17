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

                $table->integer('championId')->nullable();
                $table->integer('endLevel')->nullable();
                $table->integer('kills')->nullable();
                $table->integer('deaths')->nullable();
                $table->integer('assists')->nullable();
                $table->double('kda', 5, 3)->nullable();

                $table->integer('item0Id')->nullable();
                $table->integer('item1Id')->nullable();
                $table->integer('item2Id')->nullable();
                $table->integer('item3Id')->nullable();
                $table->integer('item4Id')->nullable();
                $table->integer('item5Id')->nullable();

                $table->integer('spell0Id')->nullable();
                $table->integer('spell1Id')->nullable();

                $table->integer('totalGold')->nullable();
                $table->integer('minionsKilled')->nullable();


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
