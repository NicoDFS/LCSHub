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

        if($this->isCurrentBlock())
        {
            foreach($matches as $match)
            {
                if($match->isLive)
                {
                    return $match;
                }
            }

        }
        else
        {
            return $matches[$this->gRequestedMatchIndex()];
        }

        return null;

    }

    public function requestedMatch($matchId)
    {
        $this->newMatchId = $matchId;
    }

    public function gRequestedMatchIndex()
    {
        foreach($this->matches as $ind => $match)
        {
            if($match->matchId == $this->newMatchId)
            {
                return $ind;
            }
        }
    }

    public function isFutureBlock()
    {
        if($this->dateTime > date('Y-m-d H:i:s'))
        return true;

        return false;
    }

    public function isCurrentBlock()
    {
        $timezone = 'America/Los_Angeles';
        Cookie::queue('timezone', 'America/Los_Angeles', (60 * 24));

        if(Cookie::get('timezone'))
        {
            $timezone = Cookie::get('timezone');
        }

        $datetime = new DateTime('now', new DateTimeZone($timezone));

        $query = "dateTime >= '" . $datetime->format('Y-m-d') . " 00:00:00' AND dateTime <= '" . $datetime->format('Y-m-d') . " 23:59:59'";
        $todayBlock = Block::whereRaw($query)->first();

        if(is_null($todayBlock))
        {
            $todayBlock = Block::where('dateTime', '<=',  $datetime->format('Y-m-d') . " 23:59:59")->orderBy('dateTime', 'desc')->get()[0];
        }

        if($todayBlock->id == $this->id)
        {
            return true;
        }

        return false;
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
