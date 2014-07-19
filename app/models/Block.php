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

        return $matches[0];
    }

    public function isLiveMatch()
    {
        return (Match::where('blockId', $this->blockId)->where('isLive', true)->get()->count() > 0 ? true : false);
    }

    public function leagueYoutubeId()
    {
        $yt = League::where('leagueId', $this->leagueId)->first()->youtube;
        return substr($yt, strpos($yt, "?v=") + 3);
    }

    public function firstMatch()
    {
        return Match::where('blockId', $this->blockId)->get()[0];
    }

}
