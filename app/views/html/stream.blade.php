@if($block->isCurrentBlock())

    @if($block->isLiveMatch() && !isset($block->newMatchId))
        <object type="application/x-shockwave-flash" height="378" width="620" id="live_embed_player_flash" data="http://www.twitch.tv/widgets/live_embed_player.swf?channel={{ $block->twitchUsername() }}" bgcolor="#F6F6F6"><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="movie" value="http://www.twitch.tv/widgets/live_embed_player.swf" /><param name="flashvars" value="hostname=www.twitch.tv&channel={{ $block->twitchUsername() }}&auto_play=true&start_volume=100" /></object>
    @else

        @if(isset($block->newMatchId))

            @if(!isset($block->requestedGame))
                <iframe width="1280" height="720" src="//www.youtube.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGame()->youtubeId() }}?autoplay=1&t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
            @else
                <iframe width="1280" height="720" src="//www.youtube.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGames()[$block->getRequestedGame()]->youtubeId() }}?autoplay=1&t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
            @endif

        @else
            <object type="application/x-shockwave-flash" height="378" width="620" id="live_embed_player_flash" data="http://www.twitch.tv/widgets/live_embed_player.swf?channel={{ $block->twitchUsername() }}" bgcolor="#F6F6F6"><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="movie" value="http://www.twitch.tv/widgets/live_embed_player.swf" /><param name="flashvars" value="hostname=www.twitch.tv&channel={{ $block->twitchUsername() }}&auto_play=true&start_volume=100" /></object>
        @endif

    @endif

@elseif(!$block->isFutureBlock())

    @if(isset($block->newMatchId))

        @if(!isset($block->requestedGame))
            <iframe width="1280" height="720" src="//www.youtube.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGame()->youtubeId() }}?autoplay=1&t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
        @else
            <iframe width="1280" height="720" src="//www.youtube.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGames()[$block->getRequestedGame()]->youtubeId() }}?autoplay=1&t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
        @endif

    @else
         <iframe width="1280" height="720" src="//www.youtube.com/embed/{{ $block->getMatches()[0]->getGame()->youtubeId() }}?autoplay=1&start=0&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
    @endif

@elseif($block->isFutureBlock())
    <iframe width="1280" height="720" src="//www.youtube.com/embed/{{ $block->leagueYoutubeId() }}?t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
@endif

