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
                $table->string('polldaddyId')->nullable();
                $table->integer('blockId')->index();

                $table->integer('tournamentId');
                $table->string('tournamentName');
                $table->integer('tournamentRound');

                $table->integer('blueId')->nullable();
                $table->string('blueName')->nullable();
                $table->string('blueLogoURL')->nullable();
                $table->string('blueAcronym')->nullable();
                $table->integer('blueWins')->nullable();
                $table->integer('blueLosses')->nullable();

                $table->string('redId')->nullable();
                $table->string('redName')->nullable();
                $table->string('redLogoURL')->nullable();
                $table->string('redAcronym')->nullable();
                $table->integer('redWins')->nullable();
                $table->integer('redLosses')->nullable();

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
