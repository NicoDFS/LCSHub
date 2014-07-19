<h2>
    @if(($activeMatch = $block->activeMatch()) !== null)
        <img src="http://na.lolesports.com{{ $activeMatch->blueLogoURL }}" width='50' height='50'>
        {{ $activeMatch->matchName }}
        <img src="http://na.lolesports.com{{ $activeMatch->redLogoURL }}" width='50' height='50'>
    @else
        No Live Games
    @endif
</h2>

<ol class="breadcrumb">
    <li><a href="#">{{ $block->getLeague()->shortName }}</a></li>
    <li><a href="#">{{ $block->tournamentName }}</a></li>
    <li><a href="#">{{ substr($block->label, strpos($block->label, " - ") + 3) }}</a></li>
    <li class="active">
        @if($activeMatch !== null)
            <a href="#">{{ $activeMatch->matchName }}</a>
        @else
            None
        @endif
    </li>
</ol>
