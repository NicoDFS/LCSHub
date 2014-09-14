<div style="margin: auto; position: relative; width: 370px; margin: auto; position: relative; width: 375px; padding-bottom: 13px; background-color: rgb(245, 245, 245); border: 1px solid {{ $activeMatch->color() }}; box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05); margin-bottom:-2px; margin-top: -8px;">
@if(in_array($block->tournamentId, Config::get('standings.approvedTournaments')))

    <?php $block->sortPlaces() ?>

    <img src="http://na.lolesports.com{{ $activeMatch->blueLogoURL }}" width='55' height='55' style="border-radius: 5%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:8px; {{ $activeMatch->winnerImg($activeMatch->blueId) }}">

    <div style="display: inline-table; width:90px; {{ $activeMatch->winner($activeMatch->blueId) }}">

        <span style="float:left;">{{ $activeMatch->blueAcronym }}</span> <br/>

        <span style="font-size: 14px; float:left;">
            {{ $block->_places[$activeMatch->blueAcronym] }} ({{ $activeMatch->blueWins }}-{{ $activeMatch->blueLosses }})
        </span>

    </div>

    <span style="color: {{ ($activeMatch !== null ? $activeMatch->color() : '') }}" title="{{ ($activeMatch !== null ? $activeMatch->status() : '') }}" data-toggle="tooltip">vs</span>

    <div style="display: inline-table; text-align:right; width:90px; {{ $activeMatch->winner($activeMatch->redId) }}">

        <span style="float:right;">{{ $activeMatch->redAcronym }}</span> <br/>

        <span style="font-size: 14px; float:right;">
            {{ $block->_places[$activeMatch->redAcronym] }} ({{ $activeMatch->redWins }}-{{ $activeMatch->redLosses }})
        </span>

    </div>

    <img src="http://na.lolesports.com{{ $activeMatch->redLogoURL }}" width='55' height='55' style="border-radius: 5%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:10px; {{ $activeMatch->winnerImg($activeMatch->redId) }}">

@else

    <img src="{{ ($activeMatch->blueLogoURL !== null ? 'http://na.lolesports.com' . $activeMatch->blueLogoURL : 'https://s3-us-west-1.amazonaws.com/riot-api/img/riot-fist-inverted.png') }}" width='55' height='55' style="border-radius: 5%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:9px; {{ $activeMatch->winnerImg($activeMatch->blueId) }}">

    <div style="display: inline-table; width:95px; {{ $activeMatch->winner($activeMatch->blueId) }}">

        <span style="float:left; line-height: 143%; font-size:27px;">{{ ($activeMatch->blueAcronym !== null ? $activeMatch->blueAcronym : 'TBD') }}</span> <br/>

    </div>

    <div style="display: inline-table;  ">

        <span style="float:left; line-height: 143%; font-size:24px;">vs</span> <br>

        @if($activeMatch->maxGames > 1)

            @if($activeMatch->status() == 'Live')

                @if(count($activeMatch->getGames()) > 1)
                    @if($activeMatch->seriesWinner() == $activeMatch->blueId)
                        <span class="label label-success" style=" font-size: 10px; position: absolute; left: 73px; top: 48px; ">{{ $activeMatch->seriesResult() }}</span>
                    @elseif($activeMatch->seriesWinner() == $activeMatch->redId)
                        <span class="label label-success" style=" font-size: 10px; position: absolute; left: 263px; top: 48px; ">{{ $activeMatch->seriesResult() }}</span>
                    @endif
                @endif

                <span class="label label-primary" style=" font-size: 10px; position: absolute; left: 185px; top: 48px; ">Best of {{ $activeMatch->maxGames }}</span>
                <span class="label label-danger" style=" font-size: 10px; position: absolute; left: 127px; top: 48px; ">Game {{ $activeMatch->liveGameCount() }}</span>
            @elseif($activeMatch->status() == 'Finished')
                <span class="label label-primary" style=" font-size: 10px; position: absolute; left: 159px; top: 48px; ">Best of {{ $activeMatch->maxGames }}</span>

                @if($activeMatch->winnerId == $activeMatch->blueId)
                    <span class="label label-success" style=" font-size: 10px; position: absolute; left: 73px; top: 48px; ">{{ $activeMatch->seriesResult() }}</span>
                @elseif($activeMatch->winnerId == $activeMatch->redId)
                    <span class="label label-success" style=" font-size: 10px; position: absolute; left: 263px; top: 48px; ">{{ $activeMatch->seriesResult() }}</span>
                @endif

            @elseif($activeMatch->status() == 'Scheduled')
                <span class="label label-primary" style=" font-size: 10px; position: absolute; left: 159px; top: 48px; ">Best of {{ $activeMatch->maxGames }}</span>
            @endif

        @endif

    </div>

    <div style="display: inline-table; text-align:right; width:95px; {{ $activeMatch->winner($activeMatch->redId) }}">

        <span style="float:right; line-height: 143%; font-size:27px;">{{ ($activeMatch->redAcronym !== null ? $activeMatch->redAcronym : 'TBD') }}</span> <br/>



    </div>

    <img src="{{ ($activeMatch->redLogoURL !== null ? 'http://na.lolesports.com' . $activeMatch->redLogoURL : 'https://s3-us-west-1.amazonaws.com/riot-api/img/riot-fist-inverted.png') }}" width='55' height='55' style="border-radius: 5%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:9px;  {{ $activeMatch->winnerImg($activeMatch->redId) }}">

@endif
</div>
