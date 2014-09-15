<?php

Route::controller('ajax', 'AjaxController');

Route::get('/', function()
{
    return View::make('html.home')->with('block', Block::currentBlock());
});


Route::get('/test', function()
{

    $fTeam = new FTeam();
    dd($fTeam->teamOptions());

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
