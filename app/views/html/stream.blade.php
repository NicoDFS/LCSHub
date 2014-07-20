

@if($block->isCurrentBlock())
    <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->leagueYoutubeId() }}?autoplay=1&t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
@elseif(!$block->isFutureBlock())
    <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->matches[$block->gRequestedMatchIndex()]->game()->youtubeId() }}?autoplay=1&t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
@elseif($block->isFutureBlock())
    <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->leagueYoutubeId() }}?t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
@endif


