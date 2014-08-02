<?php

class FPlayerGame extends Eloquent {


    protected $table = 'fPlayerGames';

    public function fantasyPoints()
    {
        $points = 0;

        $points += ($this->kills * Config::get("fantasy.kills"));
        $points += ($this->deaths * Config::get("fantasy.deaths"));
        $points += ($this->assists * Config::get("fantasy.assists"));
        $points += ($this->minionKills * Config::get("fantasy.minionKills"));
        $points += ($this->tripleKills * Config::get("fantasy.tripleKills"));
        $points += ($this->quadraKills * Config::get("fantasy.quadraKills"));
        $points += ($this->pentaKills * Config::get("fantasy.pentaKills"));

        if($this->kills >= 10 or $this->assists >= 10)
        {
            $points += Config::get("fantasy.tenPlusKA");
        }

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
                  <td>Kills:</td>
                  <td class='text-center'>" . $this->kills . " (" . sprintf("%+d", Config::get("fantasy.kills")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", ($this->kills * Config::get("fantasy.kills"))) . "</td>
                </tr>

                <tr>
                  <td>Deaths:</td>
                  <td class='text-center'>" . $this->deaths . " (" . rtrim(rtrim(sprintf("%+f", Config::get("fantasy.deaths")), "0"),".") . ")</td>
                  <td class='text-center'>" . rtrim(rtrim(sprintf("%+f", ($this->deaths * Config::get("fantasy.deaths"))), "0"),".") . "</td>
                </tr>

                <tr>
                  <td>Assists:</td>
                  <td class='text-center'>" . $this->assists . " (" . rtrim(rtrim(sprintf("%+f", Config::get("fantasy.assists")), "0"),".") . ")</td>
                  <td class='text-center'>" . rtrim(rtrim(sprintf("%+f", ($this->assists * Config::get("fantasy.assists"))), "0"),".") . "</td>
                </tr>

                <tr>
                  <td>CS:</td>
                  <td class='text-center'>" . $this->minionKills . " (" . rtrim(rtrim(sprintf("%+f", Config::get("fantasy.minionKills")), "0"),".") . ")</td>
                  <td class='text-center'>" . rtrim(rtrim(sprintf("%+f", ($this->minionKills * Config::get("fantasy.minionKills"))), "0"),".") . "</td>
                </tr>

                <tr>
                  <td>Triple Kills:</td>
                  <td class='text-center'>" . $this->tripleKills . " (" . sprintf("%+d", Config::get("fantasy.tripleKills")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", ($this->tripleKills * Config::get("fantasy.tripleKills"))) . "</td>
                </tr>

                <tr>
                  <td>Quadra Kills:</td>
                  <td class='text-center'>" . $this->quadraKills . " (" . sprintf("%+d", Config::get("fantasy.quadraKills")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", ($this->quadraKills * Config::get("fantasy.quadraKills"))) . "</td>
                </tr>

                <tr>
                  <td>Penta Kills:</td>
                  <td class='text-center'>" . $this->pentaKills . " (" . sprintf("%+d", Config::get("fantasy.pentaKills")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", ($this->pentaKills * Config::get("fantasy.pentaKills"))) . "</td>
                </tr>

                <tr>
                  <td>10+ K/A:</td>
                  <td class='text-center'>" . ( ($this->kills >= 10 || $this->assists >= 10) ? '1' : '0') . " (" . sprintf("%+d", Config::get("fantasy.tenPlusKA")) . ")</td>
                  <td class='text-center'>" . sprintf("%+d", (($this->kills >= 10 or $this->assists >= 10 ? '1' : '0') * Config::get("fantasy.tenPlusKA"))) . "</td>
                </tr>

                <tr>
                    <td>Total:</td>
                    <td></td>
                    <td class='text-center'>" . rtrim(rtrim(sprintf("%+f", $this->fantasyPoints()), "0"),".") . "</td>
                </tr>

            </table>
        </div>";

        return $html;
    }



}
