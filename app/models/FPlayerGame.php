<?php

class FPlayerGame extends Eloquent {


    protected $table = 'fPlayerGames';

    public function fantasyPoints()
    {
        $points = 0;

        $points += ($this->kills * Config::get("fantasy.kills"));
        $points += ($this->deaths * Config::get("fantasy.deaths"));
        $points += ($this->assists * Config::get("fantasy.assists"));
        $points += ($this->minionKills * Config::get("fantasy.minionKills"));
        $points += ($this->tripleKills * Config::get("fantasy.tripleKills"));
        $points += ($this->quadraKills * Config::get("fantasy.quadraKills"));
        $points += ($this->pentaKills * Config::get("fantasy.pentaKills"));

        if($this->kills >= 10 or $this->assists >= 10)
        {
            $points += Config::get("fantasy.tenPlusKA");
        }

        return $points;
    }



}
