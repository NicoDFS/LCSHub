<?php

Route::controller('ajax', 'AjaxController');

Route::get('/', function()
{
    return View::make('html.home')->with('block', Block::currentBlock());
});

Route::get('/test', function()
{

    //$urls = [];
    //foreach(Game::all() as $game)
    //{
    //    $urls[] = "http://na.lolesports.com/api/game/{$game->gameId}.json";
    //}
    //
    //
    //$callback = function($data, $info)
    //{
    //    echo "a ";
    //};
    //
    //
    //$requests = new Requests();
    //$requests->rolling_curl($urls, $callback);
    //$requests->process($urls, $callback);

    //$game = Game::find(312);
    //if(count($game->teams()) > 0)
    //{
    //    echo "YES";
    //}

    //$match = Match::find(253);
    //foreach($match->getGames() as $k => $game)
    //{
    //    if(count($game->teams()) > 0)
    //    {
    //        "yes <br/>";
    //    }
    //}

    //dd($match->getGames());
    //dd($game->teams());

});

Route::get('/reset', function()
{

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

    return "Truncated all tables.";

});

Route::get('/inserttodayleague', 'InsertController@todayLeague');

Route::get('/inserttoday', 'InsertController@today');

Route::get('/insertall', 'InsertController@all');

Route::get('/insertfgamedata', 'InsertController@fantasyGameData');

Route::get('/insertfdata', 'InsertController@fantasyTeamData');

Route::get('/inserttournaments', 'InsertController@tournamentTeamsPlayers');

Route::get('/insertleagues', 'InsertController@leagues');

Route::get('/insertgames', 'InsertController@games');

Route::get('/insertblocks', 'InsertController@blocks');

//***********************

class Requests
{
	public $handle;

	public function __construct()
	{
		$this->handle = curl_multi_init();
	}

	public function process($urls, $callback)
	{
		foreach($urls as $url)
		{
			$ch = curl_init($url);
			curl_setopt_array($ch, array(CURLOPT_RETURNTRANSFER => TRUE));
			curl_multi_add_handle($this->handle, $ch);
		}

		do {
			$mrc = curl_multi_exec($this->handle, $active);

			if ($state = curl_multi_info_read($this->handle))
			{
				//print_r($state);
				$info = curl_getinfo($state['handle']);
				//print_r($info);
				$callback(curl_multi_getcontent($state['handle']), $info);
				curl_multi_remove_handle($this->handle, $state['handle']);
			}

			usleep(10000); // stop wasting CPU cycles and rest for a couple ms

		} while ($mrc == CURLM_CALL_MULTI_PERFORM || $active);

	}

	public function __destruct()
	{
		curl_multi_close($this->handle);
	}

        public function rolling_curl($urls, $callback, $custom_options = null)
        {

                // make sure the rolling window isn't greater than the # of urls
                $rolling_window = 5;
                $rolling_window = (sizeof($urls) < $rolling_window) ? sizeof($urls) : $rolling_window;

                $master = curl_multi_init();
                $curl_arr = array();

                // add additional curl options here
                $std_options = array(
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_MAXREDIRS => 1
                );

                $options = ($custom_options) ? ($std_options + $custom_options) : $std_options;

                // start the first batch of requests
                for ($i = 0; $i < $rolling_window; $i++)
                {
                        $ch = curl_init();
                        $options[CURLOPT_URL] = $urls[$i];
                        curl_setopt_array($ch,$options);
                        curl_multi_add_handle($master, $ch);
                }

                do {
                        while(($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
                                if($execrun != CURLM_OK)
                                        break;

                        // a request was just completed -- find out which one
                        while($done = curl_multi_info_read($master))
                        {
                                $info = curl_getinfo($done['handle']);

                                $output = curl_multi_getcontent($done['handle']);

                                // request successful.  process output using the callback function.
                                $callback($output, $info);

                                if(isset($urls[$i + 1]))
                                {
                                        // start a new request (it's important to do this before removing the old one)
                                        $ch = curl_init();
                                        $options[CURLOPT_URL] = $urls[$i++];  // increment i
                                        curl_setopt_array($ch,$options);
                                        curl_multi_add_handle($master, $ch);
                                }

                                // remove the curl handle that just completed
                                curl_multi_remove_handle($master, $done['handle']);
                        }

                        usleep(1000); // stop wasting CPU cycles and rest for a couple ms

                } while ($running);

                curl_multi_close($master);
        }
}

//***********************


