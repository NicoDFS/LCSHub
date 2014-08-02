<div class="block-flat" style="border: 1px solid #DDD; margin-bottom:-15px; padding-bottom:5px;">
    <div class="content">
        <h3 class="text-center" style="margin-top:-15px; padding-bottom:10px;">
        @if($block->previousBlocks())
            <i onclick="getBlock('{{ $block->id }}','prev')" class="fa  fa-angle-double-left" style="cursor: pointer; font-size: 26px;float: left;opacity: .9;" title="Previous" data-toggle='tooltip'></i>
        @endif

        @if(!$block->isCurrentBlock() && $block->isFutureBlock())
             <i onclick="getBlock('current')" class="fa fa-reply blueHover" style="float: left;margin-left: 25px;margin-top: 6px;font-size: 15px; cursor: pointer;" title="Jump to Current" data-toggle='tooltip'></i>
        @endif


        {{ $block->blockTournamentName() . " LCS" }} - {{ $block->blockLabelWeek() }} {{ $block->blockLabelDay() }} - {{ date('M j, Y', strtotime($block->dateTime)) }}

        @if($block->futureBlocks())
            <i onclick="getBlock('{{ $block->id }}','next')" class="fa  fa-angle-double-right" style="cursor: pointer; font-size: 26px;float: right;opacity: .9;" title="Next" data-toggle='tooltip'></i>
        @endif

        @if(!$block->isCurrentBlock() && !$block->isFutureBlock())
            <i onclick="getBlock('current')" class="fa fa-share blueHover" style="float: right;margin-right: 37px;margin-top: 6px;font-size: 15px; cursor: pointer;" title="Jump to Current" data-toggle='tooltip'></i>
        @endif

        </h3>
        <div class="list-group">
        <?php if(count($block->getMatches()) > 0) $block->sortPlaces(); ?>
            @foreach($block->getMatches() as $tempCntr => $match)
                <?php $tempZone = new DateTime($match->dateTime); $tempZone->setTimezone(new DateTimeZone($block->timezone())); ?>
                <li href="#" id="match-{{ $match->id }}" class="list-group-item match blueHover {{ $match->isLiveActive() }}" style="margin-bottom:0px; margin-top:0px; padding-top: 0px; height:76px; font-size: 20px; {{ ( (($tempCntr % 2 == 0) && ($match->status() !== "Live")) ? ' background: #F8F8F8; ' : '' ) }} {{ $match->isLiveText() }}">
                    <img src="http://na.lolesports.com{{ $match->blueLogoURL }}" width='55' height='55' style="border-radius: 10%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:8px; {{ $match->winnerImg($match->blueId) }}">

                    <div style="display: inline-table; margin-right:10px; width:80px; {{ $match->winner($match->blueId) }}">
                        {{ $match->blueAcronym }}
                        <br>
                        <span style="font-size: 14px; float:left;">
                        {{ $block->_places[$match->blueAcronym] }} ({{ $match->blueWins }}-{{ $match->blueLosses }})
                        </span>
                    </div>
                        vs

                    <div style="display: inline-table; text-align:right; margin-left:10px; width:90px; {{ $match->winner($match->redId) }}">
                        {{ $match->redAcronym }}
                        <br>
                        <span style="font-size: 14px; float:right; margin-right:5px;">
                            {{ $block->_places[$match->redAcronym] }} ({{ $match->redWins }}-{{ $match->redLosses }})
                        </span>
                    </div>

                    <img src="http://na.lolesports.com{{ $match->redLogoURL }}" width='55' height='55' style="border-radius: 10%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:10px; {{ $match->winnerImg($match->redId) }}">

                    <div class="btn-group pull-right" style="margin-top:15px;">
                        <button type="button" style="width:96px;" class="btn btn-{{ $match->cssClass() }} btn-lg" title="{{ $match->status() }}" data-toggle="tooltip" data-placement="left" data-container="body" @if($match->status() == 'Finished')
                                @if($match->getGame() !== null)
                                    @if($match->getGame()->fullPlayers())
                                        onclick="getMatchDetails('{{ $match->id }}');"
                                    @endif
                                @endif
                        @endif

                        >{{ $tempZone->format('g:i A') }}</button>
                        <button type="button" class="btn btn-{{ $match->cssClass() }} btn-lg dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            @if($match->status() == 'Finished')
                                @if($match->getGame() !== null)
                                    @if($match->getGame()->fullPlayers())
                                        <li><a href="#" id="match-{{ $match->id }}-button" onclick="getMatchDetails('{{ $match->id }}'); return false;">View game stats</a></li>
                                    @endif
                                    @if($match->getGame()->vodURL !== null)
                                    <li><a href="#" onclick="getVod('{{ $match->matchId }}', true); return false;">View VOD</a></li>
                                    @endif
                                    @if(($match->getGame()->vodURL !== null) && ($match->getGame()->fullPlayers()))
                                        <li><a href="#" onclick="getVodDetails('{{ $match->id }}', '{{ $match->matchId }}'); return false;" >View stats and VOD</a></li>
                                    @endif
                                @else
                                    <li><a href="#">Still Processing...</a></li>
                                @endif
                            @elseif($match->status() == 'Live')
                                <li><a href="#">View {{ $match->blueAcronym }}'s games</a></li>
                                <li><a href="#">View {{ $match->redAcronym }}'s games</a></li>
                            @elseif($match->status() == 'Scheduled')
                                <li><a href="#">View {{ $match->blueAcronym }}'s games</a></li>
                                <li><a href="#">View {{ $match->redAcronym }}'s games</a></li>
                                @if($block->tickets !== null)
                                    <li><a href="{{ $block->tickets }}">Buy tickets</a></li>
                                @endif
                            @endif
                        </ul>
                  </div>
                </li>
                @if($match->status() == 'Finished')

                @endif
            @endforeach

        </div>
    </div>
</div>

    <!--Add new hidden li under element, with margin-right/left of 10px and use jquery.slidetoggle on element to expand/collapse-->
