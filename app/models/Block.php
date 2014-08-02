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

            if(isset($this->newMatchId))
            {
                return $matches[$this->gRequestedMatchIndex()];
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

    public function matchesFinished()
    {
        $matches = $this->getMatches();
        $finished = true;
        foreach($matches as $match)
        {
            if($match->isFinished == false)
            {
                $finished = false;
            }
        }

        return $finished;

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

    public function timeFuture($ptime)
    {
        $etime = strtotime($ptime) - time();

        if ($etime < 1)
        {
            return '0 seconds';
        }

        $a = array( 12 * 30 * 24 * 60 * 60  =>  'Year',
                    30 * 24 * 60 * 60       =>  'Month',
                    24 * 60 * 60            =>  'Day',
                    60 * 60                 =>  'Hour',
                    60                      =>  'Minute',
                    1                       =>  'Second'
                    );

        foreach ($a as $secs => $str)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $r = round($d);
                return $r . ' ' . $str . ($r > 1 ? 's' : '');
            }
        }
    }

    public function lcsTime()
    {
        $timezone = 'America/Los_Angeles';

        $datetime = new DateTime($this->dateTime);
        $datetime->setTimezone(new DateTimeZone($timezone));
        return $datetime->format('g:i A');
    }

    public function sortPlaces()
    {
        if(isset($this->_places))
        {
            return $this->_places;
        }

        $tempArray = array();
        $placesArray = array();

        $matches = $this->getMatches();
        foreach($matches as $key => $match)
        {
            if(!isset($tempArray[$match->blueAcronym]))
            {
                $tempArray[$match->blueAcronym] = $match->blueWins;
            }

            if(!isset($tempArray[$match->redAcronym]))
            {
                $tempArray[$match->redAcronym] = $match->redWins;
            }

        }


        arsort($tempArray);
        $counter = 1;
        $prevKey = null;
        $prevPlace = null;

        $ends = array('th','st','nd','rd','th','th','th','th','th','th');

        foreach($tempArray as $key => $value)
        {
            if(!is_null($prevKey) && $tempArray[$key] == $tempArray[$prevKey])
            {
                if (($prevPlace % 100) >= 11 && ($prevPlace % 100) <= 13)
                {
                    $placesArray[$key] = $prevPlace . 'th';
                }
                else
                {
                    $placesArray[$key] = $prevPlace . $ends[$prevPlace % 10];
                }

            }
            else
            {
                if (($counter % 100) >= 11 && ($counter % 100) <= 13)
                {
                    $placesArray[$key] = $counter . 'th';
                }
                else
                {
                    $placesArray[$key] = $counter . $ends[$counter % 10];
                }

                $prevPlace = $counter;
            }

            $counter++;
            $prevKey = $key;
        }

        $this->_places = $placesArray;
    }

    public function futureBlocks()
    {
        if(Block::where('dateTime', '>', $this->dateTime)->get()->count() > 0)
        {
            return true;
        }

        return false;
    }

    public function previousBlocks()
    {
        if(Block::where('dateTime', '<', $this->dateTime)->get()->count() > 0)
        {
            return true;
        }

        return false;
    }

    public function spoilers()
    {
        $matches = $this->getMatches();
        $wins = array();
        $losses = array();

        foreach($matches as $match)
        {
            if($match->isFinished)
            {
                $wins[$match->blueId] = 0;
                $wins[$match->redId] = 0;
                $losses[$match->blueId] = 0;
                $losses[$match->redId] = 0;
            }
        }

        foreach($matches as $match)
        {
            if($match->isFinished)
            {
                if($match->winnerId == $match->blueId)
                {
                    $wins[$match->blueId] += 1;
                    $wins[$match->redId] += 0;
                    $losses[$match->blueId] += 0;
                    $losses[$match->redId] += 1;

                }
                elseif($match->winnerId == $match->redId)
                {
                    $wins[$match->blueId] += 0;
                    $wins[$match->redId] += 1;
                    $losses[$match->blueId] += 1;
                    $losses[$match->redId] += 0;
                }
            }
        }

        foreach($matches as $match)
        {
            if($match->isFinished)
            {
                $match->blueWins = $match->blueWins - $wins[$match->blueId];
                $match->blueLosses = $match->blueLosses - $losses[$match->blueId];

                $match->redWins = $match->redWins - $wins[$match->redId];
                $match->redLosses = $match->redLosses - $losses[$match->redId];
            }
        }


        $this->gotMatches = $matches;
    }

    public function timezone()
    {
        if(!isset($this->_timezone))
        {
            $timezone = Config::get('cookie.timezoneDefault');

            if(Cookie::has(Config::get('cookie.timezone')))
            {
                $timezone = Cookie::get(Config::get('cookie.timezone'));
            }

            $this->_timezone = $timezone;
        }

        return $this->_timezone;
    }

}
