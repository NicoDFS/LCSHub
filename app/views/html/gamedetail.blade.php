<?php $tblCount = 0; ?>
@foreach($game->teams() as $team)
<table style="{{ ($tblCount == 0 ? 'margin-bottom:20px' : '') }}">
        <thead>
                <tr>
                        <th>Team: {{ ($tblCount == 0 ? $game->blueName : $game->redName) }}</th>
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
                        <td> <img class="img-rounded" style="border:1px solid black; width:70px; height:50px;" src="{{ $player->photoURL }}"> {{ $player->name }}</td>
                        <td class="text-center"> <img class="img-rounded" style="border:1px solid black;" src="http://lkimg.zamimg.com/shared/riot/images/champions/{{ $player->championId }}_32.png"> ({{ $player->endLevel }})</td>
                        <td class="text-center">{{ $player->kills }}/{{ $player->deaths }}/{{ $player->assists }}</td>
                        <td class="text-center">{{ GamePlayer::count_format($player->totalGold) }}</td>
                        <td class="text-center">{{ $player->minionsKilled }}</td>
                        <td class="text-center">{{ $player->getFantasyPlayer()->fantasyPoints() }}</td>
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

<table style="{{ ($tblCount == 0 ? 'margin-top: -10px; margin-bottom: 25px;' : ' margin-top:10px;') }}">

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

            <td> <img class="img-rounded" style="border:1px solid black; width:32; height:32px; background: #1A1A1A;" src="{{ ($tblCount == 0 ? $game->blueLogoURL : $game->redLogoURL) }}"> {{ $game->fantasyTeams()[$tblCount]->teamName }}</td>
            <td class="text-center">{{ ($game->fantasyTeams()[$tblCount]->matchVictory == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') }}</td>
            <td class="text-center">{{ $game->fantasyTeams()[$tblCount]->baronsKilled }}</td>
            <td class="text-center">{{ $game->fantasyTeams()[$tblCount]->dragonsKilled }}</td>
            <td class="text-center">{{ ($game->fantasyTeams()[$tblCount]->firstBlood == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') }}</td>
            <td class="text-center">{{ ($game->fantasyTeams()[$tblCount]->firstTower == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') }}</td>
            <td class="text-center">{{ ($game->fantasyTeams()[$tblCount]->firstInhibitor == 1 ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>') }}</td>
            <td class="text-center">{{ $game->fantasyTeams()[$tblCount]->towersKilled }}</td>
            <td class="text-center">{{ $game->fantasyTeams()[$tblCount]->fantasyPoints() }}</td>

        </tr>

    </tbody>

</table>
<?php $tblCount++; ?>
@endforeach
