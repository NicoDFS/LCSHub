<?php

class Game extends Eloquent {


    public function getPlayers()
    {
        return GamePlayer::where('gameId', $this->gameId)->get();
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


}
