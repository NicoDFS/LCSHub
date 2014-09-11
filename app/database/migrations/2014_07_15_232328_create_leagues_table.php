<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaguesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('leagues', function($table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->integer('leagueId')->unique();
                $table->string('color');
                $table->string('leagueImage')->nullable();
                $table->integer('defaultTournamentId')->nullable();
                $table->integer('defaultSeriesId')->nullable();
                $table->string('shortName');
                $table->string('url');
                $table->string('label');
                $table->boolean('noVods');
                $table->integer('menuWeight');
                $table->string('twitch')->nullable();
                $table->string('youtube')->nullable();
                $table->text('azubu')->nullable();
                $table->string('leagueTournaments')->nullable();
                $table->boolean('published');

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
	    Schema::drop('leagues');
	}

}
