<?php

class InsertController extends BaseController {


    public function all()
    {

        $start = microtime(true);

        echo $this->debug("Starting database seed");

        $leagues = $this->leagues(true);
        echo $this->debug("Inserted Leagues ($leagues)");

        $ttp = $this->tournamentTeamsPlayers(true);
        echo $this->debug("Inserted Tournaments, Teams, and Players (" . implode($ttp, ", ") . ")");

        $blocks = $this->blocks(true);
        echo $this->debug("Inserted Blocks and Matches (" . implode($blocks, ", ") . ")");

        $games = $this->games(true);
        echo $this->debug("Inserted Games and GamePlayers (" . implode($games, ", ") . ")");

        $fData = $this->fantasyTeamData(true);
        echo $this->debug("Inserted FantasyTeams and FantasyPlayers (" . implode($fData, ", ") . ")");

        $fGame = $this->fantasyGameData(true);
        echo $this->debug("Inserted FantasyTeamGames and FantasyPlayerGames (" . implode($fGame, ", ") . ")");

        $end = microtime(true);

        echo $this->debug("Finished database seed (" . gmdate("H:i:s", (int) ($end - $start)) . ")");
    }

    public function debug($message)
    {
        return "\033[37m[" . date("Y-m-d H:i:s") . "]\033[0m \033[31m" . $message . "\033[0m\n";
    }

    public function fantasyGameData($returnCount = false)
    {
        Eloquent::unguard();

        $tournaments = Tournament::all();
        $this->insertFantasyGameData($tournaments);

        if($returnCount)
        return [FTeamGame::all()->count(), FPlayerGame::all()->count()];

    }
    public function fantasyTeamData($returnCount = false)
    {
        Eloquent::unguard();

        $fTeamURL = "http://fantasy.na.lolesports.com/en-US/api/season/4?timestamp=" . time();
        $fTeamData = json_decode(file_get_contents($fTeamURL));

        foreach($fTeamData->proTeams as $team)
        {
            $fTeam = FTeam::firstOrCreate(["fId" => $team->id]);

            $fTeam->update([
                "fId"           => $team->id,
                "riotId"        => $team->riotId,
                "name"          => $team->name,
                "shortName"     => $team->shortName,
                "flavorText"    => (isset($team->flavorTextEntries[0]) ? $team->flavorTextEntries[0]->flavorText : null),
                "positions"     => $team->positions[0]
            ]);

        }

        foreach($fTeamData->proPlayers as $player)
        {
            $fPlayer = FPlayer::firstOrCreate(["fId" => $player->id]);

            $fPlayer->update([
                "fId"           => $player->id,
                "riotId"        => $player->riotId,
                "name"          => $player->name,
                "proTeamId"     => $player->proTeamId,
                "flavorText"    => (isset($player->flavorTextEntries[0]) ? $player->flavorTextEntries[0]->flavorText : null),
                "positions"     => $player->positions[0]
            ]);

        }

        if($returnCount)
        return [FTeam::all()->count(), FPlayer::all()->count()];
    }

    public function tournamentTeamsPlayers($returnCount = false)
    {
        Eloquent::unguard();

        $leagues = League::whereNotNull('defaultTournamentId')->orWhereNotNull('defaultSeriesId')->get();

        $pastTournaments = array(

            array(1, 104),
            array(2, 102),
            array(8, 162),
            array(2, 168),

        );

        foreach($pastTournaments as $lId => $tId)
        {
            $temp = new League;

            $temp->defaultTournamentId = $tId[1];
            $temp->leagueId = $tId[0];

            $leagues[] = $temp;

        }

        foreach($leagues as $league)
        {
            $tIds = array();

            if($league->defaultTournamentId == null && $league->defaultSeriesId != null)
            {
                $seriesDataURL = "http://na.lolesports.com:80/api/series/" . $league->defaultSeriesId . ".json?timestamp=" . time();
                $seriesData = json_decode(file_get_contents($seriesDataURL));

                foreach($seriesData->tournaments as $tourney)
                {
                    $tIds[] = $tourney;
                }

            }
            else if($league->defaultTournamentId != null)
            {
                $tIds[] = $league->defaultTournamentId;
            }

            if(!empty($tIds))
            {

                foreach($tIds as $tId)
                {

                    $leagueDataURL = "http://na.lolesports.com:80/api/tournament/" . $tId . ".json?timestamp=" . time();
                    $leagueData = json_decode(file_get_contents($leagueDataURL));

                    $tournament = Tournament::firstOrCreate(["tournamentId" => $tId]);

                    if(empty($leagueData->winner)) $leagueData->winner = null;

                    $tournament->update([
                        "leagueId"          => $league->leagueId,
                        "tournamentId"      => $tId,
                        "name"              => $leagueData->name,
                        "namePublic"        => $leagueData->namePublic,
                        "isFinished"        => $leagueData->isFinished,
                        "dateBegin"         => date("Y-m-d H:i:s", strtotime($leagueData->dateBegin)),
                        "dateEnd"           => date("Y-m-d H:i:s", strtotime($leagueData->dateEnd)),
                        "noVods"            => $leagueData->noVods,
                        "season"            => $leagueData->season,
                        "published"         => $leagueData->published,
                        "winner"            => $leagueData->winner
                    ]);

                    foreach($leagueData->contestants as $contestant)
                    {
                        if($contestant->id == null)
                        {
                            continue;
                        }

                        $contestantURL = "http://na.lolesports.com:80/api/team/" . $contestant->id . ".json?expandPlayers=1&timestamp=" . time();
                        $contestantData = json_decode(file_get_contents($contestantURL));

                        $team = Team::firstOrCreate(["teamId" => $contestant->id]);

                        $team->update([
                            "tournamentId"      => $tId,
                            "teamId"            => $contestant->id,
                            "name"              => $contestantData->name,
                            "bio"               => $contestantData->bio,
                            "noPlayers"         => $contestantData->noPlayers,
                            "logoUrl"           => $contestantData->logoUrl,
                            "profileUrl"        => $contestantData->profileUrl,
                            "teamPhotoUrl"      => $contestantData->teamPhotoUrl,
                            "acronym"           => ($contestantData->acronym == " " ? null : $contestantData->acronym )
                        ]);

                        if($contestantData->roster !== null)
                        {
                            foreach($contestantData->roster as $pData)
                            {
                                $playerId = substr($pData->profileUrl, strpos($pData->profileUrl, "/node/") + 6);

                                $player = Player::firstOrCreate(["playerId" => $playerId]);

                                $player->update([
                                    "playerId"      => $playerId,
                                    "name"          => $pData->name,
                                    "bio"           => $pData->bio,
                                    "firstName"     => $pData->firstname,
                                    "lastName"      => $pData->lastName,
                                    "hometown"      => $pData->hometown,
                                    "facebookURL"   => $pData->facebookUrl,
                                    "twitterURL"    => $pData->twitterUrl,
                                    "teamId"        => $contestant->id,
                                    "profileURL"    => $pData->profileUrl,
                                    "role"          => $pData->role,
                                    "roleId"        => $pData->roleId,
                                    "photoURL"      => $pData->photoUrl,
                                    "isStarter"     => $pData->isStarter
                                ]);

                            }
                        }
                    }
                }
            }
        }

        if($returnCount);
        return [Tournament::all()->count(), Team::all()->count(), Player::all()->count()];
    }

    public function leagues($returnCount = false)
    {
        Eloquent::unguard();

        for($i = 1; $i < 20; $i++)
        {
            $leagueURL = "http://na.lolesports.com:80/api/league/" . $i . ".json?timestamp=" . time();

            try
            {
                $leagueData = json_decode(file_get_contents($leagueURL));
            }
            catch(Exception $ex)
            {
                continue;
            }


            $this->insertLeagues($leagueData);
        }

        if($returnCount)
        return League::all()->count();
    }

    public function games($returnCount = false)
    {
        Eloquent::unguard();

        $matches = Match::where("isFinished", true)->orderBy('id', 'desc')->get();
        $this->insertGames($matches, true);

        if($returnCount)
        return [Game::all()->count(), GamePlayer::all()->count()];

    }
    public function blocks($returnCount = false)
    {
        Eloquent::unguard();

        $tournaments = Tournament::orderBy('id', 'desc')->get();

        foreach($tournaments as $tournament)
        {

            $programmingUrl = "http://na.lolesports.com/api/programming.json?parameters[method]=all&parameters[limit]=100&parameters[expand_matches]=1&parameters[tournament]=" . $tournament->tournamentId . "&timestamp=" . time();
            $programming = json_decode(file_get_contents($programmingUrl));

            $this->insertBlocks($programming, true);
        }

        if($returnCount)
        return [Block::all()->count(), Match::all()->count()];

    }

    public function insertBlocks($data)
    {
        Eloquent::unguard();

        foreach($data as $program)
        {
            $block = Block::firstOrCreate(["blockId" => $program->blockId]);
            if($program->tickets == " ") $program->tickets = null;

            $block->update([
                "dateTime"          => date("Y-m-d H:i:s", strtotime($program->dateTime)),
                "tickets"           => $program->tickets,
                "leagueId"          => $program->leagueId,
                "tournamentId"      => $program->tournamentId,
                "tournamentName"    => $program->tournamentName,
                "significance"      => $program->significance,
                "tbdTime"           => $program->tbdTime,
                "leagueColor"       => $program->leagueColor,
                "week"              => $program->week,
                "label"             => $program->label,
                "bodyTime"          => (isset($program->body[0]) ? date("Y-m-d H:i:s", strtotime($program->body[0]->bodyTime)) : null),
                "body"              => (isset($program->body[0]) ? $program->body[0]->body : null),
                "bodyTitle"         => (isset($program->body[0]) ? $program->body[0]->bodyTitle : null)

            ]);

            foreach($program->matches as $matchData)
            {
                $match = Match::firstOrCreate(["matchId" => $matchData->matchId]);

                if($matchData->winnerId == "") $matchData->winnerId = null;
                if($matchData->polldaddyId == " ") $matchData->polldaddyId = null;

                $match->update([
                    "dateTime"          => date("Y-m-d H:i:s", strtotime($matchData->dateTime)),
                    "matchName"         => $matchData->matchName,
                    "winnerId"          => $matchData->winnerId,
                    "url"               => $matchData->url,
                    "maxGames"          => $matchData->maxGames,
                    "isLive"            => $matchData->isLive,
                    "isFinished"        => $matchData->isFinished,
                    "liveStreams"       => (isset($matchData->liveStreams) ? true : false),
                    "polldaddyId"       => $matchData->polldaddyId,
                    "blockId"           => $program->blockId,

                    "tournamentId"      => $matchData->tournament->id,
                    "tournamentName"    => $matchData->tournament->name,
                    "tournamentRound"   => $matchData->tournament->round,

                    "gameId"            => $matchData->gamesInfo->game0->id,
                    "gameNoVods"        => $matchData->gamesInfo->game0->noVods,
                    "gameHasVod"        => $matchData->gamesInfo->game0->hasVod,
                ]);

                if($matchData->contestants !== null && isset($matchData->contestants->blue))
                {
                    $match->update([
                        "blueId"            => $matchData->contestants->blue->id,
                        "blueName"          => $matchData->contestants->blue->name,
                        "blueLogoURL"       => $matchData->contestants->blue->logoURL,
                        "blueAcronym"       => $matchData->contestants->blue->acronym,
                        "blueWins"          => $matchData->contestants->blue->wins,
                        "blueLosses"        => $matchData->contestants->blue->losses,
                    ]);

                    //Match::where('blueId', $matchData->contestants->blue->id)->update( ['blueWins' => $matchData->contestants->blue->wins, 'blueLosses' =>  $matchData->contestants->blue->losses] );
                    //Match::where('redId', $matchData->contestants->blue->id)->update( ['redWins' => $matchData->contestants->blue->wins, 'redLosses' =>  $matchData->contestants->blue->losses] );

                }

                if($matchData->contestants !== null && isset($matchData->contestants->red))
                {
                    $match->update([
                        "redId"             => $matchData->contestants->red->id,
                        "redName"           => $matchData->contestants->red->name,
                        "redLogoURL"        => $matchData->contestants->red->logoURL,
                        "redAcronym"        => $matchData->contestants->red->acronym,
                        "redWins"           => $matchData->contestants->red->wins,
                        "redLosses"         => $matchData->contestants->red->losses
                    ]);

                    //Match::where('blueId', $matchData->contestants->red->id)->update( ['blueWins' => $matchData->contestants->red->wins, 'blueLosses' =>  $matchData->contestants->red->losses] );
                    //Match::where('redId', $matchData->contestants->red->id)->update( ['redWins' => $matchData->contestants->red->wins, 'redLosses' =>  $matchData->contestants->red->losses] );

                }


            }

        }

    }

    public function insertGames($data)
    {
        foreach($data as $match)
        {
            $games = array();

            if($match->maxGames !== 1)
            {
                $matchURL = "http://na.lolesports.com:80/api/match/" . $match->matchId . ".json?timestamp=" . time();
                $matchData = json_decode(file_get_contents($matchURL));

                foreach($matchData->games as $key => $value)
                {
                    if(strpos($key, "game") !== false)
                    {
                        $games[] = $value->id;
                    }
                }
            }
            else
            {
                $games[] = $match->gameId;
            }

            foreach($games as $gameId)
            {

                $gameURL = "http://na.lolesports.com:80/api/game/" . $gameId . ".json?timestamp=" . time();
                $gameData = json_decode(file_get_contents($gameURL));

                $playersInserted = array();

                foreach($gameData->players as $playerData)
                {
                    $itemArray = array();
                    foreach($playerData as $key => $value)
                    {
                        if(strpos($key, "item") !== false)
                        {
                            $itemArray[] = $key;

                            if($value == "" || $value == 0)
                            {
                                $playerData->$key = null;
                            }
                        }
                    }

                    $spellArray = array();
                    foreach($playerData as $key => $value)
                    {
                        if(strpos($key, "spell") !== false)
                        {
                            $spellArray[] = $key;

                            if($value == "" || $value == 0)
                            {
                                $playerData->$key = null;
                            }
                        }
                    }

                    //foreach($itemArray as $k => $v)
                    //{
                    //    if($playerData->$itemArray[$v] == "" || $playerData->$itemArray[$v] == 0)
                    //    $playerData->$itemArray[$v] = null;
                    //}
                    //
                    //foreach($spellArray as $k => $v)
                    //{
                    //    if($playerData->$spellArray[$v] == "" || $playerData->$spellArray[$v] == 0)
                    //    $playerData->$spellArray[$v] = null;
                    //}

                    $playerRow = GamePlayer::whereRaw("gameId = " . $gameId . " AND playerId = " . $playerData->id)->get();

                    if($playerData->championId == 0) $playerData->championId = null;

                    if(count($playerRow) > 0)
                    {
                        $playerRow[0]->update([
                            "gameId"            => $gameId,
                            "playerId"          => $playerData->id,
                            "teamId"            => $playerData->teamId,
                            "name"              => $playerData->name,
                            "photoURL"          => $playerData->photoURL,
                            "championId"        => $playerData->championId,
                            "endLevel"          => $playerData->endLevel,
                            "kills"             => $playerData->kills,
                            "deaths"            => $playerData->deaths,
                            "assists"           => $playerData->assists,
                            "kda"               => $playerData->kda,
                            "item0Id"           => (isset($itemArray[0]) ? $playerData->$itemArray[0] : null),
                            "item1Id"           => (isset($itemArray[1]) ? $playerData->$itemArray[1] : null),
                            "item2Id"           => (isset($itemArray[2]) ? $playerData->$itemArray[2] : null),
                            "item3Id"           => (isset($itemArray[3]) ? $playerData->$itemArray[3] : null),
                            "item4Id"           => (isset($itemArray[4]) ? $playerData->$itemArray[4] : null),
                            "item5Id"           => (isset($itemArray[5]) ? $playerData->$itemArray[5] : null),
                            "spell0Id"          => (isset($spellArray[0]) ? $playerData->$spellArray[0] : null),
                            "spell1Id"          => (isset($spellArray[1]) ? $playerData->$spellArray[1] : null),
                            "totalGold"         => $playerData->totalGold,
                            "minionsKilled"     => $playerData->minionsKilled
                        ]);

                        $playersInserted[] = $playerRow[0]->id;
                    }
                    else
                    {
                        $gamePlayer = GamePlayer::create([
                            "gameId"            => $gameId,
                            "playerId"          => $playerData->id,
                            "teamId"            => $playerData->teamId,
                            "name"              => $playerData->name,
                            "photoURL"          => $playerData->photoURL,
                            "championId"        => $playerData->championId,
                            "endLevel"          => $playerData->endLevel,
                            "kills"             => $playerData->kills,
                            "deaths"            => $playerData->deaths,
                            "assists"           => $playerData->assists,
                            "kda"               => $playerData->kda,
                            "item0Id"           => (isset($itemArray[0]) ? $playerData->$itemArray[0] : null),
                            "item1Id"           => (isset($itemArray[1]) ? $playerData->$itemArray[1] : null),
                            "item2Id"           => (isset($itemArray[2]) ? $playerData->$itemArray[2] : null),
                            "item3Id"           => (isset($itemArray[3]) ? $playerData->$itemArray[3] : null),
                            "item4Id"           => (isset($itemArray[4]) ? $playerData->$itemArray[4] : null),
                            "item5Id"           => (isset($itemArray[5]) ? $playerData->$itemArray[5] : null),
                            "spell0Id"          => (isset($spellArray[0]) ? $playerData->$spellArray[0] : null),
                            "spell1Id"          => (isset($spellArray[1]) ? $playerData->$spellArray[1] : null),
                            "totalGold"         => $playerData->totalGold,
                            "minionsKilled"     => $playerData->minionsKilled
                        ]);

                        $playersInserted[] = $gamePlayer->id;
                    }

                }


                $game = Game::firstOrCreate(["gameId" => $gameId]);

                if($gameData->gameLength == 0) $gameData->gameLength = null;

                $game->update([
                    "dateTime"              => (!is_null($gameData->dateTime) ? date("Y-m-d H:i:s", strtotime($gameData->dateTime)) : null),
                    "gameId"                => $gameId,
                    "winnerId"              => $gameData->winnerId,
                    "gameNumber"            => ($gameData->gameNumber != 1 ? ($gameData->gameNumber - 1) : $gameData->gameNumber),
                    "maxGames"              => $gameData->maxGames,
                    "gameLength"            => $gameData->gameLength,
                    "matchId"               => $gameData->matchId,
                    "noVods"                => $gameData->noVods,
                    "tournamentId"          => $gameData->tournament->id,
                    "tournamentName"        => $gameData->tournament->name,
                    "tournamentRound"       => $gameData->tournament->round,
                    "vodType"               => ($gameData->vods == null ? null : $gameData->vods->vod->type),
                    "vodURL"                => ($gameData->vods == null ? null : $gameData->vods->vod->URL),
                    "embedCode"             => ($gameData->vods == null ? null : $gameData->vods->vod->embedCode),
                    "blueId"                => $gameData->contestants->blue->id,
                    "blueName"              => $gameData->contestants->blue->name,
                    "blueLogoURL"           => $gameData->contestants->blue->logoURL,
                    "redId"                 => $gameData->contestants->red->id,
                    "redName"               => $gameData->contestants->red->name,
                    "redLogoURL"            => $gameData->contestants->red->logoURL,
                    "player0"               => (isset($playersInserted[0]) ? $playersInserted[0] : null),
                    "player1"               => (isset($playersInserted[1]) ? $playersInserted[1] : null),
                    "player2"               => (isset($playersInserted[2]) ? $playersInserted[2] : null),
                    "player3"               => (isset($playersInserted[3]) ? $playersInserted[3] : null),
                    "player4"               => (isset($playersInserted[4]) ? $playersInserted[4] : null),
                    "player5"               => (isset($playersInserted[5]) ? $playersInserted[5] : null),
                    "player6"               => (isset($playersInserted[6]) ? $playersInserted[6] : null),
                    "player7"               => (isset($playersInserted[7]) ? $playersInserted[7] : null),
                    "player8"               => (isset($playersInserted[8]) ? $playersInserted[8] : null),
                    "player9"               => (isset($playersInserted[9]) ? $playersInserted[9] : null),
                    "platformId"            => $gameData->platformId,
                    "platformGameId"        => $gameData->platformGameId
                ]);
            }
        }

        //return [Game::all()->count(), GamePlayer::all()->count()];
    }

    public function insertFantasyGameData($data)
    {
        Eloquent::unguard();

        foreach($data as $tId)
        {

            if(!in_array($tId->tournamentId, Config::get('fantasygame.tournaments')))
            {
                continue;
            }

            $fGameURL = "http://na.lolesports.com:80/api/gameStatsFantasy.json?tournamentId=" . $tId->tournamentId . "&timestamp=" . time();
            $fGameData = json_decode(file_get_contents($fGameURL));

            foreach($fGameData->teamStats as $tKey => $tStats)
            {

                $teamArray = array();
                foreach($tStats as $key => $value)
                {
                    if(strpos($key, "team") !== false)
                    {
                        $teamArray[] = $key;
                    }
                }

                foreach($teamArray as $teamId)
                {

                    $fTeamGame = FTeamGame::whereRaw("gameId = " . substr($tKey, 4) . " AND teamId = " . $tStats->$teamId->teamId)->get();

                    if(count($fTeamGame) > 0)
                    {
                        $fTeamGame[0]->update([
                            "dateTime"          => ($tStats->dateTime != "1970-01-01T00:00Z" ?  date("Y-m-d H:i:s", strtotime($tStats->dateTime)) : null),
                            "gameId"            => (int) substr($tKey, 4),
                            "matchId"           => $tStats->matchId,
                            "teamId"            => $tStats->$teamId->teamId,
                            "teamName"          => $tStats->$teamId->teamName,
                            "matchVictory"      => $tStats->$teamId->matchVictory,
                            "matchDefeat"       => $tStats->$teamId->matchDefeat,
                            "baronsKilled"      => $tStats->$teamId->baronsKilled,
                            "dragonsKilled"     => $tStats->$teamId->dragonsKilled,
                            "firstBlood"        => $tStats->$teamId->firstBlood,
                            "firstTower"        => $tStats->$teamId->firstTower,
                            "firstInhibitor"    => $tStats->$teamId->firstInhibitor,
                            "towersKilled"      => $tStats->$teamId->towersKilled
                        ]);
                    }
                    else
                    {
                        $fTeamGame = FTeamGame::create([
                            "dateTime"          => ($tStats->dateTime != "1970-01-01T00:00Z" ?  date("Y-m-d H:i:s", strtotime($tStats->dateTime)) : null),
                            "gameId"            => (int) substr($tKey, 4),
                            "matchId"           => $tStats->matchId,
                            "teamId"            => $tStats->$teamId->teamId,
                            "teamName"          => $tStats->$teamId->teamName,
                            "matchVictory"      => $tStats->$teamId->matchVictory,
                            "matchDefeat"       => $tStats->$teamId->matchDefeat,
                            "baronsKilled"      => $tStats->$teamId->baronsKilled,
                            "dragonsKilled"     => $tStats->$teamId->dragonsKilled,
                            "firstBlood"        => $tStats->$teamId->firstBlood,
                            "firstTower"        => $tStats->$teamId->firstTower,
                            "firstInhibitor"    => $tStats->$teamId->firstInhibitor,
                            "towersKilled"      => $tStats->$teamId->towersKilled
                        ]);
                    }
                }
            }

            foreach($fGameData->playerStats as $pKey => $pStats)
            {
                $playerArray = array();
                foreach($pStats as $key => $value)
                {
                    if(strpos($key, "player") !== false)
                    {
                        $playerArray[] = $key;
                    }
                }

                foreach($playerArray as $playerId)
                {

                    $fPlayerGame = FPlayerGame::whereRaw("gameId = " . substr($pKey, 4) . " AND fId = " . $pStats->$playerId->playerId)->get();

                    if(count($fPlayerGame) > 0)
                    {
                        $fPlayerGame[0]->update([
                            "dateTime"          => ($pStats->dateTime != "1970-01-01T00:00Z" ?  date("Y-m-d H:i:s", strtotime($pStats->dateTime)) : null),
                            "matchId"           => $pStats->matchId,
                            "gameId"            => substr($pKey, 4),
                            "fId"               => $pStats->$playerId->playerId,
                            "kills"             => $pStats->$playerId->kills,
                            "deaths"            => $pStats->$playerId->deaths,
                            "assists"           => $pStats->$playerId->assists,
                            "minionKills"       => $pStats->$playerId->minionKills,
                            "doubleKills"       => $pStats->$playerId->doubleKills,
                            "tripleKills"       => $pStats->$playerId->tripleKills,
                            "quadraKills"       => $pStats->$playerId->quadraKills,
                            "pentaKills"        => $pStats->$playerId->pentaKills,
                            "playerName"        => $pStats->$playerId->playerName,
                            "role"              => $pStats->$playerId->role
                        ]);
                    }
                    else
                    {
                        $fPlayerGame = FPlayerGame::create([
                            "dateTime"          => ($pStats->dateTime != "1970-01-01T00:00Z" ?  date("Y-m-d H:i:s", strtotime($pStats->dateTime)) : null),
                            "matchId"           => $pStats->matchId,
                            "gameId"            => substr($pKey, 4),
                            "fId"               => $pStats->$playerId->playerId,
                            "kills"             => $pStats->$playerId->kills,
                            "deaths"            => $pStats->$playerId->deaths,
                            "assists"           => $pStats->$playerId->assists,
                            "minionKills"       => $pStats->$playerId->minionKills,
                            "doubleKills"       => $pStats->$playerId->doubleKills,
                            "tripleKills"       => $pStats->$playerId->tripleKills,
                            "quadraKills"       => $pStats->$playerId->quadraKills,
                            "pentaKills"        => $pStats->$playerId->pentaKills,
                            "playerName"        => $pStats->$playerId->playerName,
                            "role"              => $pStats->$playerId->role
                        ]);
                    }
                }
            }
        }

        //return [FTeamGame::all()->count(), FPlayerGame::all()->count()];
    }

    public function insertSpecificFantasyGameData($tournamentId, $games)
    {
        Eloquent::unguard();

        $fGameURL = "http://na.lolesports.com:80/api/gameStatsFantasy.json?timestamp=" . time() . "&tournamentId=" . $tournamentId;
        $fGameData = json_decode(file_get_contents($fGameURL));

        if(!empty($games))
        {
            foreach($games as $gameId)
            {
                if(property_exists($fGameData->teamStats, 'game' . $gameId))
                {

                    $tStats = $fGameData->teamStats->{'game' . $gameId};

                    $teamArray = array();
                    foreach($tStats as $key => $value)
                    {
                        if(strpos($key, "team") !== false)
                        {
                            $teamArray[] = $key;
                        }
                    }

                    foreach($teamArray as $teamId)
                    {
                        $fTeamGame = FTeamGame::whereRaw("gameId = " . $gameId . " AND teamId = " . $tStats->$teamId->teamId)->get();

                        if(count($fTeamGame) > 0)
                        {
                            $fTeamGame[0]->update([
                                "dateTime"          => (!is_null($tStats->dateTime) ? date("Y-m-d H:i:s", strtotime($tStats->dateTime)) : null),
                                "gameId"            => $gameId,
                                "matchId"           => $tStats->matchId,
                                "teamId"            => $tStats->$teamId->teamId,
                                "teamName"          => $tStats->$teamId->teamName,
                                "matchVictory"      => $tStats->$teamId->matchVictory,
                                "matchDefeat"       => $tStats->$teamId->matchDefeat,
                                "baronsKilled"      => $tStats->$teamId->baronsKilled,
                                "dragonsKilled"     => $tStats->$teamId->dragonsKilled,
                                "firstBlood"        => $tStats->$teamId->firstBlood,
                                "firstTower"        => $tStats->$teamId->firstTower,
                                "firstInhibitor"    => $tStats->$teamId->firstInhibitor,
                                "towersKilled"      => $tStats->$teamId->towersKilled
                            ]);
                        }
                        else
                        {
                            $fTeamGame = FTeamGame::create([
                                "dateTime"          => (!is_null($tStats->dateTime) ? date("Y-m-d H:i:s", strtotime($tStats->dateTime)) : null),
                                "gameId"            => $gameId,
                                "matchId"           => $tStats->matchId,
                                "teamId"            => $tStats->$teamId->teamId,
                                "teamName"          => $tStats->$teamId->teamName,
                                "matchVictory"      => $tStats->$teamId->matchVictory,
                                "matchDefeat"       => $tStats->$teamId->matchDefeat,
                                "baronsKilled"      => $tStats->$teamId->baronsKilled,
                                "dragonsKilled"     => $tStats->$teamId->dragonsKilled,
                                "firstBlood"        => $tStats->$teamId->firstBlood,
                                "firstTower"        => $tStats->$teamId->firstTower,
                                "firstInhibitor"    => $tStats->$teamId->firstInhibitor,
                                "towersKilled"      => $tStats->$teamId->towersKilled
                            ]);
                        }

                    }

                    $pStats = $fGameData->playerStats->{'game' . $gameId};

                    $playerArray = array();
                    foreach($pStats as $key => $value)
                    {
                        if(strpos($key, "player") !== false)
                        {
                            $playerArray[] = $key;
                        }
                    }

                    foreach($playerArray as $playerId)
                    {
                        $fPlayerGame = FPlayerGame::whereRaw("gameId = " . $gameId . " AND fId = " . $pStats->$playerId->playerId)->get();

                        if(count($fPlayerGame) > 0)
                        {
                            $fPlayerGame[0]->update([
                                "dateTime"          => (!is_null($pStats->dateTime) ? date("Y-m-d H:i:s", strtotime($pStats->dateTime)) : null),
                                "matchId"           => $pStats->matchId,
                                "gameId"            => $gameId,
                                "fId"               => $pStats->$playerId->playerId,
                                "kills"             => $pStats->$playerId->kills,
                                "deaths"            => $pStats->$playerId->deaths,
                                "assists"           => $pStats->$playerId->assists,
                                "minionKills"       => $pStats->$playerId->minionKills,
                                "doubleKills"       => $pStats->$playerId->doubleKills,
                                "tripleKills"       => $pStats->$playerId->tripleKills,
                                "quadraKills"       => $pStats->$playerId->quadraKills,
                                "pentaKills"        => $pStats->$playerId->pentaKills,
                                "playerName"        => $pStats->$playerId->playerName,
                                "role"              => $pStats->$playerId->role
                            ]);
                        }
                        else
                        {
                            $fPlayerGame = FPlayerGame::create([
                                "dateTime"          => (!is_null($pStats->dateTime) ? date("Y-m-d H:i:s", strtotime($pStats->dateTime)) : null),
                                "matchId"           => $pStats->matchId,
                                "gameId"            => $gameId,
                                "fId"               => $pStats->$playerId->playerId,
                                "kills"             => $pStats->$playerId->kills,
                                "deaths"            => $pStats->$playerId->deaths,
                                "assists"           => $pStats->$playerId->assists,
                                "minionKills"       => $pStats->$playerId->minionKills,
                                "doubleKills"       => $pStats->$playerId->doubleKills,
                                "tripleKills"       => $pStats->$playerId->tripleKills,
                                "quadraKills"       => $pStats->$playerId->quadraKills,
                                "pentaKills"        => $pStats->$playerId->pentaKills,
                                "playerName"        => $pStats->$playerId->playerName,
                                "role"              => $pStats->$playerId->role
                            ]);
                        }

                    }
                }
            }

        }

    }

    public function insertLeagues($data)
    {
        Eloquent::unguard();

        $leagueData = $data;
        $streams = array();

        if(isset($leagueData->internationalLiveStream))
        {
            foreach($leagueData->internationalLiveStream as $stream)
            {
                if($stream->language == "English" && $stream->display_language == "English")
                {
                    foreach($stream->streams as $lStream)
                    {
                        $streams[strtolower($lStream->title)] = $lStream->url;
                    }
                }
            }
        }

        $league = League::firstOrCreate(["leagueId" => $leagueData->id]);

        if($leagueData->leagueImage == " ") $leagueData->leagueImage = null;

        $league->update([
            "leagueId"              => $leagueData->id,
            "color"                 => $leagueData->color,
            "leagueImage"           => $leagueData->leagueImage,
            "defaultTournamentId"   => ($leagueData->defaultTournamentId == 0 ? null : $leagueData->defaultTournamentId),
            "defaultSeriesId"       => ($leagueData->defaultSeriesId == 0 ? null : $leagueData->defaultSeriesId),
            "shortName"             => $leagueData->shortName,
            "url"                   => $leagueData->url,
            "label"                 => $leagueData->label,
            "noVods"                => $leagueData->noVods,
            "menuWeight"            => $leagueData->menuWeight,
            "twitch"                => (isset($streams["twitch"]) ? $streams["twitch"] : null),
            "youtube"               => (isset($streams["youtube"]) ? $streams["youtube"] : null),
            "azubu"                 => (isset($streams["azubu"]) ? $streams["azubu"] : null),
            "leagueTournaments"     => implode(", ", $leagueData->leagueTournaments),
            "published"             => $leagueData->published
        ]);
    }

    public function today()
    {
        $timezone = 'America/Los_Angeles';

        $datetime = new DateTime('now', new DateTimeZone($timezone));
        $dateplus = new DateTime('+1 day', new DateTimeZone($timezone));

        $blocks = Block::where('blocks.dateTime', '>=', $datetime->format('Y-m-d H:i:s'))
                        ->where('blocks.dateTime', '<=', $dateplus->format('Y-m-d H:i:s'))
                        ->get();

        if(!empty($blocks))
        {
            foreach($blocks as $block)
            {
                $programmingUrl = "http://na.lolesports.com:80/api/programming/{$block->blockId}.json?expand_matches=1&timestamp=" . time();
                $programming = json_decode(file_get_contents($programmingUrl));

                $this->insertBlocks([$programming]);

                $matches = Match::where('blockId', $block->blockId)->get();
                $this->insertGames($matches);

                $gameIds = array();
                foreach($matches as $match)
                {
                    foreach($match->getGames() as $game)
                    {
                        $gameIds[] = $game->gameId;
                    }
                }

                $this->insertSpecificFantasyGameData($block->tournamentId, $gameIds);
            }
        }

    }

    public function todayLeague()
    {
        $timezone = 'America/Los_Angeles';

        $datetime = new DateTime('now', new DateTimeZone($timezone));
        $dateplus = new DateTime('+1 day', new DateTimeZone($timezone));

        $leagues = League::select('leagues.*')
                            ->from('leagues')
                            ->join('blocks', 'blocks.leagueId', '=', 'leagues.leagueId')
                            ->where('blocks.dateTime', '>=', $datetime->format('Y-m-d H:i:s'))
                            ->where('blocks.dateTime', '<=', $dateplus->format('Y-m-d H:i:s'))
                            ->get();

        if(!empty($leagues))
        {
            foreach($leagues as $league)
            {
                if($league !== null)
                {
                    $leagueURL = "http://na.lolesports.com:80/api/league/" . $league->leagueId . ".json?timestamp=" . time();
                    $leagueData = json_decode(file_get_contents($leagueURL));

                    $this->insertLeagues($leagueData);
                    echo "Inserted League: " . $league->leagueId;
                }
            }
        }
    }
}
