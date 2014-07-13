<?php

class Match extends Eloquent {


    public function block()
    {
        return $this->belongsTo('Block');
    }


}
