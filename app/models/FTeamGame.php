<?php

class FTeamGame extends Eloquent {


    protected $table = 'fTeamGames';

    public function fantasyPoints()
    {
        $points = 0;

        $points += ($this->matchVictory * Config::get("fantasy.matchVictory"));
        $points += ($this->firstBlood * Config::get("fantasy.firstBlood"));
        $points += ($this->baronsKilled * Config::get("fantasy.baronsKilled"));
        $points += ($this->dragonsKilled * Config::get("fantasy.dragonsKilled"));
        $points += ($this->towersKilled * Config::get("fantasy.towersKilled"));

        return $points;
    }

    public function generatePopover()
    {
        $html =
        "<div class='table-responsive'>
            <table class='table table-bordered' style='margin-bottom:0px; cursor: default; '>
                <tr>
                    <td>Stat</td>
                    <td class='text-center'># * (Value)</td>
                    <td class='text-center'>Total</td>
                </tr>

                <tr>
                  <td>Victory:</td>
                  <td class='text-center'>" . $this->matchVictory . " (" . sprintf("%+d", Config::get("fantasy.matchVictory")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", ($this->matchVictory * Config::get("fantasy.matchVictory"))) . "</td>
                </tr>

                <tr>
                  <td>Barons:</td>
                  <td class='text-center'>" . $this->baronsKilled . " (" . sprintf("%+d", Config::get("fantasy.baronsKilled")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", ($this->baronsKilled * Config::get("fantasy.baronsKilled"))) . "</td>
                </tr>

                <tr>
                  <td>Dragons:</td>
                  <td class='text-center'>" . $this->dragonsKilled . " (" . sprintf("%+d", Config::get("fantasy.dragonsKilled")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", ($this->dragonsKilled * Config::get("fantasy.dragonsKilled"))) . "</td>
                </tr>

                <tr>
                  <td>Towers:</td>
                  <td class='text-center'>" . $this->towersKilled . " (" . sprintf("%+d", Config::get("fantasy.towersKilled")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", ($this->towersKilled * Config::get("fantasy.towersKilled"))) . "</td>
                </tr>

                <tr>
                  <td>1st Blood:</td>
                  <td class='text-center'>" . $this->firstBlood . " (" . sprintf("%+d", Config::get("fantasy.firstBlood")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", ($this->firstBlood * Config::get("fantasy.firstBlood"))) . "</td>
                </tr>

                <tr>
                    <td>Total:</td>
                    <td></td>
                    <td class='text-center'>" . sprintf("%+d", $this->fantasyPoints()) . "</td>
                </tr>

            </table>
        </div>";

        return $html;
    }


}
