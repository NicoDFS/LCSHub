<?php

class Match extends Eloquent {


    public function block()
    {
        return $this->belongsTo('Block', 'blockId', 'blockId');
    }

    public function scopeFinished($query)
    {
        return $query->where('isFinished', true);
    }

    public function seriesWinner()
    {
        $games = $this->getGames();
        $blueWins = 0;
        $redWins = 0;

        foreach($games as $game)
        {
            if($game->winnerId == $game->redId) $redWins++;
            if($game->winnerId == $game->blueId) $blueWins++;
        }

        if($blueWins == 0 && $redWins == 0)
        {
            return -2;
        }

        if($blueWins > $redWins)
        {
            return $this->blueId;
        }
        elseif($redWins > $blueWins)
        {
            return $this->redId;
        }
        elseif($redWins == $blueWins)
        {
            return -1;
        }
    }

    public function seriesResult()
    {
        $games = $this->getGames();
        $blueWins = 0;
        $redWins = 0;

        foreach($games as $game)
        {
            if($game->winnerId == $game->redId) $redWins++;
            if($game->winnerId == $game->blueId) $blueWins++;
        }

        if($blueWins > $redWins)
        {
            return $blueWins . ' -- ' . $redWins;
        }
        else
        {
            return $redWins  . ' -- ' . $blueWins;
        }

    }

    public function cssClass()
    {
        if($this->isLive)
        return 'danger';


        if(!$this->isLive && !$this->isFinished)
        return 'primary';


        if($this->isFinished)
        return 'success';

    }

    public function isLiveActive()
    {
        if($this->status() == 'Live')
        {
            return "active";
        }
    }

    public function isLiveText()
    {
        if($this->status() == 'Live')
        {
            return "color: white;";
        }
    }

    public function status()
    {
        if($this->isLive)
        {
            return 'Live';
        }

        if(!$this->isLive && !$this->isFinished)
        {
            return 'Scheduled';
        }

        if($this->isFinished && !$this->isLive)
        {
            return 'Finished';
        }
    }

    public function color()
    {
        if($this->status() == 'Live')
        {
            return '#ED5B56';
        }

        if($this->status() == 'Scheduled')
        {
            return '#4D90FD';
        }

        if($this->status() == 'Finished')
        {
            return '#60C060';
        }
    }

    public function getGames()
    {
        if(!isset($this->_games))
        {
            $this->_games = Game::where('matchId', $this->matchId)->get();
        }

        return $this->_games;
    }

    public function liveGameCount()
    {
        $games = $this->getGames();
        $last = null;

        foreach($games as $key => $value)
        {
            if($key == count($games) - 1)
            $last = $value;
        }

        if($last->winnerId == null)
        {
            return count($games);
        }
        else
        {
            return count($games) + 1;
        }
    }

    public function game()
    {
        return Game::where('matchId', $this->matchId)->first();
    }

    public function getGame()
    {
        if(!isset($this->_game))
        {
            $this->_game = Game::where('matchId', $this->matchId)->first();
        }

        return $this->_game;
    }

    public function winner($id)
    {
        if($id == null) return null;

        if($this->winnerId == $id)
        {
            return "font-weight: 600;";
        }
    }

    public function winnerImg($id)
    {
        if($this->winnerId == $id && $this->winnerId !== null)
        {
            return "border: 3px solid #60C060;";
        }
        elseif($this->winnerId !== null && $this->winnerId !== $id)
        {
            return "border: 3px solid rgb(0, 0, 0);";
        }
    }

    public function winnerImgURL()
    {
        if($this->winnerId == $this->blueId)
        {
            return $this->blueLogoURL;
        }
        elseif($this->winnerId == $this->redId)
        {
            return $this->redLogoURL;
        }
    }

}
