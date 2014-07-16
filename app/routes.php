<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/block/{id}', function($id)
{

    $block = Block::where('blockId', $id)->first();
    return dd($block->matches);

});

Route::get('/today', function()
{

    $timezone = 'America/Los_Angeles';
    Cookie::queue('timezone', 'America/Louisville', (60 * 24));

    if(Cookie::get('timezone'))
    {
        $timezone = Cookie::get('timezone');
    }

    $datetime = new DateTime('now', new DateTimeZone($timezone));

    $query = "dateTime >= '" . $datetime->format('Y-m-d') . " 00:00:00' AND dateTime <= '" . $datetime->format('Y-m-d') . " 23:59:59'";
    $todayBlock = Block::whereRaw($query)->first();

    if(!is_null($todayBlock))
    {
        return View::make('today')->with('matches', $todayBlock->matches);
    }
    else
    {
        return View::make('none');
    }

});

Route::get('/all', function()
{

    $allBlocks = Block::with('matches')->orderBy('dateTime', 'DESC')->get();

    return View::make('all')->with('blocks', $allBlocks);

});

Route::get('/deletegames', function()
{

    DB::table('games')->truncate();
    DB::table('gamePlayers')->truncate();

    return "Truncated games, and gamePlayers.";
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

Route::get('/insertall', 'InsertController@all');

Route::get('/insertfgamedata', 'InsertController@fantasyGameData');

Route::get('/insertfdata', 'InsertController@fantasyTeamData');

Route::get('/inserttournaments', 'InsertController@tournamentTeamsPlayers');

Route::get('/insertleagues', 'InsertController@leagues');

Route::get('/insertgames', 'InsertController@games');

Route::get('/insertblocks', 'InsertController@blocks');


