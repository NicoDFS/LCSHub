<?php

class AjaxController extends BaseController {


    public function getIndex()
    {

    }

    public function getRefresh()
    {
        $timezone = 'America/Los_Angeles';
        Cookie::queue('timezone', 'America/Los_Angeles', (60 * 24));

        if(Cookie::get('timezone'))
        {
            $timezone = Cookie::get('timezone');
        }

        $datetime = new DateTime('now', new DateTimeZone($timezone));

        $query = "dateTime >= '" . $datetime->format('Y-m-d') . " 00:00:00' AND dateTime <= '" . $datetime->format('Y-m-d') . " 23:59:59'";
        $todayBlock = Block::whereRaw($query)->first();

        if(!is_null($todayBlock))
        {
            return View::make('html.home')->with('block', $todayBlock);
        }

    }


}
