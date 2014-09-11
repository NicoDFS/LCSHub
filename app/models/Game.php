<?php

class Game extends Eloquent {


    public function getPlayers()
    {
        if(!isset($this->_players))
        {
            $this->_players = GamePlayer::where('gameId', $this->gameId)->get();
        }
        return $this->_players;
    }

    public function getPlayersAndFantasy()
    {
        if(!isset($this->_playersF))
        {
            $gamePlayers = GamePlayer::where('gameId', $this->gameId)->get();
            $fantasyPlayers = null;
            $join = array();

            if(count($gamePlayers) == 10)
            {
                    if($gamePlayers[0]->championId !== null)
                    {
                        $fantasyPlayers = FPlayerGame::where('gameId', $this->gameId)->get();
                    }
            }

            foreach($gamePlayers as $player)
            {
                $temp = $player;
                $temp->fantasyPlayer = null;

                if(!empty($fantasyPlayers))
                {
                    foreach($fantasyPlayers as $fPlayer)
                    {
                        if($fPlayer->fId == $player->playerId)
                        {
                            $temp->fantasyPlayer = $fPlayer;
                        }
                    }
                }

                $join[] = $temp;
            }

            $this->_playersF = $join;

        }

        return $this->_playersF;
    }

    public function teams()
    {
        if(!isset($this->_teams))
        {
            $teams = array();

            $players = $this->getPlayersAndFantasy();

            if(!empty($players))
            {
                foreach($players as $player)
                {
                    if($player->championId == null)
                    {
                        continue;
                    }

                    if($player->teamId == $this->blueId)
                    {
                        $teams[$this->blueId][] = $player;
                    }
                    elseif($player->teamId == $this->redId)
                    {
                        $teams[$this->redId][] = $player;
                    }
                }
            }

            if(!empty($teams))
            {
                if(array_search($this->blueId, array_keys($teams)) != 0)
                {
                    $teams = array_reverse($teams, true);
                }
            }

            $this->_teams = $teams;
        }

        return $this->_teams;
    }

    public function getFantasyPlayers()
    {
        return FPlayerGame::where('gameId', $this->gameId)->get();
    }

    public function getFantasyTeams()
    {
        return FTeamGame::where('gameId', $this->gameId)->get();
    }

    public function youtubeId()
    {
        $yt = $this->vodURL;
        return substr($yt, strpos($yt, "?v=") + 3);
    }

    public function fullPlayers()
    {
        if(!is_null($this->player0) && !is_null($this->player2) && !is_null($this->player2) && !is_null($this->player3) && !is_null($this->player4) && !is_null($this->player5) && !is_null($this->player6) && !is_null($this->player7) && !is_null($this->player8) && !is_null($this->player9) )
        {
            return true;
        }

        return false;
    }

    public function winnerName()
    {
        if($this->winnerId == $this->blueId)
        {
            return $this->blueName;
        }
        else if($this->winnerId == $this->redId)
        {
            return $this->redName;
        }
    }

    public function fantasyTeams()
    {
        if(!isset($this->_fantasyTeams))
        {
            $fTeamsArray = array();
            $fTeams = FTeamGame::where('gameId', $this->gameId)->get();

            if(!empty($fTeams))
            {
                foreach($fTeams as $key => $value)
                {
                    if($value->teamId == $this->blueId)
                    {
                       $fTeamsArray[$this->blueId] = $value;
                    }

                    if($value->teamId == $this->redId)
                    {
                        $fTeamsArray[$this->redId] = $value;
                    }
                }
            }

            $this->_fantasyTeams = $fTeamsArray;
        }

        return $this->_fantasyTeams;
    }

    public function getMatch()
    {
        if(!isset($this->_match))
        {
            $this->_match = Match::where('matchId', $this->matchId)->first();
        }

        return $this->_match;
    }


}
