<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('games', function($table)
            {

                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->datetime('dateTime');
                $table->integer('winnerId')->nullable();
                $table->integer('gameNumbers');
                $table->integer('maxGames');
                $table->integer('gameLength');
                $table->integer('matchId');
                $table->integer('noVods');

                $table->integer('tournamentId');
                $table->string('tournamentName');
                $table->integer('tournamentRound');

                $table->string('vodType');
                $table->string('vodURL');
                $table->text('embedCode')->nullable();

                $table->integer('blueId');
                $table->string('blueName');
                $table->string('blueLogoURL');

                $table->integer('redId');
                $table->string('redName');
                $table->string('redLogoURL');

                $table->integer('player0');
                $table->integer('player1');
                $table->integer('player2');
                $table->integer('player3');
                $table->integer('player4');
                $table->integer('player5');
                $table->integer('player6');
                $table->integer('player7');
                $table->integer('player8');
                $table->integer('player9');

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
            Schema::drop('games');
	}

}
