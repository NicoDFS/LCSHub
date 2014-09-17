<?php

class Block extends Eloquent {


    public function matches()
    {
        return $this->hasMany('Match', 'blockId', 'blockId');
    }

    public function twitchUsername()
    {
        $league = $this->getLeague();

        if (preg_match('/twitch\.tv\/(?:([-\w]+)\/?)/', $league->twitch, $match) > 0)
        {
            return $match[1];
        }

        return 'riotgames';
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
        if(!isset($this->_tournament))
        {
            $this->_tournament = Tournament::where('tournamentId', $this->tournamentId)->first();
        }

        return $this->_tournament;
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

    public function requestedGame($gameId)
    {
        $this->requestedGame = $gameId;
    }

    public function getRequestedGame()
    {
        foreach($this->getMatches() as $ind => $val)
        {
            foreach($val->getGames() as $index => $value)
            {
                if($value->gameId == $this->requestedGame)
                {
                    return $index;
                }
            }
        }
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

        $datetime = new DateTime('now', new DateTimeZone($this->timezone()));
        if($this->dateTime > ($datetime->format('Y-m-d') . " 00:00:00") && $this->dateTime < ($datetime->format('Y-m-d') . " 23:59:59"))
        {
            return true;
        }

        return false;
    }

    public function retrieveCurrentBlock()
    {
        $datetime = new DateTime('now', new DateTimeZone($this->timezone()));

        $query = "dateTime >= '" . $datetime->format('Y-m-d') . " 00:00:00' AND dateTime <= '" . $datetime->format('Y-m-d') . " 23:59:59'";
        $todayBlock = Block::whereRaw($query)->first();

        if(is_null($todayBlock))
        {
            $todayBlock = Block::where('dateTime', '<=',  $datetime->format('Y-m-d') . " 23:59:59")->orderBy('dateTime', 'desc')->get()[0];
            $todayBlock->currBlock = false;
        }
        else
        {
            $todayBlock->currBlock = true;
        }
    }

    public function isMatchLive($matchId)
    {
        $matches = $this->getMatches();
        foreach($matches as $match)
        {
            if($match->isLive == 1 && $match->matchId == $matchId)
            {
                return true;
            }
        }

        return false;
    }

    public function isLiveMatch()
    {
        $matches = $this->getMatches();
        foreach($matches as $match)
        {
            if($match->isLive == 1)
            {
                return true;
            }
        }

        return false;
        //return (Match::where('blockId', $this->blockId)->where('isLive', true)->get()->count() > 0 ? true : false);
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
        $timezone = $this->timezone();

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
                    $placesArray[$key] = $prevPlace;
                }
                else
                {
                    $placesArray[$key] = $prevPlace;
                }

            }
            else
            {
                if (($counter % 100) >= 11 && ($counter % 100) <= 13)
                {
                    $placesArray[$key] = $counter;
                }
                else
                {
                    $placesArray[$key] = $counter;
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
        if(!isset($this->_isFutureBlocks))
        {
            $this->_isFutureBlocks = false;

            if(Block::where('dateTime', '>', $this->dateTime)->get()->count() > 0)
            {
                $this->_isFutureBlocks = true;
            }

        }

        return $this->_isFutureBlocks;
    }

    public function previousBlocks()
    {
        if(!isset($this->_isPreviousBlocks))
        {
            $this->_isPreviousBlocks = false;

            if(Block::where('dateTime', '<', $this->dateTime)->get()->count() > 0)
            {
                $this->_isPreviousBlocks = true;
            }
        }

        return $this->_isPreviousBlocks;
    }

    public function spoilers()
    {
        if(Cookie::has(Config::get('cookie.spoilers')))
        {
            if(Cookie::get(Config::get('cookie.spoilers')) == 1)
            {
                //if($this->isCurrentBlock())
                //{
                //    $matches = $this->getMatches();
                //}
                //else
                //{
                //    $matches = $this->retrieveCurrentBlock()->getMatches();
                //}

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

                $myMatches = $this->getMatches();
                foreach($myMatches as $match)
                {
                    if($match->isFinished)
                    {
                        $match->blueWins = $match->blueWins - $wins[$match->blueId];
                        $match->blueLosses = $match->blueLosses - $losses[$match->blueId];

                        $match->redWins = $match->redWins - $wins[$match->redId];
                        $match->redLosses = $match->redLosses - $losses[$match->redId];
                    }
                }


                $this->gotMatches = $myMatches;
            }
        }
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

    public static function defaultTimezone()
    {
        $timezone = Config::get('cookie.timezoneDefault');

        if(Cookie::has(Config::get('cookie.timezone')))
        {
            $timezone = Cookie::get(Config::get('cookie.timezone'));
        }

        return $timezone;
    }

    public static function currentBlock()
    {
        $datetime = new DateTime('now', new DateTimeZone(Block::defaultTimezone()));
        $dateplus = new DateTime('+1 day', new DateTimeZone(Block::defaultTimezone()));

        $blocks = Block::where('blocks.dateTime', '>=', $datetime->format('Y-m-d H:i:s'))
                        ->where('blocks.dateTime', '<=', $dateplus->format('Y-m-d H:i:s'))
                        ->join('matches', 'matches.blockId', '=', 'blocks.blockId')
                        ->select('blocks.*', 'matches.isFinished', 'matches.isLive')
                        ->get();

        $todayBlock = null;

        if(!empty($blocks))
        {
            foreach($blocks as $block)
            {
                if($block->isLive)
                {
                    $todayBlock = $block; break;
                }

                if(!$block->isFinished)
                {
                    $todayBlock = $block; break;
                }
            }

            if(is_null($todayBlock))
            {
                foreach($blocks as $block)
                {
                    if($block->isFinished)
                    {
                        $todayBlock = $block; break;
                    }
                }
            }
        }

        if(is_null($todayBlock))
        {
            $todayBlock = Block::where('dateTime', '<=',  $datetime->format('Y-m-d H:i:s'))->orderBy('dateTime', 'desc')->first();
            $todayBlock->currBlock = false;
        }
        else
        {
            $todayBlock->currBlock = true;
        }

        $todayBlock->setLatestBlock(true);

        return $todayBlock;
    }

    public function isLatestBlock()
    {
        if(isset($this->_latestBlock))
        {
            return $this->_latestBlock;
        }

        return false;
    }

    public function setLatestBlock($bool)
    {
        $this->_latestBlock = $bool;
    }

    public function getVideoPlayer()
    {
        if(Cookie::has(Config::get('cookie.player')))
        {
            if(Cookie::get(Config::get('cookie.player')) == 'twitch')
            {
                return '<object type="application/x-shockwave-flash" height="378" width="620" id="live_embed_player_flash" data="https://www.twitch.tv/widgets/live_embed_player.swf?channel=' . $this->twitchUsername() . '" bgcolor="#F6F6F6"><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="movie" value="https://www.twitch.tv/widgets/live_embed_player.swf" /><param name="flashvars" value="hostname=www.twitch.tv&channel=' . $this->twitchUsername() . '&auto_play=true&start_volume=100" /></object>';
            }
            elseif(Cookie::get(Config::get('cookie.player')) == 'youtube')
            {
                return '<iframe width="1280" height="720" src="//www.youtube.com/embed/' . $this->leagueYoutubeId() . '?t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>';
            }
            elseif(Cookie::get(Config::get('cookie.player')) == 'azubu')
            {
                return $this->getLeague()->azubu;
            }
        }
        else
        {
            return '<object type="application/x-shockwave-flash" height="378" width="620" id="live_embed_player_flash" data="https://www.twitch.tv/widgets/live_embed_player.swf?channel=' . $this->twitchUsername() . '" bgcolor="#F6F6F6"><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="movie" value="https://www.twitch.tv/widgets/live_embed_player.swf" /><param name="flashvars" value="hostname=www.twitch.tv&channel=' . $this->twitchUsername() . '&auto_play=true&start_volume=100" /></object>';
        }
    }

    public function color()
    {
        if($this->bodyTime >= date('Y-m-d H:i:s'))
        {
            return "#4D90FD";
        }
        else
        {
            return "#60C060";
        }
    }

    public function colorClass()
    {
        if($this->bodyTime >= date('Y-m-d H:i:s'))
        {
            return "primary";
        }
        else
        {
            return "success";
        }
    }

    public function status()
    {
        if($this->bodyTime >= date('Y-m-d H:i:s'))
        {
            return "Scheduled";
        }
        else
        {
            return "Finished";
        }
    }

}
