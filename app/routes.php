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

Route::get('/insertfgamedata', function()
{

    Eloquent::unguard();

    $tournaments = array('NA' => 104, 'EU' => 102);

    foreach($tournaments as $tId)
    {
        $fGameURL = 'http://na.lolesports.com:80/api/gameStatsFantasy.json?tournamentId=' . $tId;
        $fGameData = json_decode(file_get_contents($fGameURL));

        foreach($fGameData->teamStats as $tKey => $tStats)
        {

            $teamArray = array();
            foreach($tStats as $key => $value)
            {
                if(strpos($key, 'team') !== false)
                {
                    $teamArray[] = $key;
                }
            }

            foreach($teamArray as $teamId)
            {
                if(FTeamGame::whereRaw('matchId = ' . $tStats->matchId . ' AND teamId = ' . $tStats->$teamId->teamId)->count() == 0)
                {
                    $fTeamGame = FTeamGame::create([
                        'dateTime'          => date('Y-m-d H:i:s', strtotime($tStats->dateTime)),
                        'gameId'            => (int) substr($tKey, 4),
                        'matchId'           => $tStats->matchId,
                        'teamId'            => $tStats->$teamId->teamId,
                        'teamName'          => $tStats->$teamId->teamName,
                        'matchVictory'      => $tStats->$teamId->matchVictory,
                        'matchDefeat'       => $tStats->$teamId->matchDefeat,
                        'baronsKilled'      => $tStats->$teamId->baronsKilled,
                        'dragonsKilled'     => $tStats->$teamId->dragonsKilled,
                        'firstBlood'        => $tStats->$teamId->firstBlood,
                        'firstTower'        => $tStats->$teamId->firstTower,
                        'firstInhibitor'    => $tStats->$teamId->firstInhibitor,
                        'towersKilled'      => $tStats->$teamId->towersKilled
                    ]);
                }
            }
        }

        foreach($fGameData->playerStats as $pKey => $pStats)
        {
            $playerArray = array();
            foreach($pStats as $key => $value)
            {
                if(strpos($key, 'player') !== false)
                {
                    $playerArray[] = $key;
                }
            }

            foreach($playerArray as $playerId)
            {
                if(FPlayerGame::whereRaw('matchId = ' . $pStats->matchId . ' AND fId = ' . $pStats->$playerId->playerId)->count() == 0)
                {
                    $fPlayerGame = FPlayerGame::create([
                        'dateTime'          => date('Y-m-d H:i:s', strtotime($pStats->dateTime)),
                        'matchId'           => $pStats->matchId,
                        'gameId'            => substr($pKey, 4),
                        'fId'               => $pStats->$playerId->playerId,
                        'kills'             => $pStats->$playerId->kills,
                        'deaths'            => $pStats->$playerId->deaths,
                        'assists'           => $pStats->$playerId->assists,
                        'minionKills'       => $pStats->$playerId->minionKills,
                        'doubleKills'       => $pStats->$playerId->doubleKills,
                        'tripleKills'       => $pStats->$playerId->tripleKills,
                        'quadraKills'       => $pStats->$playerId->quadraKills,
                        'pentaKills'        => $pStats->$playerId->pentaKills,
                        'playerName'        => $pStats->$playerId->playerName,
                        'role'              => $pStats->$playerId->role
                    ]);
                }
            }
        }
    }

});

Route::get('/insertfdata', function()
{

    Eloquent::unguard();

    $fTeamURL = 'http://fantasy.na.lolesports.com/en-US/api/season/4';
    $fTeamData = json_decode(file_get_contents($fTeamURL));

    foreach($fTeamData->proTeams as $team)
    {
        $fTeam = FTeam::firstOrCreate(['fId' => $team->id]);

        $fTeam->update([
            'fId'           => $team->id,
            'riotId'        => $team->riotId,
            'name'          => $team->name,
            'shortName'     => $team->shortName,
            'flavorText'    => $team->flavorTextEntries[0]->flavorText,
            'positions'     => $team->positions[0]
        ]);

        $fTeam->save();
    }

    $fPlayerURL = 'http://fantasy.na.lolesports.com/en-US/api/season/4';
    $fPlayerData = json_decode(file_get_contents($fPlayerURL));

    foreach($fPlayerData->proPlayers as $player)
    {
        $fPlayer = FPlayer::firstOrCreate(['fId' => $player->id]);

        $fPlayer->update([
            'fId'           => $player->id,
            'riotId'        => $player->riotId,
            'name'          => $player->name,
            'proTeamId'     => $player->proTeamId,
            'flavorText'    => (isset($player->flavorTextEntries[0]) ? $player->flavorTextEntries[0]->flavorText : null),
            'positions'     => $player->positions[0]
        ]);

        $fPlayer->save();
    }

});

Route::get('/insertgames', function()
{
    $matches = Match::where('isFinished', true)->get();

    Eloquent::unguard();

    foreach($matches as $match)
    {
        $gameURL = 'http://na.lolesports.com:80/api/game/' . $match->gameId . '.json';
        $gameData = json_decode(file_get_contents($gameURL));

        $playersInserted = array();

        foreach($gameData->players as $playerData)
        {
            if(GamePlayer::whereRaw('gameId = ' . $match->gameId . ' AND playerId = ' . $playerData->id)->count() == 0)
            {
                $itemArray = array();
                foreach($playerData as $key => $value)
                {
                    if(strpos($key, 'item') !== false)
                    {
                        $itemArray[] = $key;
                    }
                }

                $spellArray = array();
                foreach($playerData as $key => $value)
                {
                    if(strpos($key, 'spell') !== false)
                    {
                        $spellArray[] = $key;
                    }
                }

                $gamePlayer = GamePlayer::create([
                    'gameId'            => $match->gameId,
                    'playerId'          => $playerData->id,
                    'teamId'            => $playerData->teamId,
                    'name'              => $playerData->name,
                    'photoURL'          => $playerData->photoURL,
                    'championId'        => $playerData->championId,
                    'endLevel'          => $playerData->endLevel,
                    'kills'             => $playerData->kills,
                    'deaths'            => $playerData->deaths,
                    'assists'           => $playerData->assists,
                    'kda'               => $playerData->kda,
                    'item0Id'           => (isset($itemArray[0]) ? $playerData->$itemArray[0] : null),
                    'item1Id'           => (isset($itemArray[1]) ? $playerData->$itemArray[1] : null),
                    'item2Id'           => (isset($itemArray[2]) ? $playerData->$itemArray[2] : null),
                    'item3Id'           => (isset($itemArray[3]) ? $playerData->$itemArray[3] : null),
                    'item4Id'           => (isset($itemArray[4]) ? $playerData->$itemArray[4] : null),
                    'item5Id'           => (isset($itemArray[5]) ? $playerData->$itemArray[5] : null),
                    'item6Id'           => (isset($itemArray[6]) ? $playerData->$itemArray[6] : null),
                    'spell0Id'          => $playerData->$spellArray[0],
                    'spell1Id'          => $playerData->$spellArray[1],
                    'totalGold'         => $playerData->totalGold,
                    'minionsKilled'     => $playerData->minionsKilled
                ]);

                $playersInserted[] = $gamePlayer->id;
            }
        }


        $game = Game::firstOrCreate(['gameId' => $match->gameId]);

        $game->update([
            'dateTime'              => date('Y-m-d H:i:s', strtotime($gameData->dateTime)),
            'gameId'                => $match->gameId,
            'winnerId'              => $gameData->winnerId,
            'gameNumber'            => $gameData->gameNumber,
            'maxGames'              => $gameData->maxGames,
            'gameLength'            => $gameData->gameLength,
            'matchId'               => $gameData->matchId,
            'noVods'                => $gameData->noVods,
            'tournamentId'          => $gameData->tournament->id,
            'tournamentName'        => $gameData->tournament->name,
            'tournamentRound'       => $gameData->tournament->round,
            'vodType'               => ($gameData->vods == null ? null : $gameData->vods->vod->type),
            'vodURL'                => ($gameData->vods == null ? null : $gameData->vods->vod->URL),
            'embedCode'             => ($gameData->vods == null ? null : $gameData->vods->vod->embedCode),
            'blueId'                => $gameData->contestants->blue->id,
            'blueName'              => $gameData->contestants->blue->name,
            'blueLogoURL'           => $gameData->contestants->blue->logoURL,
            'redId'                 => $gameData->contestants->red->id,
            'redName'               => $gameData->contestants->red->name,
            'redLogoURL'            => $gameData->contestants->red->logoURL,
            'player0'               => $playersInserted[0],
            'player1'               => $playersInserted[1],
            'player2'               => $playersInserted[2],
            'player3'               => $playersInserted[3],
            'player4'               => $playersInserted[4],
            'player5'               => $playersInserted[5],
            'player6'               => $playersInserted[6],
            'player7'               => $playersInserted[7],
            'player8'               => $playersInserted[8],
            'player9'               => $playersInserted[9],
        ]);

        $game->save();
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


