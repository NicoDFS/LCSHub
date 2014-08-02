<?php $activeMatch = $block->activeMatch(); ?>

<h2 style="color: {{ ($activeMatch !== null ? $activeMatch->color() : '') }}" title="{{ ($activeMatch !== null ? $activeMatch->status() : '') }}" data-toggle="tooltip">

    @if($activeMatch !== null)
        <?php $block->sortPlaces(); ?>
       <img src="http://na.lolesports.com{{ $activeMatch->blueLogoURL }}" width='55' height='55' style="border-radius: 10%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:8px; {{ $activeMatch->winnerImg($activeMatch->blueId) }}">

        <div style="display: inline-table; width:90px; {{ $activeMatch->winner($activeMatch->blueId) }}">
            <span style="float:left;">{{ $activeMatch->blueAcronym }}</span>
            <br>
            <span style="font-size: 14px; float:left;">
            {{ $block->_places[$activeMatch->blueAcronym] }} ({{ $activeMatch->blueWins }}-{{ $activeMatch->blueLosses }})
            </span>
        </div><span>vs</span><div style="display: inline-table; text-align:right; width:90px; {{ $activeMatch->winner($activeMatch->redId) }}">
            <span style="float:right;">{{ $activeMatch->redAcronym }}</span>
            <br>
            <span style="font-size: 14px; float:right;">
                {{ $block->_places[$activeMatch->redAcronym] }} ({{ $activeMatch->redWins }}-{{ $activeMatch->redLosses }})
            </span>
        </div>

        <img src="http://na.lolesports.com{{ $activeMatch->redLogoURL }}" width='55' height='55' style="border-radius: 10%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:10px; {{ $activeMatch->winnerImg($activeMatch->redId) }}">
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

<ol class="breadcrumb" style="{{ ($activeMatch !== null ? 'margin-top:14px;' : '') }} margin-bottom:-14px;">
    <li><a href="#" onclick="return false;">{{ $block->getLeague()->shortName }}</a></li>
    <li><a href="#" onclick="return false;">{{ substr($block->tournamentName, 3) }}</a></li>
    <li><a href="#" onclick="return false;">{{ substr($block->label, strpos($block->label, " - ") + 3) }}</a></li>
    <li class="active">
        @if($activeMatch !== null)
            <a href="#" onclick="return false;">{{ $activeMatch->matchName }}</a>
        @elseif($activeMatch == null && $block->isCurrentBlock())
            @if($block->matchesFinished())
                Finished
            @else
                Scheduled
            @endif
        @else
            Finished
        @endif
    </li>
</ol>
