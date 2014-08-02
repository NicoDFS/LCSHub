<li class="list-group-item match-detail" matchid="{{ $match->id }}" id="match-{{ $match->id }}-details" style="margin-left:15px; margin-right:15px;">
    <h2 class="text-center" style="font-size:20px; margin-top:2px;"><button type="button" class="btn btn-default pull-left" style="margin-left:0px; margin-top:-6px; outline:none;" onclick="closeDetails('{{ $match->id }}');"><i class="fa  fa-times"></i> Close</button>
        Winner: {{ ($match->getGame()->winnerId == $match->getGame()->blueId ? $match->blueAcronym : $match->redAcronym) }} ({{ gmdate('i:s', $match->getGame()->gameLength) }})
        <button type="button" class="btn btn-default pull-right" style="margin-top:-6px; outline:none;" id="refresh-match-{{ $match->id }}"  onclick="refreshDetail('{{ $match->id }}');"><i class="fa fa-refresh"></i> Refresh</button>
    </h2>
    @include('html.gamedetail', array('game' => $match->getGame()))
</li>
