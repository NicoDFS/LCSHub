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

Route::get('/insertgames', function()
{
    $matches = Match::where('isFinished', true)->get();

    Eloquent::unguard();

    foreach($matches as $match)
    {
        $gameURL = 'http://na.lolesports.com:80/api/game/' . $match->gameId . '.json';
        $game = json_decode(file_get_contents($gameURL));
    }

});

Route::get('/insertblocks', function()
{

    $lcs = array('NA' => 104, 'EU' => 102);
    $methods = array('prev', 'next');

    Eloquent::unguard();

    foreach($lcs as $region => $tournament)
    {

        foreach($methods as $method)
        {
            $programmingUrl = 'http://na.lolesports.com/api/programming.json/?parameters[method]=' . $method . '&parameters[limit]=100&parameters[expand_matches]=1&parameters[tournament]=' . $tournament;
            $programming = json_decode(file_get_contents($programmingUrl));

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
        }

    }


    $programIds = array('1826', '1827', '1828');

    foreach($programIds as $pId)
    {
        $programmingUrl = 'http://na.lolesports.com:80/api/programming/' . $pId . '.json?expand_matches=1';
        $program = json_decode(file_get_contents($programmingUrl));

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


