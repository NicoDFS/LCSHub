<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('blocks', function($table)
            {
                $table->increments('id');
                $table->string('dateTime');
                $table->string('tickets');
                $table->string('leagueId');
                $table->string('tournamentId');
                $table->string('tournamentName');
                $table->string('significance');
                $table->string('tbdTime');
                $table->string('leagueColor');
                $table->string('week');
                $table->string('label');
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
            Schema::drop('blocks');
	}

}
