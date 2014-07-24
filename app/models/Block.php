<?php

class Block extends Eloquent {


    public function matches()
    {
        return $this->hasMany('Match', 'blockId', 'blockId');
    }

    public function getLeague()
    {
        if(!isset($this->gotLeague))
        {
            $this->gotLeague = League::where('leagueId', $this->leagueId)->first();
        }

        return $this->gotLeague;
    }

    public function getTournament()
    {
        return Tournament::where('tournamentId', $this->tournamentId)->first();
    }

    public function getMatches()
    {
        if(!isset($this->gotMatches))
        {
            $this->gotMatches = Match::where('blockId', $this->blockId)->get();
        }

        return $this->gotMatches;
    }

    public function activeMatch()
    {

        $matches = $this->getMatches();

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
            if(isset($this->newMatchId))
            {
                return $matches[$this->gRequestedMatchIndex()];
            }
            else
            {
                return $matches[0];
            }
        }

        return null;

    }

    public function requestedMatch($matchId)
    {
        $this->newMatchId = $matchId;
    }

    public function gRequestedMatchIndex()
    {
        foreach($this->getMatches() as $ind => $match)
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
        if(isset($this->currBlock))
        {
            return $this->currBlock;
        }

        $timezone = 'America/Los_Angeles';
        Cookie::queue('timezone', 'America/Los_Angeles', (60 * 24));

        if(Cookie::get('timezone'))
        {
            $timezone = Cookie::get('timezone');
        }

        $datetime = new DateTime('now', new DateTimeZone($timezone));
        if($this->dateTime > ($datetime->format('Y-m-d') . " 00:00:00") && $this->dateTime < ($datetime->format('Y-m-d') . " 23:59:59"))
        {
            return true;
        }

        return false;

        //$query = "dateTime >= '" . $datetime->format('Y-m-d') . " 00:00:00' AND dateTime <= '" . $datetime->format('Y-m-d') . " 23:59:59'";
        //$todayBlock = Block::whereRaw($query)->first();
        //
        //if(is_null($todayBlock))
        //{
        //    $todayBlock = Block::where('dateTime', '<=',  $datetime->format('Y-m-d') . " 23:59:59")->orderBy('dateTime', 'desc')->get()[0];
        //}
        //
        //if($todayBlock->id == $this->id)
        //{
        //    return true;
        //}
        //
        //return false;
    }

    public function isLiveMatch()
    {
        return (Match::where('blockId', $this->blockId)->where('isLive', true)->get()->count() > 0 ? true : false);
    }

    public function leagueYoutubeId()
    {
        $yt = $this->getLeague()->youtube;
        return substr($yt, strpos($yt, "?v=") + 3);
    }

    public function firstMatch()
    {
        return Match::where('blockId', $this->blockId)->get()[0];
    }

    public function blockLabelDay()
    {
        $str = substr($this->label, strpos($this->label, " - ") + 3);
        $pos = strpos($str, ' ', strpos($str, ' ') + 1);
        return substr($str, $pos + 1);
    }

    public function blockLabelWeek()
    {
        $str = substr($this->label, strpos($this->label, " - ") + 3);
        $pos = strpos($str, ' ', strpos($str, ' ') + 1);
        return substr($str, 0, $pos);
    }

    public function blockTournamentName()
    {
        return substr($this->tournamentName, 0, 2);
    }

    public function putBackground($text, $class)
    {
        $html = "<span class='label-$class' style='
            color: white;
            padding-left: 9px;
            padding-right: 8px;
            padding-bottom: 4px;
            padding-top: 3px;
        '>$text</span>";

        return $html;
    }

}
