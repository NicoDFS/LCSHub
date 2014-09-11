<?php

class League extends Eloquent {

    protected $table = 'leagues';

    public function formatShortName()
    {
        if($this->shortName == "eu-cs")
        return "EU CS";

        if($this->shortName == "na-cs")
        return "NA CS";

        return $this->shortName;
    }

}
