@if($block->isCurrentBlock())

    @if($block->isLiveMatch() && !isset($block->newMatchId))
        {{ $block->getVideoPlayer() }}
    @elseif(isset($block->newMatchId))

        @if(!isset($block->requestedGame))

            @if(!$block->isMatchLive($block->newMatchId))
                $block->_stream = 'youtube';
                <iframe width="1280" height="720" src="https://www.youtube.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGames()[0]->youtubeId() }}?autoplay=1&t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
            @else
                {{ $block->getVideoPlayer() }}
            @endif

        @else
            $block->_stream = 'youtube';
            <iframe width="1280" height="720" src="https://www.youtube.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGames()[$block->getRequestedGame()]->youtubeId() }}?autoplay=1&t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
        @endif

    @else
        {{ $block->getVideoPlayer() }}
    @endif

@elseif(!$block->isFutureBlock())

$block->_stream = 'youtube';

    @if(isset($block->newMatchId))

        @if(!isset($block->requestedGame))
            <iframe width="1280" height="720" src="https://www.youtube.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGames()[0]->youtubeId() }}?autoplay=1&t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
        @else
            <iframe width="1280" height="720" src="https://www.youtube.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGames()[$block->getRequestedGame()]->youtubeId() }}?autoplay=1&t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
        @endif

    @else
        <iframe width="1280" height="720" src="https://www.youtube.com/embed/{{ $block->getMatches()[0]->getGames()[0]->youtubeId() }}?autoplay=1&start=0&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
    @endif

@elseif($block->isFutureBlock())
    <iframe width="1280" height="720" src="https://www.youtube.com/embed/{{ $block->leagueYoutubeId() }}?t=100000000&vq=highres&autohide=1&rel=0&iv_load_policy=3&showinfo=0&theme=light&controls=2&color=white" frameborder="0" allowfullscreen></iframe>
@endif

