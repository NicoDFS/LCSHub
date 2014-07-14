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

/*
|   Route::get('/', function()
|   {
|	return View::make('hello');
|   });
*/

Route::get('/block/{id}', function($id)
{

    $block = Block::where('blockId', $id)->first();
    return dd($block->matches);

});

Route::get('/today', function()
{

    $laTz = new DateTimeZone('America/Los_Angeles');

    $datetime = new DateTime();
    $datetime->setTimezone($laTz);

    $hoursLeft = 24 - $datetime->format('H');
    $hoursUp = 24 - $hoursLeft;

    $query = "dateTime >= '" . date('Y-m-d H:i:s', strtotime("-" . $hoursUp . " hours")) . "' AND dateTime <= '" . date('Y-m-d H:i:s', strtotime("+" . $hoursLeft . " hours")) . "'";
    $todayBlock = Block::whereRaw($query)->first();

    return View::make('today')->with('matches', $todayBlock->matches);
});

Route::get('/all', function()
{

    $allBlocks = Block::with('matches')->get();

    return View::make('all')->with('blocks', $allBlocks);
});

Route::get('/insertblocks', function()
{

    $programmingUrl = 'http://na.lolesports.com/api/programming.json/?parameters[method]=prev&parameters[limit]=6&parameters[expand_matches]=1&parameters[tournament]=104';
    $programming = json_decode(file_get_contents($programmingUrl));

    Eloquent::unguard();

    foreach($programming as $program)
    {
        $block = Block::firstOrCreate(['blockId' => $program->blockId]);

        $block->update([
            'dateTime'          => date('Y-m-d H:i:s', strtotime($program->dateTime)),
            'tickets'           => $program->tickets,
            'leagueId'          => $program->leagueId,
            'tournamentId'      => $program->tournamentId,
            'tournamentName'    => $program->tournamentName,
            'significance'      => $program->significance,
            'tbdTime'           => $program->tbdTime,
            'leagueColor'       => $program->leagueColor,
            'week'              => $program->week,
            'label'             => $program->label,
            'bodyTime'          => date('Y-m-d H:i:s', strtotime($program->body[0]->bodyTime))
        ]);

        $block->save();

        foreach($program->matches as $matchData)
        {
            $match = Match::firstOrCreate(['matchId' => $matchData->matchId]);

            $match->update([
                'dateTime'          => date('Y-m-d H:i:s', strtotime($matchData->dateTime)),
                'matchName'         => $matchData->matchName,
                'winnerId'          => $matchData->winnerId,
                'url'               => $matchData->url,
                'maxGames'          => $matchData->maxGames,
                'isLive'            => $matchData->isLive,
                'isFinished'        => $matchData->isFinished,
                'liveStreams'       => $matchData->liveStreams,
                'polldaddyId'       => $matchData->polldaddyId,
                'blockId'           => $program->blockId,

                'tournamentId'      => $matchData->tournament->id,
                'tournamentName'    => $matchData->tournament->name,
                'tournamentRound'   => $matchData->tournament->round,

                'blueId'            => $matchData->contestants->blue->id,
                'blueName'          => $matchData->contestants->blue->name,
                'blueLogoURL'       => $matchData->contestants->blue->logoURL,
                'blueAcronym'       => $matchData->contestants->blue->acronym,
                'blueWins'          => $matchData->contestants->blue->wins,
                'blueLosses'        => $matchData->contestants->blue->losses,

                'redId'             => $matchData->contestants->red->id,
                'redName'           => $matchData->contestants->red->name,
                'redLogoURL'        => $matchData->contestants->red->logoURL,
                'redAcronym'        => $matchData->contestants->red->acronym,
                'redWins'           => $matchData->contestants->red->wins,
                'redLosses'         => $matchData->contestants->red->losses,

                'gameId'            => $matchData->gamesInfo->game0->id,
                'gameNoVods'        => $matchData->gamesInfo->game0->noVods,
                'gameHasVod'        => $matchData->gamesInfo->game0->hasVod,
            ]);

            $match->save();
        }
    }


});
