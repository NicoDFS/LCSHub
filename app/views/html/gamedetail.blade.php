<?php $tblCount = 0; ?>
@foreach($game->teams() as $teamKey => $team)
<div class="table-responsive" style="{{ ($game->winnerId == $teamKey ? 'border:2px solid #60C060;' : '') }} {{ ($tblCount == 0 ? 'margin-bottom:20px' : '') }}">
<table style="" class="hover">
        <thead>
                <tr>
                        <th>Team: {{ ($teamKey == $game->blueId ? $game->blueName : $game->redName) }}</th>
                        <th class="text-center" style="border-left: none;"></th>
                        <th class="text-center" style="border-left: none;"></th>
                        <th class="text-center" style="border-left: none;"></th>
                        <th class="text-center" style="border-left: none;"></th>
                        <th class="text-center" style="border-left: none;"></th>
                        <th class="text-center" style="border-left: none;"></th>
                        <th class="text-center" style="border-left: none;"></th>
                </tr>
                <tr>
                        <th>Player</th>
                        <th class="text-center">Champ</th>
                        <th class="text-center">KDA</th>
                        <th class="text-center">Gold</th>
                        <th class="text-center">CS</th>
                        <th class="text-center">FPoints</th>
                        <th class="text-center">Spells</th>
                        <th class="text-center">Items</th>
                </tr>
        </thead>
        <tbody>
        @foreach($team as $player)
                <tr>
                        <td> <img class="img-rounded" style="border:1px solid black; width:70px; height:50px;" src="{{ $player->photoURL }}"> <span data-toggle="tooltip" title="Position: {{ $player->getFantasyPlayer()->role }}"> &nbsp;{{ $player->name }}</span></td>
                        <td class="text-center"> <img class="img-rounded" style="border:1px solid black;" src="http://lkimg.zamimg.com/shared/riot/images/champions/{{ $player->championId }}_32.png"> &nbsp;({{ $player->endLevel }})</td>
                        <td class="text-center"><span data-toggle="tooltip" title="{{ floatval((double)$player->kda) }}">{{ $player->kills }}/{{ $player->deaths }}/{{ $player->assists }}</span></td>
                        <td class="text-center">{{ GamePlayer::count_format($player->totalGold) }}</td>
                        <td class="text-center">{{ $player->minionsKilled }}</td>
                        <td class="text-center" style="font-weight:bold;">{{ $player->getFantasyPlayer()->fantasyPoints() }}</td>
                        <td class="text-center"> <img src="http://lkimg.zamimg.com/images/spells/{{ $player->spell0Id }}.png" style="width:32px; height:32px; border:1px solid black;" class="img-rounded"> <img src="http://lkimg.zamimg.com/images/spells/{{ $player->spell1Id }}.png" style="width:32px; height:32px; border:1px solid black;" class="img-rounded"> </td>
                        <td class="text-left">
                        @foreach($player->items() as $item)
                            <img src="http://lkimg.zamimg.com/shared/riot/images/items/{{ $item }}_32.png" style="width:32px; height:32px; border:1px solid black;" class="img-rounded">
                        @endforeach
                        </td>

                </tr>
        @endforeach
        </tbody>

</table>
</div>

<div class="table-responsive" style="{{ ($tblCount == 0 ? 'margin-top: -10px; margin-bottom: 35px;' : ' margin-top:10px; margin-bottom:10px;') }} {{ ($game->winnerId == $teamKey ? 'border:2px solid #60C060;' : '') }}">
<table style="" class="hover">

    <thead>

        <tr>

            <th>Team</th>
            <th class="text-center">Win</th>
            <th class="text-center">Barons</th>
            <th class="text-center">Dragons</th>
            <th class="text-center">FBlood</th>
            <th class="text-center">FTower</th>
            <th class="text-center">FInhib</th>
            <th class="text-center">Towers</th>
            <th class="text-center">FPoints</th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <td> <img class="img-rounded" style="border:1px solid black; width:32; height:32px; background: #1A1A1A;" src="{{ ($teamKey == $game->blueId ? $game->blueLogoURL : $game->redLogoURL) }}"> {{ $game->fantasyTeams()[$tblCount]->teamName }}</td>
            <td class="text-center">{{ ($game->fantasyTeams()[$tblCount]->matchVictory == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') }}</td>
            <td class="text-center">{{ $game->fantasyTeams()[$tblCount]->baronsKilled }}</td>
            <td class="text-center">{{ $game->fantasyTeams()[$tblCount]->dragonsKilled }}</td>
            <td class="text-center">{{ ($game->fantasyTeams()[$tblCount]->firstBlood == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') }}</td>
            <td class="text-center">{{ ($game->fantasyTeams()[$tblCount]->firstTower == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') }}</td>
            <td class="text-center">{{ ($game->fantasyTeams()[$tblCount]->firstInhibitor == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') }}</td>
            <td class="text-center">{{ $game->fantasyTeams()[$tblCount]->towersKilled }}</td>
            <td class="text-center" style="font-weight:bold;">{{ $game->fantasyTeams()[$tblCount]->fantasyPoints() }}</td>

        </tr>

    </tbody>

</table>
</div>
<?php $tblCount++; ?>
@endforeach
<button type="button" class="btn btn-default btn-flat" style="width:100%" onclick="closeDetails('{{ $game->getMatch()->id }}');"><i class="fa fa-angle-double-up"></i> Hide stats</button>
