<?php $block->sortPlaces(); ?>

@foreach($block->getMatches() as $tempCntr => $match)

    <?php $tempZone = new DateTime($match->dateTime); $tempZone->setTimezone(new DateTimeZone($block->timezone())); ?>

    <li id="match-{{ $match->id }}" class="list-group-item match blueHover" style="border-left: 5px solid {{ $match->color() }}; padding-left: 11px; margin-bottom:0px; margin-top:0px; padding-top: 0px; height:76px; font-size: 20px; {{ ( (($tempCntr % 2 == 0) && ($match->status() !== "Live")) ? ' background: #F8F8F8; ' : '' ) }}">

    @if(in_array($block->tournamentId, Config::get('standings.approvedTournaments')))

        <img src="{{ ($match->blueLogoURL !== null ? 'http://na.lolesports.com' . $match->blueLogoURL : 'https://s3-us-west-1.amazonaws.com/riot-api/img/riot-fist-inverted.png') }}" width='55' height='55' style="border-radius: 5%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:8px; {{ $match->winnerImg($match->blueId) }}">

        @if($match->winnerId == $match->blueId)
            <span class="label label-danger" style=" font-size: 10px; position: absolute; left: 45px; top: 46px; border-bottom: 3px solid rgb(96, 192, 96); border-right: 3px solid rgb(96, 192, 96);">{{ $block->_places[$match->blueAcronym] }}</span>
        @else
            <span class="label label-danger" style=" font-size: 10px; position: absolute; left: 48px; top: 49px;">{{ $block->_places[$match->blueAcronym] }}</span>
        @endif

        <div style="display: inline-table; margin-right:10px; width:95px; {{ $match->winner($match->blueId) }}">

            {{ ($match->blueAcronym !== null ? $match->blueAcronym : 'TBD') }}

            <br> <span style="font-size: 14px; float:left;">

                @if($match->blueId !== null)
                    ({{ $match->blueWins }}-{{ $match->blueLosses }})
                @else
                    N/A
                @endif

            </span>

        </div>

        <div style="display: inline-table;">

            <span style="float:left; line-height: 143%; font-size:24px;">vs</span> <br>

            @if($match->maxGames > 1)

                @if($match->status() == 'Live')
                    <span class="label label-primary" style=" font-size: 10px; position: absolute; left: 184px; top: 53px; ">Best of {{ $match->maxGames }}</span>
                    <span class="label label-danger" style=" font-size: 10px; position: absolute; left: 126px; top: 53px; ">Game {{ count($match->getGames()) }}</span>
                @elseif($match->status() == 'Finished')
                    <span class="label label-primary" style=" font-size: 10px; position: absolute; left: 156px; top: 53px; ">Best of {{ $match->maxGames }}</span>

                    @if($match->winnerId == $match->blueId)
                        <span class="label label-success" style=" font-size: 10px; position: absolute; left: 73px; top: 53px; ">{{ $match->seriesResult() }}</span>
                    @elseif($match->winnerId == $match->redId)
                        <span class="label label-success" style=" font-size: 10px; position: absolute; left: 257px; top: 53px; ">{{ $match->seriesResult() }}</span>
                    @endif

                @elseif($match->status() == 'Scheduled')
                    <span class="label label-primary" style=" font-size: 10px; position: absolute; left: 156px; top: 53px; ">Best of {{ $match->maxGames }}</span>
                @endif

            @endif

        </div>

        <div style="display: inline-table; text-align:right; margin-left:10px; width:95px; {{ $match->winner($match->redId) }}">

            {{ ($match->redAcronym !== null ? $match->redAcronym : 'TBD') }}

            <br> <span style="font-size: 14px; float:right; margin-right:5px;">

                @if($match->redId !== null)
                    ({{ $match->redWins }}-{{ $match->redLosses }})
                @else
                    N/A
                @endif

            </span>

        </div>

        <img src="{{ ($match->redLogoURL !== null ? 'http://na.lolesports.com' . $match->redLogoURL : 'https://s3-us-west-1.amazonaws.com/riot-api/img/riot-fist-inverted.png') }}" width='55' height='55' style="border-radius: 5%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:10px; {{ $match->winnerImg($match->redId) }}">

        @if($match->winnerId == $match->redId)
            <span class="label label-danger" style=" font-size: 10px; position: absolute; left: 318px; top: 47px; border-bottom: 3px solid rgb(96, 192, 96); border-left: 3px solid rgb(96, 192, 96);">{{ $block->_places[$match->redAcronym] }}</span>
        @else
            <span class="label label-danger" style=" font-size: 10px; position: absolute; left: 318px; top: 50px;">{{ $block->_places[$match->redAcronym] }}</span>
        @endif

    @else

        <img src="{{ ($match->blueLogoURL !== null ? 'http://na.lolesports.com' . $match->blueLogoURL : 'https://s3-us-west-1.amazonaws.com/riot-api/img/riot-fist-inverted.png') }}" width='55' height='55' style="border-radius: 5%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:9px; {{ $match->winnerImg($match->blueId) }}">

        <div style="display: inline-table; width:95px; {{ $match->winner($match->blueId) }}">

            <span style="float:left; line-height: 143%; font-size:27px;">{{ ($match->blueAcronym !== null ? $match->blueAcronym : 'TBD') }}</span> <br/><br/>

        </div>

        <div style="display: inline-table;  ">

            <span style="float:left; line-height: 143%; font-size:24px;">vs</span> <br>

            @if($match->maxGames > 1)

                @if($match->status() == 'Live')

                    @if(count($match->getGames()) > 1)
                        @if($match->seriesWinner() == $match->blueId)
                            <span class="label label-success" style=" font-size: 10px; position: absolute; left: 73px; top: 53px; ">{{ $match->seriesResult() }}</span>
                        @elseif($match->seriesWinner() == $match->redId)
                            <span class="label label-success" style=" font-size: 10px; position: absolute; left: 258px; top: 53px; ">{{ $match->seriesResult() }}</span>
                        @endif
                    @endif

                    <span class="label label-primary" style=" font-size: 10px; position: absolute; left: 185px; top: 53px; ">Best of {{ $match->maxGames }}</span>
                    <span class="label label-danger" style=" font-size: 10px; position: absolute; left: 127px; top: 53px; ">Game {{ $match->liveGameCount() }}</span>
                @elseif($match->status() == 'Finished')
                    <span class="label label-primary" style=" font-size: 10px; position: absolute; left: 156px; top: 53px; ">Best of {{ $match->maxGames }}</span>

                    @if($match->winnerId == $match->blueId)
                        <span class="label label-success" style=" font-size: 10px; position: absolute; left: 73px; top: 53px; ">{{ $match->seriesResult() }}</span>
                    @elseif($match->winnerId == $match->redId)
                        <span class="label label-success" style=" font-size: 10px; position: absolute; left: 257px; top: 53px; ">{{ $match->seriesResult() }}</span>
                    @endif

                @elseif($match->status() == 'Scheduled')
                    <span class="label label-primary" style=" font-size: 10px; position: absolute; left: 156px; top: 53px; ">Best of {{ $match->maxGames }}</span>
                @endif

            @endif

        </div>

        <div style="display: inline-table; text-align:right; width:95px; {{ $match->winner($match->redId) }}">

            <span style="float:right; line-height: 143%; font-size:27px;">{{ ($match->redAcronym !== null ? $match->redAcronym : 'TBD') }}</span> <br/><br/>

        </div>

        <img src="{{ ($match->redLogoURL !== null ? 'http://na.lolesports.com' . $match->redLogoURL : 'https://s3-us-west-1.amazonaws.com/riot-api/img/riot-fist-inverted.png') }}" width='55' height='55' style="border-radius: 5%; background: #1A1A1A; padding:5px; margin-bottom:-5px; margin-top:9px;  {{ $match->winnerImg($match->redId) }}">

    @endif

        <div class="btn-group pull-right" style="margin-top:15px;">
            <button type="button" style="width:96px;" class="btn btn-{{ $match->cssClass() }} btn-lg" title="{{ $match->status() }}" data-toggle="tooltip" data-placement="left" data-container="body"
                @if($match->status() == 'Finished')
                    @if(count($match->getGames()) > 0)
                        @if($match->getGames()[0]->fullPlayers())
                            onclick="getMatchDetails('{{ $match->id }}');"
                        @endif
                    @endif
                @endif

                @if($match->status() == 'Live')
                    onclick="getMatchDetails('{{ $match->id }}');"
                @endif

            >{{ $tempZone->format('g:i A') }}</button>
            <button type="button" class="btn btn-{{ $match->cssClass() }} btn-lg dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">
                @if($match->status() == 'Finished')
                    @if(count($match->getGames()) > 1)
                        @if($match->getGames()[0]->fullPlayers())
                            <li><a href="#" id="match-{{ $match->id }}-button" onclick="getMatchDetails('{{ $match->id }}'); return false;">View match result</a></li>
                        @endif

                        @foreach($match->getGames() as $game)
                            <li><a href="#" onclick="getGameVod('{{ $game->gameId }}'); return false;">Watch Game {{ $game->gameNumber }}</a></li>
                        @endforeach

                    @elseif(count($match->getGames()) == 1)

                        @if($match->getGames()[0]->vodURL !== null)
                            <li><a href="#" onclick="getVod('{{ $match->matchId }}', true); return false;">View VOD</a></li>
                        @endif
                        @if($match->getGames()[0]->fullPlayers())
                            <li><a href="#" id="match-{{ $match->id }}-button" onclick="getMatchDetails('{{ $match->id }}'); return false;">View match result</a></li>
                        @endif
                        @if(($match->getGames()[0]->vodURL !== null) && ($match->getGames()[0]->fullPlayers()))
                            <li><a href="#" onclick="getVodDetails('{{ $match->id }}', '{{ $match->matchId }}'); return false;" >View match and VOD</a></li>
                        @endif

                    @else
                        <li><a href="#">Still Processing...</a></li>
                    @endif
                @elseif($match->status() == 'Live')

                    @if(count($match->getGames()) > 0)

                        @if($match->getGames()[0]->fullPlayers())
                            <li><a href="#" id="match-{{ $match->id }}-button" onclick="getMatchDetails('{{ $match->id }}'); return false;">View match result</a></li>
                        @endif

                    @endif

                    <li><a href="#" onclick="getLiveGame('{{ $match->matchId }}'); return false;">View LIVE game</a></li>
                    <li><a href="#" onclick="return false;">View {{ $match->blueAcronym }}'s games</a></li>
                    <li><a href="#" onclick="return false;">View {{ $match->redAcronym }}'s games</a></li>
                @elseif($match->status() == 'Scheduled')
                    <li><a href="#" onclick="return false;">View {{ $match->blueAcronym }}'s games</a></li>
                    <li><a href="#" onclick="return false;">View {{ $match->redAcronym }}'s games</a></li>
                    @if($block->tickets !== null)
                        <li><a href="{{ $block->tickets }}" target="_blank">Buy tickets</a></li>
                    @endif
                @endif
            </ul>
      </div>
    </li>

@endforeach

@if(count($block->getMatches()) == 0)

<?php $tempZone = new DateTime($block->bodyTime); $tempZone->setTimezone(new DateTimeZone($block->timezone())); ?>

<style>
.noMatches .show-compact-schedule
{
    display: none;
}

.noMatches .show-schedule
{
    display:inline;
}

.noMatches center
{
    text-align: left;
    display: inline;
}

.noMatches a
{
    pointer-events: none;
    cursor: default;
}

.noMatches p
{
    display:none;
}

.noMatches img
{
    padding-top:10px;
    padding-bottom:10px;
}
</style>

<script type="text/javascript">
$(document).ready(function()
{
    $(".noMatches img").each(function() {

        $(this).attr('src', 'http://na.lolesports.com' + $(this).attr('src'));

    });

    $(".noMatches a").each(function() {

        $(this).attr('href', 'http://na.lolesports.com' + $(this).attr('href'));

    });
});
</script>

<li class="list-group-item noMatches blueHover" style='border-left: 5px solid; padding:0; padding-left:11px; background: #F8F8F8; border-left: 5px solid {{ $block->color() }};'>

    {{ $block->body }}

    <div style='display:inline;height: 116px;padding-top: 10px;padding-right: 11px;float: right;'>
        <button type="button" class="btn btn-{{ $block->colorClass() }} btn-lg" style="height: 116px; margin-bottom: 0px !important;" data-toggle="tooltip" data-placement="left" data-container="body" title="{{ $block->status() }}">
        {{ $tempZone->format('g:i A') }}
        </button>
    </div>

</li>

@endif
