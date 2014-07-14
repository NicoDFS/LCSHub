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

                $table->integer('playerId');
                $table->integer('teamId');
                $table->integer('name');
                $table->string('photoURL');

                $table->integer('championId');
                $table->integer('endLevel');
                $table->integer('kills');
                $table->integer('deaths');
                $table->integer('assists');
                $table->double('kda', 5, 3);

                $table->integer('item0Id');
                $table->integer('item1Id');
                $table->integer('item2Id');
                $table->integer('item3Id');
                $table->integer('item4Id');
                $table->integer('item5Id');
                $table->integer('item6Id');

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
