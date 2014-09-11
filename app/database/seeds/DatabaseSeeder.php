<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

                Block::truncate();
                FPlayer::truncate();
                FPlayerGame::truncate();
                FTeam::truncate();
                FTeamGame::truncate();
                Game::truncate();
                GamePlayer::truncate();
                League::truncate();
                Match::truncate();
                Player::truncate();
                Team::truncate();
                Tournament::truncate();

                $insertController = new InsertController;
                $insertController->all();
                //$insertController->blocks();
                //$insertController->games();

	}

}
