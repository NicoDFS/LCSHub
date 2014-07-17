<?php

class FTeamGame extends Eloquent {


    protected $table = 'fTeamGames';

    public function getFantasyPoints()
    {
        $points = 0;

        $points += ($this->matchVictory * Config::get("fantasy.matchVictory"));
        $points += ($this->firstBlood * Config::get("fantasy.firstBlood"));
        $points += ($this->baronsKilled * Config::get("fantasy.baronsKilled"));
        $points += ($this->dragonsKilled * Config::get("fantasy.dragonsKilled"));
        $points += ($this->towersKilled * Config::get("fantasy.towersKilled"));

        return $points;
    }


}
