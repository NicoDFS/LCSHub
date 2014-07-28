<?php

class FTeam extends Eloquent {


    protected $table = 'fTeams';
    /*
    public function players()
    {
        return $this->hasMany('GamePlayer', 'player', 'blockId');
    }
    */

    public static function allOptions()
    {
        $output = "";

        $teams = FTeam::all();
        foreach($teams as $key => $team)
        {
            if($key == 0)
            {
                $output .= "<optgroup label='{$team->positions}'>";
            }

            $output .= "<option value='{$team->riotId}'>{$team->name}</option>";
        }

        $output .= "</optgroup>";

        return $output;
    }


}
