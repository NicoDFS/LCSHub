<li class="list-group-item match-detail" matchid="{{ $match->id }}" id="match-{{ $match->id }}-details" style="margin-left:15px; margin-right:15px; border-top:none;">
    <h2 class="text-center" style="font-size:20px; margin-top:2px;"><button type="button" class="btn btn-default pull-left" style="margin-left:0px; margin-top:-6px; outline:none;" onclick="closeDetails('{{ $match->id }}');"><i class="fa  fa-angle-double-up"></i> Hide game{{ (count($match->getGames()) > 1 ? 's' : '') }}</button>

        @if($match->winnerId !== null)
            Winner: {{ ($match->winnerId == $match->blueId ? $match->blueAcronym : $match->redAcronym) }}
        @else
            In Progress: {{ ($match->seriesWinner() == $match->blueId ? $match->blueAcronym : $match->redAcronym) }}
        @endif

        @if(count($match->getGames()) > 1) ({{ $match->seriesResult() }})
        @else &#40;{{ gmdate('G:i:s', $match->getGames()[0]->gameLength) }}&#41;
        @endif
        <button type="button" class="btn btn-default pull-right" style="margin-top:-6px; outline:none;" id="refresh-match-{{ $match->id }}"  onclick="refreshDetail('{{ $match->id }}');"><i class="fa fa-refresh"></i> Refresh</button>
    </h2>
    @include('html.gamedetail', array('match' => $match))
</li>