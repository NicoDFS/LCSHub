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

    public function cssClass()
    {
        if($this->isLive)
        return 'label-danger';


        if(!$this->isLive && !$this->isFinished)
        return 'label-info';


        if($this->isFinished)
        return 'label-success';

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
         if($this->isLive)
        {
            return '#ED5B56';
        }

        if(!$this->isLive && !$this->isFinished)
        {
            return '#5DC4EA';
        }

        if($this->isFinished && !$this->isLive)
        {
            return '#60C060';
        }
    }

    public function game()
    {
        return Game::where('matchId', $this->matchId)->first();
    }

}
