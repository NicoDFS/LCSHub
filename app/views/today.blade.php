<!doctype html>
<html>

    <head>

        <meta charset="utf-8">

        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript">
            function correctStreamSizeCheck()
            {

                var totalWidth = $(window).width() - 300;
                var playerWidth = totalWidth - 340;
                var playerHeight = Math.floor(playerWidth * 0.5625);
                var chatHeight = playerHeight;

                //if(playerHeight >= 400)
                //{
                    $('#playerContainer').css({width: playerWidth, height: playerHeight});
                    $('#player').css({width: playerWidth, height: playerHeight});
                    $('#chatContainer').css({height: chatHeight});

                //}
                //else
                //{
                //    var totalWidth = 950;
                //    var playerWidth = 640;
                //    var playerHeight = 400;
                //    var chatHeight = 400;
                //
                //    $('#playerContainer').css({width: playerWidth, height: playerHeight});
                //    $('#player').css({width: playerWidth, height: playerHeight});
                //    $('#chatContainer').css({height: chatHeight});
                //
                //}

            }

            function setPreScroll()
            {

            }

            $(window).resize(function()
            {
                correctStreamSizeCheck();
            });
        </script>

    </head>

    <body>

        <div class="container-fluid">

            <div class="row" style="border-bottom:solid #000000; padding-left: 20px;">
                <div class="col-md-3" style="border-right:solid #000000; height:56px;">
                    <h2 class="text-center" style="margin:auto">lcshub.tv</h2>
                </div>

                <div class="col-md-9">
                    <h3 class='text-center'>Live:
                    @foreach($block->matches as $match)
                        @if($match->isLive)
                            {{ $match->matchName }}
                        @endif
                    @endforeach
                    </h3>
                </div>
            </div>

            <div class="row" style="margin-top:-20px; padding-left: 20px; padding-right: 20px;">

                <div class="col-md-3" style="margin-right:-2px; padding:15px;" id="leftScroll">
                    <h3 class="text-center">Master Baiterz</h3>

                    <ul class="list-group">
                        <li class="list-group-item">Top: Dyrus</li>
                        <li class="list-group-item">Jungle: Dexter</li>
                        <li class="list-group-item">Mid: Hai</li>
                        <li class="list-group-item">AD: Rekkles</li>
                        <li class="list-group-item">Support: Gleeb</li>
                        <li class="list-group-item">Flex: Doublelift</li>
                        <li class="list-group-item">Team: LMQ</li>
                    </ul>
                </div>

                <div class="col-md-9" style="border-left:solid black; padding-top:15px;" id="rightScroll"  data-spy="affix">
                    <div id="playerContainer" style="display: inline;">
                        <object id="player" type="application/x-shockwave-flash"
                                height="100%"
                                width="100%"
                                data="http://www.twitch.tv/widgets/live_embed_player.swf?channel=riotgames"
                                bgcolor="#000000">
                          <param  name="allowFullScreen"
                                  value="true" />
                          <param  name="allowScriptAccess"
                                  value="always" />
                          <param  name="allowNetworking"
                                  value="all" />
                          <param  name="movie"
                                  value="http://www.twitch.tv/widgets/live_embed_player.swf" />
                          <param  name="flashvars"
                                  value="hostname=www.twitch.tv&channel=riotgames&auto_play=true&start_volume=25" />
                        </object>
                    </div>

                    <div id="chatContainer" style="display: inline-block;">
                        <iframe id="chat" frameborder="0"
                                scrolling="no"
                                src="http://twitch.tv/chat/embed?channel=riotgames&amp;popout_chat=true"
                                height="100%"
                                width="239">
                        </iframe>
                    </div>

                    <div class='text-center'>
                        <h3>{{ $block->label }}</h3>
                    </div>

                    <ul class="list-group">

                    @foreach($block->matches as $match)
                        <li class="list-group-item">

                            @if($match->isFinished)

                                @if($match->blueId == $match->winnerId)
                                    <span style="color:green">{{ $match->blueName }}</span> vs {{ $match->redName }}
                                @endif

                                @if($match->redId == $match->winnerId)
                                    {{ $match->blueName }} vs <span style="color:green">{{ $match->redName }}</span>
                                @endif

                            @else
                                {{ $match->blueName }} vs {{ $match->redName }}
                            @endif

                            @if ($match->isLive && !$match->isFinished)
                                <span class='pull-right'>Live</span>
                            @endif

                            @if(!$match->isLive && !$match->isFinished)
                                <span class='pull-right'>Scheduled</span>
                            @endif

                            @if(!$match->isLive && $match->isFinished)
                                <a href='/match/{{ $match->matchId }}'><span class='pull-right'>Finished</span></a>
                            @endif

                        </li>
                    @endforeach

                    </ul>
                </div>

            </div>

        </div>

    </body>

    <script type="text/javascript">
        correctStreamSizeCheck();
        $(function()
        {
            correctStreamSizeCheck();
        });
    </script>

</html>
