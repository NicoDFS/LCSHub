<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFteamsweekTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('fTeamWeeks', function($table)
            {

                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->integer('fId');
                $table->integer('fTeamId');
                $table->integer('riotId');

                $table->integer('week');

                $table->integer('firstBloodProjected');
                $table->integer('firstBloodActual');

                $table->integer('towerKillsProjected');
                $table->integer('towerKillsActual');

                $table->integer('baronKillsProjected');
                $table->integer('baronKillsActual');

                $table->integer('dragonKillsProjected');
                $table->integer('dragonKillsActual');

                $table->integer('matchVictoryProjected');
                $table->integer('matchVictoryActual');

                $table->integer('matchDefeatProjected');
                $table->integer('matchDefeatActual');

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
	    Schema::drop('fTeamWeeks');
        }

}
