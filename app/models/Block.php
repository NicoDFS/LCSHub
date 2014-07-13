<?php

class Block extends Eloquent {


    public function matches()
    {
        return $this->hasMany('Match');
    }


}
