<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFteamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('fTeams', function($table)
            {

                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->integer('fId');
                $table->integer('riotId');
                $table->string('name');
                $table->string('shortName');
                $table->text('flavorText');
                $table->string('positions');

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
	    Schema::drop('fTeams');
	}

}
