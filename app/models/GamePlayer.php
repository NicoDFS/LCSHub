<?php

class GamePlayer extends Eloquent {

    protected $table = 'gamePlayers';

    public function items()
    {
        if(!isset($this->_items))
        {
            $items = array();

            if($this->item0Id != 0)
            {
                $items[] = $this->item0Id;
            }

            if($this->item1Id  != 0)
            {
                $items[] = $this->item1Id;
            }

            if($this->item2Id  != 0)
            {
                $items[] = $this->item2Id;
            }

            if($this->item3Id  != 0)
            {
                $items[] = $this->item3Id;
            }

            if($this->item4Id  != 0)
            {
                $items[] = $this->item4Id;
            }

            if($this->item5Id  != 0)
            {
                $items[] = $this->item5Id;
            }

            $this->_items = $items;
        }

        return $this->_items;
    }

    public static function count_format($size)
    {
        $size = preg_replace('/[^0-9]/','',$size);
        $sizes = array("", "K", "M");
        if ($size == 0) { return('n/a'); } else {
        return (round($size/pow(1000, ($i = floor(log($size, 1000)))), 1) . $sizes[$i]); }
    }

    public function getFantasyPlayer()
    {
        if(!isset($this->_fantasyPlayer))
        {
            $this->_fantasyPlayer = FPlayerGame::where('gameId', $this->gameId)->where('fId', $this->playerId)->first();
        }

        return $this->_fantasyPlayer;
    }


}
