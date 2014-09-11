<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('teams', function($table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->integer('tournamentId');
                $table->integer('teamId')->unique();
                $table->string('name');
                $table->string('bio')->nullable();
                $table->string('noPlayers')->nullable();
                $table->string('logoUrl');
                $table->string('profileUrl')->nullable();
                $table->string('teamPhotoUrl')->nullable();
                $table->string('acronym')->nullable();

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
	    Schema::drop('teams');
	}

}
