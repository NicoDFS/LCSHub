<?php

class Game extends Eloquent {


    public function getPlayers()
    {
        $players = array();

        for($i = 0; $i < 10; $i++)
        {
            $playerName = "player$i";

            $players[] = GamePlayer::where('id', $this->$playerName)->first();
        }

        return $players;
    }

    public function getFantasyPlayers()
    {
        return FPlayerGame::where('gameId', $this->gameId)->get();
    }

    public function getFantasyTeams()
    {
        return FTeamGame::where('gameId', $this->gameId)->get();
    }


}
