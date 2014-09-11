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
