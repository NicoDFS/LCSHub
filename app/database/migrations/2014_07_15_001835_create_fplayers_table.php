<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFplayersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('fPlayers', function($table)
            {

                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->integer('fId');
                $table->integer('riotId')->unique();
                $table->integer('proTeamId');
                $table->string('name');
                $table->text('flavorText')->nullable();
                $table->string('positions')->index();

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
	    Schema::drop('fPlayers');
	}

}
