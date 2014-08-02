<li class="list-group-item" id="match-{{ $match->id }}-details" style="margin-left:15px; margin-right:15px;">
    <h2 class="text-center" style="font-size:21px;">Winner: {{ ($match->getGame()->winnerId == $match->getGame()->blueId ? $match->blueAcronym : $match->redAcronym) }} ({{ gmdate('i:s', $match->getGame()->gameLength) }}) <button type="button" class="btn btn-default"  id="refresh-match-{{ $match->id }}"  onclick="refreshDetail('{{ $match->id }}');" style="outline:none;"><i class="fa fa-refresh"></i></button></h3>
    @include('html.gamedetail', array('game' => $match->getGame()))
</li>
