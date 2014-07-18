<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('matches', function($table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->datetime('dateTime');
                $table->string('matchName');
                $table->integer('winnerId')->nullable();
                $table->integer('matchId')->unique();
                $table->string('url');
                $table->integer('maxGames');
                $table->boolean('isLive');
                $table->boolean('isFinished');
                $table->boolean('liveStreams');
                $table->string('polldaddyId');
                $table->integer('blockId')->index();

                $table->integer('tournamentId');
                $table->string('tournamentName');
                $table->integer('tournamentRound');

                $table->integer('blueId');
                $table->string('blueName');
                $table->string('blueLogoURL');
                $table->string('blueAcronym');
                $table->integer('blueWins');
                $table->integer('blueLosses');

                $table->string('redId');
                $table->string('redName');
                $table->string('redLogoURL');
                $table->string('redAcronym');
                $table->integer('redWins');
                $table->integer('redLosses');

                $table->integer('gameId');
                $table->boolean('gameNoVods');
                $table->boolean('gameHasVod');

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
            Schema::drop('matches');
	}

}
