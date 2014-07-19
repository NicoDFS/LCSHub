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

        if(is_null($todayBlock))
        {
            $todayBlock = Block::where('dateTime', '<=',  $datetime->format('Y-m-d') . " 23:59:59")->orderBy('dateTime', 'desc')->get()[0];
        }

        $pageHeader = View::make('html.titlebar')->with('block', $todayBlock);
        $scheduleBlock = View::make('html.schedule')->with('block', $todayBlock);
        $streamContainer = View::make('html.stream')->with('block', $todayBlock);

        return json_encode( ['pageHeader' => $pageHeader->render(), 'scheduleBlock' => $scheduleBlock->render(), 'streamContainer' => $streamContainer->render()] );


    }


}
