<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFplayergamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

            Schema::create('fPlayerGames', function($table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->datetime('dateTime');
                $table->integer('matchId');
                $table->integer('gameId');
                $table->integer('fId');
                $table->integer('kills')->nullable();
                $table->integer('deaths')->nullable();
                $table->integer('assists')->nullable();
                $table->integer('minionKills')->nullable();
                $table->integer('doubleKills')->nullable();
                $table->integer('tripleKills')->nullable();
                $table->integer('quadraKills')->nullable();
                $table->integer('pentaKills')->nullable();
                $table->string('playerName');
                $table->string('role');

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
	    Schema::drop('fPlayerGames');
	}

}
