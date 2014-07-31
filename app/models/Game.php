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

    public function teams()
    {
        if(!isset($this->_teams))
        {
            //$teams = array();
            //$counter = 0;
            //$players = $this->getPlayers();
            //foreach($players as $player)
            //{
            //    if($counter < 5)
            //    {
            //        $teams[0][] = $player;
            //    }
            //    else
            //    {
            //        $teams[1][] = $player;
            //    }
            //
            //    $counter++;
            //}
            //
            //$this->_teams = $teams;

            $teams = array();

            $players = $this->getPlayers();
            foreach($players as $player)
            {
                if($player->teamId == $this->blueId)
                {
                    $teams[$this->blueId][] = $player;
                }
                elseif($player->teamId == $this->redId)
                {
                    $teams[$this->redId][] = $player;
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
            $fTeams = FTeamGame::where('gameId', $this->gameId)->get();
            $this->_fantasyTeams = $fTeams;
        }

        return $this->_fantasyTeams;
    }


}
