<?php

class FTeam extends Eloquent {


    protected $table = 'fTeams';

    public static function allOptions()
    {
        $output = "<optgroup label='{$team->positions}'>";

        $teams = FTeam::all();
        foreach($teams as $key => $team)
        {
            if($key == 0)
            {
                $output = "<optgroup label='{$team->positions}'>";
            }

            $output .= "<option value='{$team->riotId}'>{$team->name}</option>";
        }

        $output .= "</optgroup>";

        return $output;
    }

    public function teamOptions()
    {

        if(!isset($this->_teamSelect))
        {
            $teams = FTeam::select('fTeams.*', 'leagues.shortName', 'teams.acronym')
                            ->join('teams', 'teams.teamId', '=', 'fTeams.riotId')
                            ->join('tournaments', 'tournaments.tournamentId', '=', 'teams.tournamentId')
                            ->join('leagues', 'leagues.leagueId', '=', 'tournaments.leagueId')->get();

            $lastPosition = null;
            $positions = array();
            $output = null;

            foreach($teams as $key => $team)
            {
                if($lastPosition == null)
                {
                    $output = "<optgroup label='{$team->shortName}'>";
                }

                if($lastPosition !== null && $lastPosition !== $team->shortName)
                {
                    $output .= "</optgroup>";

                    //$selects[$lastPosition] = $output;

                    $output .= "<optgroup label='{$team->shortName}'>";
                }

                $output .= "<option value='{$team->riotId}'>{$team->name} ({$team->acronym})</option>";
                $lastPosition = $team->shortName;

                $positions[] = "{$team->name} ({$team->acronym})";
            }

            if($output !== null)
            {
                $output .= "</optgroup>";

                $this->_teamSelect = $output;
                $this->_teamPositions = $positions;
            }
        }

        return $this->_teamSelect;
    }

    public function teamOptionsRandom()
    {
        if(isset($this->_teamPositions))
        {
            return $this->_teamPositions[ array_rand($this->_teamPositions) ];
        }
    }


}
