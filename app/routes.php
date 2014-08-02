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




Route::controller('ajax', 'AjaxController');

Route::get('/home', function()
{
    $timezone = Config::get('cookie.timezoneDefault');
    //Cookie::queue(Config::get('cookie.timezone'), 'America/Los_Angeles', (60 * 24));

    if(Cookie::has(Config::get('cookie.timezone')))
    {
        $timezone = Cookie::get(Config::get('cookie.timezone'));
    }

    $datetime = new DateTime('now', new DateTimeZone($timezone));

    $query = "dateTime >= '" . $datetime->format('Y-m-d') . " 00:00:00' AND dateTime <= '" . $datetime->format('Y-m-d') . " 23:59:59'";
    $todayBlock = Block::whereRaw($query)->first();

    if(is_null($todayBlock))
    {
        $todayBlock = Block::where('dateTime', '<=',  $datetime->format('Y-m-d') . " 23:59:59")->orderBy('dateTime', 'desc')->get()[0];
        $todayBlock->currBlock = false;
    }
    else
    {
        $todayBlock->currBlock = true;
    }

    $todayBlock->timezone = $timezone;

    return View::make('html.home')->with('block', $todayBlock);
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


