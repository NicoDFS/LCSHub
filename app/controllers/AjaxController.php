<?php

class AjaxController extends BaseController {


    public function getIndex()
    {

    }

    public function getRefresh()
    {
        $timezone = Config::get('cookie.timezoneDefault');

        if(Cookie::has(Config::get('cookie.timezone')))
        {
            $timezone = Cookie::get(Config::get('cookie.timezone'));
        }

        $datetime = new DateTime('now', new DateTimeZone($timezone));

        $query = "dateTime >= '" . $datetime->format('Y-m-d') . " 00:00:00' AND dateTime <= '" . $datetime->format('Y-m-d') . " 23:59:59'";
        $todayBlock = Block::whereRaw($query)->first();
        $todayBlock->currBlock = true;

        if(is_null($todayBlock))
        {
            $todayBlock = Block::where('dateTime', '<=',  $datetime->format('Y-m-d') . " 23:59:59")->orderBy('dateTime', 'desc')->get()[0];
            $todayBlock->currBlock = false;
        }

        $pageHeader = View::make('html.titlebar')->with('block', $todayBlock);
        $scheduleBlock = View::make('html.schedule')->with('block', $todayBlock);
        $streamContainer = View::make('html.stream')->with('block', $todayBlock);

        return json_encode( ['pageHeader' => $pageHeader->render(), 'scheduleBlock' => $scheduleBlock->render()] );
    }

    public function getMatch($id)
    {

        $block = Block::select('blocks.*')->join('matches', 'matches.blockId', '=', 'blocks.blockId')->where('matches.matchId', $id)->first();
        $block->requestedMatch($id);

        $pageHeader = View::make('html.titlebar')->with('block', $block);
        $scheduleBlock = View::make('html.schedule')->with('block', $block);
        $streamContainer = View::make('html.stream')->with('block', $block);

        return json_encode( ['pageHeader' => $pageHeader->render(), 'scheduleBlock' => $scheduleBlock->render(), 'streamContainer' => $streamContainer->render()] );
    }

    public function getRefreshmatch($id)
    {

        $block = Block::select('blocks.*')->join('matches', 'matches.blockId', '=', 'blocks.blockId')->where('matches.matchId', $id)->first();

        $pageHeader = View::make('html.titlebar')->with('block', $block);
        $scheduleBlock = View::make('html.schedule')->with('block', $block);
        $streamContainer = View::make('html.stream')->with('block', $block);

        return json_encode( ['pageHeader' => $pageHeader->render(), 'scheduleBlock' => $scheduleBlock->render(), 'streamContainer' => $streamContainer->render()] );
    }

    public function getVod($id)
    {
        $block = Block::select('blocks.*')->join('matches', 'matches.blockId', '=', 'blocks.blockId')->where('matches.matchId', $id)->first();
        $block->requestedMatch($id);

        $pageHeader = View::make('html.titlebar')->with('block', $block);
        $streamContainer = View::make('html.stream')->with('block', $block);

        return json_encode(['streamContainer' => $streamContainer->render(), 'pageHeader' => $pageHeader->render()]);
    }

    public function getBlock($id, $dir = null)
    {
        if($dir == null)
        {
            if($id == 'current')
            {
                $timezone = Config::get('cookie.timezoneDefault');

                if(Cookie::has(Config::get('cookie.timezone')))
                {
                    $timezone = Cookie::get(Config::get('cookie.timezone'));
                }

                $datetime = new DateTime('now', new DateTimeZone($timezone));

                $query = "dateTime >= '" . $datetime->format('Y-m-d') . " 00:00:00' AND dateTime <= '" . $datetime->format('Y-m-d') . " 23:59:59'";
                $todayBlock = Block::whereRaw($query)->first();
                $todayBlock->currBlock = true;

                if(is_null($todayBlock))
                {
                    $todayBlock = Block::where('dateTime', '<=',  $datetime->format('Y-m-d') . " 23:59:59")->orderBy('dateTime', 'desc')->get()[0];
                    $todayBlock->currBlock = false;
                }

                $scheduleBlock = View::make('html.schedule')->with('block', $todayBlock);

                return json_encode(['scheduleBlock' => $scheduleBlock->render()]);
            }
        }
        else
        {
            $prevBlock = Block::where('id', $id)->first();
            $operator = ($dir == 'next' ? '>' : '<');
            $order = ($dir == 'next' ? 'asc' : 'desc');

            $block = Block::where('dateTime', $operator, $prevBlock->dateTime)->orderBy('dateTime', $order)->first();

            $scheduleBlock = View::make('html.schedule')->with('block', $block);
            return json_encode(['scheduleBlock' => $scheduleBlock->render()]);
        }
    }

    public function getDetails($id)
    {
        $match = Match::where('id', $id)->first();
        $slideDown = View::make('html.game')->with('match', $match);

        return json_encode(['match' => $slideDown->render()]);
    }

    public function getLive($id)
    {
        return $this->getMatch($id);
    }

    public function postTimezone()
    {
        if(Input::has('timezone'))
        {
            if(!is_null(Input::get('timezone')))
            {
                Input::merge(array('timezone', Config::get('cookie.timezoneDefault')));
            }

            if (in_array(Input::get('timezone'), DateTimeZone::listIdentifiers()))
            {
                $foreverCookie = Cookie::forever(Config::get('cookie.timezone'), Input::get('timezone'));
                $content = json_encode([ 'timezone' => Input::get('timezone') ]);
                return Response::make($content)->withCookie($foreverCookie);
            }
        }
    }


}
