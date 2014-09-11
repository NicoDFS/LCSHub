<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFteammatchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('fTeamGames', function($table)
            {

                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->datetime('dateTime')->nullable();
                $table->integer('gameId')->index();
                $table->integer('matchId');
                $table->integer('teamId');
                $table->string('teamName');
                $table->integer('matchVictory');
                $table->integer('matchDefeat');
                $table->integer('baronsKilled')->nullable();
                $table->integer('dragonsKilled')->nullable();
                $table->integer('firstBlood')->nullable();
                $table->integer('firstTower')->nullable();
                $table->integer('firstInhibitor')->nullable();
                $table->integer('towersKilled')->nullable();

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
            Schema::drop('fTeamGames');
	}

}
