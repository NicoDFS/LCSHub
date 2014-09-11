<?php

class AjaxController extends BaseController {


    public function getIndex()
    {

    }

    public function getRefresh()
    {
        $todayBlock = Block::currentBlock();

        $pageHeader = View::make('html.titlebar')->with('block', $todayBlock);
        $scheduleBlock = View::make('html.schedule')->with('block', $todayBlock);
        $streamContainer = View::make('html.stream')->with('block', $todayBlock);

        return Response::json( ['pageHeader' => $pageHeader->render(), 'scheduleBlock' => $scheduleBlock->render()] );
    }

    public function getMatch($id)
    {

        $block = Block::select('blocks.*')->join('matches', 'matches.blockId', '=', 'blocks.blockId')->where('matches.matchId', $id)->first();
        $block->requestedMatch($id);

        $pageHeader = View::make('html.titlebar')->with('block', $block);
        $scheduleBlock = View::make('html.schedule')->with('block', $block);
        $streamContainer = View::make('html.stream')->with('block', $block);
        $chatContainer = View::make('html.chat')->with('block', $block);

        return  Response::json( ['pageHeader' => $pageHeader->render(), 'scheduleBlock' => $scheduleBlock->render(), 'streamContainer' => $streamContainer->render(), 'chatContainer' => $chatContainer->render()] );
    }

    public function getRefreshmatch($id)
    {

        $block = Block::select('blocks.*')->join('matches', 'matches.blockId', '=', 'blocks.blockId')->where('matches.matchId', $id)->first();

        $pageHeader = View::make('html.titlebar')->with('block', $block);
        $scheduleBlock = View::make('html.schedule')->with('block', $block);
        $streamContainer = View::make('html.stream')->with('block', $block);
        $chatContainer = View::make('html.chat')->with('block', $block);


        return Response::json( ['pageHeader' => $pageHeader->render(), 'scheduleBlock' => $scheduleBlock->render(), 'streamContainer' => $streamContainer->render(), 'chatContainer' => $chatContainer->render()] );
    }

    public function getGamevod($id)
    {
        $block = Block::select('blocks.*', 'matches.matchId')->join('matches', 'matches.blockId', '=', 'blocks.blockId')->join('games', 'games.matchId', '=', 'matches.matchId')->where('games.gameId', $id)->first();
        $block->requestedMatch($block->matchId);
        $block->requestedGame($id);

        $pageHeader = View::make('html.titlebar')->with('block', $block);
        $streamContainer = View::make('html.stream')->with('block', $block);
        $chatContainer = View::make('html.chat')->with('block', $block);

        return Response::json( ['streamContainer' => $streamContainer->render(), 'pageHeader' => $pageHeader->render(), 'chatContainer' => $chatContainer->render()]);
    }

    public function getVod($id)
    {
        $block = Block::select('blocks.*')->join('matches', 'matches.blockId', '=', 'blocks.blockId')->where('matches.matchId', $id)->first();
        $block->requestedMatch($id);

        $pageHeader = View::make('html.titlebar')->with('block', $block);
        $streamContainer = View::make('html.stream')->with('block', $block);
        $chatContainer = View::make('html.chat')->with('block', $block);

        return Response::json( ['streamContainer' => $streamContainer->render(), 'pageHeader' => $pageHeader->render(), 'chatContainer' => $chatContainer->render()]);
    }

    public function getBlock($id, $dir = null)
    {
        if($dir == null)
        {
            if($id == 'current')
            {

                $todayBlock = Block::currentBlock();

                $scheduleBlock = View::make('html.schedule')->with('block', $todayBlock);

                return Response::json( ['scheduleBlock' => $scheduleBlock->render()]);
            }
        }
        else
        {
            $prevBlock = Block::where('id', $id)->first();

            $operator = ($dir == 'next' ? '>' : '<');
            $order = ($dir == 'next' ? 'asc' : 'desc');

            $block = Block::where('dateTime', $operator, $prevBlock->dateTime)->orderBy('dateTime', $order)->first();

            $scheduleBlock = View::make('html.schedule')->with('block', $block);

            return Response::json(['scheduleBlock' => $scheduleBlock->render()]);
        }
    }

    public function getDetails($id)
    {
        $match = Match::where('id', $id)->first();

        $slideDown = View::make('html.game')->with('match', $match);

        return Response::json(['match' => $slideDown->render()]);
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

            if(in_array(Input::get('timezone'), DateTimeZone::listIdentifiers()))
            {
                $foreverCookie = Cookie::forever(Config::get('cookie.timezone'), Input::get('timezone'));

                return Response::json([ 'timezone' => Input::get('timezone') ])->withCookie($foreverCookie);
            }
        }
    }

    public function postSettings()
    {
        if(Input::has('timezone') && Input::has('spoilers') && Input::has('updates') && Input::has('player'))
        {
            if(!is_null(Input::get('timezone')))
            {
                if(in_array(Input::get('timezone'), DateTimeZone::listIdentifiers()))
                {
                    Cookie::queue(Config::get('cookie.timezone'), Input::get('timezone'), (60 * 24 * 360));
                }
            }

            if(!is_null(Input::has('spoilers')))
            {
                Cookie::queue(Config::get('cookie.spoilers'), Input::get('spoilers'), (60 * 24 * 360));
            }

            if(!is_null(Input::get('updates')))
            {
                Cookie::queue(Config::get('cookie.updates'), Input::get('updates'), (60 * 24 * 360));
            }

            if(!is_null(Input::get('player')))
            {
                Cookie::queue(Config::get('cookie.player'), Input::get('player'), (60 * 24 * 360));
            }

            if(Input::has('fantasyTeams') && !is_null(Input::get('fantasyTeams')))
            {
                $output = json_decode(Input::get('fantasyTeams'));

                if(isset($output[0]))
                {
                    if(is_null($output[0]))
                    {
                        unset($output[0]);
                    }
                }

                Cookie::queue(Config::get('cookie.fantasyTeams'), $output, (60 * 24 * 360));
            }

            return Response::json([ 'message' => 'success' ]);
        }
        else
        {
            return Response::json([ 'message' => 'failure']);
        }
    }


}
