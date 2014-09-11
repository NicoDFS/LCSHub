<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('tournaments', function($table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->integer('leagueId');
                $table->integer('tournamentId')->unique();
                $table->string('name');
                $table->string('namePublic');
                $table->boolean('isFinished');
                $table->datetime('dateBegin');
                $table->datetime('dateEnd');
                $table->integer('noVods');
                $table->string('season');
                $table->boolean('published');
                $table->string('winner')->nullable();

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
	    Schema::drop('tournaments');
	}

}
