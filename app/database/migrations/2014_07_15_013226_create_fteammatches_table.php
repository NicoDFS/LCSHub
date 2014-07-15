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
                $table->datetime('dateTime');
                $table->integer('gameId');
                $table->integer('matchId');
                $table->integer('teamId');
                $table->string('teamName');
                $table->integer('matchVictory');
                $table->integer('matchDefeat');
                $table->integer('baronsKilled');
                $table->integer('dragonsKilled');
                $table->integer('firstBlood');
                $table->integer('firstTower');
                $table->integer('firstInhibitor');
                $table->integer('towersKilled');

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
