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


}
