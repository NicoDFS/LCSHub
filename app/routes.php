<?php

Route::controller('ajax', 'AjaxController');

Route::get('/', function()
{

    if(Cookie::has(Config::get('cookie.fantasyTeams')))
    {
        if(is_array(Cookie::get(Config::get('cookie.fantasyTeams'))))
        {
            unset($_COOKIE[Config::get('cookie.fantasyTeams')]);
            Cookie::queue(Config::get('cookie.fantasyTeams'), null, (60 * 24 * 360));
        }
    }

    return View::make('html.home')->with('block', Block::currentBlock());
});


Route::get('/test', function()
{

    if(Cookie::has(Config::get('cookie.spoilers')))
    {
        if(Cookie::get(Config::get('cookie.spoilers')) == 1)
        {
            dd("YES");
        }

    }

});

Route::group(array('prefix' => 'reset'), function()
{

    Route::get('all', function() {

        $tableNames = DB::select('SHOW TABLES');

        foreach ($tableNames as $name)
        {
            if ($name->Tables_in_flcshub == 'migrations') continue;

            DB::statement("TRUNCATE {$name->Tables_in_flcshub}");
        }

    });

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

Route::group(array('prefix' => 'insert'), function()
{

    Route::get('todayLeague', 'InsertController@todayLeague');

    Route::get('today', 'InsertController@today');

    Route::get('all', 'InsertController@all');

    Route::get('fantasyGameData', 'InsertController@fantasyGameData');

    Route::get('fantasyTeamData', 'InsertController@fantasyTeamData');

    Route::get('tournamentTeamsPlayers', 'InsertController@tournamentTeamsPlayers');

    Route::get('leagues', 'InsertController@leagues');

    Route::get('games', 'InsertController@games');

    Route::get('blocks', 'InsertController@blocks');

});

