<?php

class FPlayer extends Eloquent {


    protected $table = 'fPlayers';

    public static function selectOptions($position)
    {
        $output = "";

        $players = FPlayer::where('positions', $position)->get();
        foreach($players as $key => $player)
        {
            if($key == 0)
            {
                $output .= "<optgroup label='{$player->positions}'>";
            }

            $output .= "<option value='{$player->riotId}'>{$player->name}</option>";
        }

        $output .= "</optgroup>";

        return $output;
    }

    public function playerRandom($position = null)
    {
        if(isset($this->_playersPositions))
        {
            if($position !== null)
            {
                return $this->_playersPositions[$position][ array_rand( $this->_playersPositions[$position] ) ];
            }
            else
            {
                $randPosition = array_rand($this->_playersPositions);
                $playerKey = array_rand($this->_playersPositions[$randPosition]);
                return $this->_playersPositions[$randPosition][$playerKey];
            }
        }

        return null;
    }

    public function playerOptions($position = null)
    {
        if(!isset($this->_selects))
        {
            $selects = array();

            $positions = array();
            $output = null;
            $lastPosition = null;

            $fPlayers = FPlayer::orderBy('positions', 'asc')->join('fTeams', 'fPlayers.proTeamId', '=', 'fTeams.fId')->select('fPlayers.*', 'fTeams.shortName')->get();

            foreach($fPlayers as $key => $player)
            {
                if($lastPosition == null)
                {
                    $output .= "<optgroup label='{$player->positions}'>";
                }

                if($lastPosition !== null && $lastPosition !== $player->positions)
                {
                    $output .= "</optgroup>";

                    $selects[$lastPosition] = $output;

                    $output = "<optgroup label='{$player->positions}'>";
                }

                $output .= "<option value='{$player->riotId}'>{$player->name} ({$player->shortName})</option>";
                $lastPosition = $player->positions;

                $positions[$player->positions][] = $player->name .  " ({$player->shortName})";
            }

            if($output !== null)
            {
                $output .= "</optgroup>";
                $selects['TOP_LANE'] = $output;

                $this->_playersPositions = $positions;
            }

            $this->_selects = $selects;
        }

        if($position !== null)
        {
            return $this->_selects[$position];
        }

        return array_values($this->_selects);
    }

    public static function allOptions()
    {
        $output = "";

        $players = FPlayer::orderBy('positions', 'asc')->get();
        $lastPosition = null;
        foreach($players as $key => $player)
        {
            if($lastPosition == null)
            {
                $output .= "<optgroup label='{$player->positions}'>";
            }

            if($lastPosition !== null && $lastPosition !== $player->positions)
            {
                $output .= "</optgroup><optgroup label='{$player->positions}'>";
            }

            $output .= "<option value='{$player->riotId}'>{$player->name}</option>";
            $lastPosition = $player->positions;

        }

        $output .= "</optgroup>";

        return $output;
    }
}
