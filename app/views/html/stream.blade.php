

@if($block->isCurrentBlock())
    @if($block->isLiveMatch())
        <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->leagueYoutubeId() }}?autoplay=1&t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
    @else
        @if(isset($block->newMatchId))
            <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGame()->youtubeId() }}?autoplay=1&t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
        @else
            <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->leagueYoutubeId() }}?autoplay=1&t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
        @endif
    @endif
@elseif(!$block->isFutureBlock())
    @if(isset($block->newMatchId))
        <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->getMatches()[$block->gRequestedMatchIndex()]->getGame()->youtubeId() }}?autoplay=1&t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
    @else
         <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->getMatches()[0]->getGame()->youtubeId() }}?autoplay=1&start=0&vq=hd1080" frameborder="0" allowfullscreen></iframe>
    @endif
@elseif($block->isFutureBlock())
    <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->leagueYoutubeId() }}?t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
@endif


