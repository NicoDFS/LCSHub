<li class="list-group-item match-detail" matchid="{{ $match->id }}" id="match-{{ $match->id }}-details" style="margin-left:15px; margin-right:15px; border-top:none;">
    <h2 class="text-center" style="font-size:20px; margin-top:2px;"><button type="button" class="btn btn-default pull-left btn-flat" style="margin-left:0px; margin-top:-6px; outline:none;" onclick="closeDetails('{{ $match->id }}');"><i class="fa  fa-angle-double-up"></i> Hide game{{ (count($match->getGames()) > 1 ? 's' : '') }}</button>

        @if($match->winnerId !== null)
            <span class='label label-success' style='font-size:16px;'>{{ ($match->winnerId == $match->blueId ? $match->blueAcronym : $match->redAcronym) }}</span>
        @else


            @if($match->seriesWinner() == -1)
                <span class='label label-danger' style='font-size:16px;'>In Progress: Tied</span>
            @elseif($match->seriesWinner() == $match->blueId)
                <span class='label label-danger' style='font-size:16px;'>In Progress: {{ $match->blueAcronym }}</span>
            @elseif($match->seriesWinner() == $match->redId)
                <span class='label label-danger' style='font-size:16px;'>In Progress: {{ $match->redAcronym }}</span>
            @elseif($match->seriesWinner() == -2)
                <span class='label label-danger' style='font-size:16px;'>In Progress</span>
            @endif

        @endif

        @if(count($match->getGames()) > 1)
            <span class='label label-primary' style='font-size:16px;'>{{ $match->seriesResult(true) }}</span>
        @elseif(count($match->getGames()) == 1 && $match->maxGames == 1 && $match->isFinished == 1)
            <span class='label label-primary' style='font-size:16px;'>{{ ($match->getGames()[0]->gameLength/60) > 59  ? gmdate('G:i:s', $match->getGames()[0]->gameLength) : gmdate('i:s', $match->getGames()[0]->gameLength)  }}</span>
            @if($match->getGames()[0]->vodType != null)
               <span class='label label-danger' style='font-size:16px;'><span data-container='body' onclick="getGameVod('{{ $match->getGames()[0]->gameId }}');" style="cursor: pointer;" data-toggle="tooltip" data-placement="right" title="Watch Game"><i id='game-{{ $match->getGames()[0]->gameId }}-play' class="fa fa-youtube-play"></i></span></span>
            @endif
        @endif

        <button type="button" class="btn btn-default btn-flat pull-right" style="margin-top:-6px; outline:none;" id="refresh-match-{{ $match->id }}"  onclick="refreshDetail('{{ $match->id }}');"><i class="fa fa-refresh"></i> Refresh</button>
    </h2>
    @include('html.gamedetail', array('match' => $match))
</li>
