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
