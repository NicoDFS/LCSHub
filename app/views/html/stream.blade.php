@if($block->isLiveMatch())
    <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->leagueYoutubeId() }}?autoplay=1&t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
@else
    <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/{{ $block->matches[0]->game()->youtubeId() }}?autoplay=1&t=100000000&vq=hd1080" frameborder="0" allowfullscreen></iframe>
@endif
