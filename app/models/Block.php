<?php

class Block extends Eloquent {


    public function matches()
    {
        return $this->hasMany('Match', 'blockId', 'blockId');
    }

    public function getLeague()
    {
        return League::where('leagueId', $this->leagueId)->first();
    }

    public function getTournament()
    {
        return Tournament::where('tournamentId', $this->tournamentId)->first();
    }

    public function activeMatch()
    {
        $matches = Match::where('blockId', $this->blockId)->get();

        foreach($matches as $match)
        {
            if($match->isLive)
            {
                return $match;
            }
        }

        return null;
    }

}
