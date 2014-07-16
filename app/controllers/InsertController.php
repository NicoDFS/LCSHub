<?php

class InsertController extends BaseController {


    public function fantasyGameData()
    {
        Eloquent::unguard();

        $tournaments = Tournament::all();

        foreach($tournaments as $tId)
        {
            $fGameURL = 'http://na.lolesports.com:80/api/gameStatsFantasy.json?tournamentId=' . $tId->tournamentId;
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
    }

    public function fantasyTeamData()
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
                'flavorText'    => (isset($team->flavorTextEntries[0]) ? $team->flavorTextEntries[0]->flavorText : null),
                'positions'     => $team->positions[0]
            ]);

        }

        foreach($fTeamData->proPlayers as $player)
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

        }
    }

    public function tournamentTeamsPlayers()
    {
        Eloquent::unguard();

        $leagues = League::whereRaw('defaultTournamentId = ' . Config::get('tournaments.NA_LCS') . ' OR defaultTournamentId = ' . Config::get('tournaments.EU_LCS'))->get();

        foreach($leagues as $league)
        {
            $leagueDataURL = 'http://na.lolesports.com:80/api/tournament/' . $league->defaultTournamentId . '.json';
            $leagueData = json_decode(file_get_contents($leagueDataURL));

            $tournament = Tournament::firstOrCreate(['tournamentId' => $league->defaultTournamentId]);

            $tournament->update([
                'leagueId'          => $league->leagueId,
                'tournamentId'      => $league->defaultTournamentId,
                'name'              => $leagueData->name,
                'namePublic'        => $leagueData->namePublic,
                'isFinished'        => $leagueData->isFinished,
                'dateBegin'         => date('Y-m-d H:i:s', strtotime($leagueData->dateBegin)),
                'dateEnd'           => date('Y-m-d H:i:s', strtotime($leagueData->dateEnd)),
                'noVods'            => $leagueData->noVods,
                'season'            => $leagueData->season
            ]);

            foreach($leagueData->contestants as $contestant)
            {
                $contestantURL = 'http://na.lolesports.com:80/api/team/' . $contestant->id . '.json?expandPlayers=1';
                $contestantData = json_decode(file_get_contents($contestantURL));

                $team = Team::firstOrCreate(['teamId' => $contestant->id]);

                $team->update([
                    'tournamentId'      => $league->defaultTournamentId ,
                    'teamId'            => $contestant->id,
                    'name'              => $contestantData->name,
                    'bio'               => $contestantData->bio,
                    'noPlayers'         => $contestantData->noPlayers,
                    'logoUrl'           => $contestantData->logoUrl,
                    'profileUrl'        => $contestantData->profileUrl,
                    'teamPhotoUrl'      => $contestantData->teamPhotoUrl,
                    'acronym'           => $contestantData->acronym
                ]);

                foreach($contestantData->roster as $pData)
                {
                    $playerId = substr($pData->profileUrl, strpos($pData->profileUrl, '/node/') + 6);

                    $player = Player::firstOrCreate(['playerId' => $playerId]);

                    $player->update([
                        'playerId'      => $playerId,
                        'name'          => $pData->name,
                        'bio'           => $pData->bio,
                        'firstName'     => $pData->firstname,
                        'lastName'      => $pData->lastName,
                        'hometown'      => $pData->hometown,
                        'facebookURL'   => $pData->facebookUrl,
                        'twitterURL'    => $pData->twitterUrl,
                        'teamId'        => $contestant->id,
                        'profileURL'    => $pData->profileUrl,
                        'role'          => $pData->role,
                        'roleId'        => $pData->roleId,
                        'photoURL'      => $pData->photoUrl
                    ]);

                }
            }
        }
    }

    public function leagues()
    {
        Eloquent::unguard();

        for($i = 1; $i < 20; $i++)
        {
            $leagueURL = 'http://na.lolesports.com:80/api/league/' . $i . '.json';

            try
            {
                $leagueData = json_decode(file_get_contents($leagueURL));
            }
            catch(Exception $ex)
            {
                continue;
            }

            $streams = array();

            if(isset($leagueData->internationalLiveStream))
            {
                foreach($leagueData->internationalLiveStream as $stream)
                {
                    if($stream->language == 'English' && $stream->display_language == 'English')
                    {
                        foreach($stream->streams as $lStream)
                        {
                            $streams[strtolower($lStream->title)] = $lStream->url;
                        }
                    }
                }
            }

            $league = League::firstOrCreate(['leagueId' => $leagueData->id]);

            $league->update([
                'leagueId'              => $leagueData->id,
                'color'                 => $leagueData->color,
                'leagueImage'           => $leagueData->leagueImage,
                'defaultTournamentId'   => $leagueData->defaultTournamentId,
                'defaultSeriesId'       => $leagueData->defaultSeriesId,
                'shortName'             => $leagueData->shortName,
                'url'                   => $leagueData->url,
                'label'                 => $leagueData->label,
                'noVods'                => $leagueData->noVods,
                'menuWeight'            => $leagueData->menuWeight,
                'twitch'                => (isset($streams['twitch']) ? $streams['twitch'] : null),
                'youtube'               => (isset($streams['youtube']) ? $streams['youtube'] : null),
                'azubu'                 => (isset($streams['azubu']) ? $streams['azubu'] : null),
                'leagueTournaments'     => implode(', ', $leagueData->leagueTournaments)
            ]);

        }
    }

    public function games()
    {
        Eloquent::unguard();

        $matches = Match::where('isFinished', true)->get();

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
                        'spell0Id'          => (isset($spellArray[0]) ? $playerData->$spellArray[0] : null),
                        'spell1Id'          => (isset($spellArray[1]) ? $playerData->$spellArray[1] : null),
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
        }
    }

    public function blocks()
    {
        Eloquent::unguard();

        $tournaments = Tournament::all();

        foreach($tournaments as $tournament)
        {

            $programmingUrl = 'http://na.lolesports.com/api/programming.json/?parameters[method]=all&parameters[limit]=100&parameters[expand_matches]=1&parameters[tournament]=' . $tournament->tournamentId;
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

                }
            }
        }
    }

}
