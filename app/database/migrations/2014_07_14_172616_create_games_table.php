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
                $table->datetime('dateTime')->nullable();
                $table->integer('gameId')->unique();
                $table->integer('winnerId')->nullable();
                $table->integer('gameNumber');
                $table->integer('maxGames');
                $table->integer('gameLength')->nullable();
                $table->integer('matchId');
                $table->integer('noVods');

                $table->integer('tournamentId');
                $table->string('tournamentName');
                $table->integer('tournamentRound');

                $table->string('vodType')->nullable();
                $table->string('vodURL')->nullable();
                $table->text('embedCode')->nullable();

                $table->integer('blueId');
                $table->string('blueName');
                $table->string('blueLogoURL');

                $table->integer('redId');
                $table->string('redName');
                $table->string('redLogoURL');

                $table->integer('player0')->nullable();
                $table->integer('player1')->nullable();
                $table->integer('player2')->nullable();
                $table->integer('player3')->nullable();
                $table->integer('player4')->nullable();
                $table->integer('player5')->nullable();
                $table->integer('player6')->nullable();
                $table->integer('player7')->nullable();
                $table->integer('player8')->nullable();
                $table->integer('player9')->nullable();

                $table->string('platformId')->nullable();
                $table->integer('platformGameId')->nullable();

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
