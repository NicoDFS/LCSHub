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
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->integer('blockId')->unique();
                $table->datetime('dateTime');
                $table->string('tickets')->nullable();
                $table->integer('leagueId');
                $table->integer('tournamentId');
                $table->string('tournamentName');
                $table->integer('significance');
                $table->integer('tbdTime');
                $table->string('leagueColor');
                $table->string('week');
                $table->string('label');
                $table->datetime('bodyTime')->nullable();
                $table->string('body')->nullable();
                $table->string('bodyTitle')->nullable();


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
