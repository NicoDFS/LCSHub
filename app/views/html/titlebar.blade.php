<h2>
    @if(($activeMatch = $block->activeMatch()) !== null)
        <img src="http://na.lolesports.com{{ $activeMatch->blueLogoURL }}" width='50' height='50' style="border-radius: 10%; background: #1A1A1A; padding:5px;">
        <span data-toggle="tooltip" data-placement="top" data-viewport="#pageHeader" title="{{ $activeMatch->status() }}" style="color:{{ $activeMatch->color() }};">{{ $activeMatch->matchName }}</span>
        <img src="http://na.lolesports.com{{ $activeMatch->redLogoURL }}" width='50' height='50' style="border-radius: 10%; background: #1A1A1A; padding:5px;">
    @elseif($activeMatch == null && $block->isCurrentBlock())
        @if($block->matchesFinished())
            No More Live Games
        @else
            Live Games in {{ $block->timeFuture($block->dateTime) }} ({{ $block->lcsTime() }})
        @endif
    @else
        No More Live Games
    @endif
</h2>

<ol class="breadcrumb">
    <li><a href="#">{{ $block->getLeague()->shortName }}</a></li>
    <li><a href="#">{{ $block->tournamentName }}</a></li>
    <li><a href="#">{{ substr($block->label, strpos($block->label, " - ") + 3) }}</a></li>
    <li class="active">
        @if($activeMatch !== null)
            <a href="#">{{ $activeMatch->matchName }}</a>
        @elseif($activeMatch == null && $block->isCurrentBlock())
            @if($block->matchesFinished())
                Finished
            @else
                Soon
            @endif
        @else
            Finished
        @endif
    </li>
</ol>
