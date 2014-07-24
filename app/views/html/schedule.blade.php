<div class="block-flat" style="border: 1px solid #DDD;">
    <div class="content">
        <h3 class="text-center" style="margin-top:-15px; padding-bottom:10px;">{{ $block->putBackground($block->blockTournamentName() . " LCS", 'warning') }} {{ $block->putBackground($block->blockLabelWeek(), 'success') }} {{ $block->putBackground($block->blockLabelDay(), 'info') }} {{ $block->putBackground(date('M j, Y', strtotime($block->dateTime)), 'primary') }}</h3>
        <div class="list-group">
            @foreach($block->getMatches() as $tempCntr => $match)
                <?php $tempZone = new DateTime($match->dateTime); $tempZone->setTimezone(new DateTimeZone(Cookie::get('timezone'))); ?>
                <li href="#" class="list-group-item {{ $match->isLiveActive() }}" style="font-size: 20px; {{ ($tempCntr % 2 == 0 ? ' background: #F8F8F8; ' : '' ) }} {{ $match->isLiveText() }}">
                    <img src="http://na.lolesports.com{{ $match->blueLogoURL }}" width='45' height='45' style="border-radius: 10%; background: #1A1A1A; padding:5px;">
                    {{ $match->matchName }}
                    <img src="http://na.lolesports.com{{ $match->redLogoURL }}" width='45' height='45' style="border-radius: 10%; background: #1A1A1A; padding:5px;">
                    <div class="btn-group pull-right">
                        <button type="button" style="width:96px;" class="btn btn-{{ $match->cssClass() }} btn-lg" title="{{ $match->status() }}" data-toggle="tooltip" data-placement="left" data-container="body">{{ $tempZone->format('g:i A') }}</button>
                        <button type="button" class="btn btn-{{ $match->cssClass() }} btn-lg dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            @if($match->status() == 'Finished')
                                @if($match->getGame()->vodURL !== null)
                                    <li><a href="#">View VOD</a></li>
                                @endif
                                @if($match->getGame()->fullPlayers())
                                    <li><a href="#">View game stats</a></li>
                                @endif
                                @if(($match->getGame()->vodURL !== null) && ($match->getGame()->fullPlayers()))
                                    <li><a href="#">View stats and VOD</a></li>
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
            @endforeach
        </div>
    </div>
</div>

    <!--Add new hidden li under element, with margin-right/left of 10px and use jquery.slidetoggle on element to expand/collapse-->
