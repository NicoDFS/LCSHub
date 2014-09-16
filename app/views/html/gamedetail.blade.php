@foreach($match->getGames() as $game)
    <?php $tblCount = 0; ?>

    @if(count($match->getGames()) > 0)
        <div class='gamesContainer' style="cursor: default; {{ (count($match->getGames()) > 1 ? 'border:1px solid rgba(0, 0, 0, 0.2); padding-left:15px; padding-bottom: 6px; padding-right:15px; padding-top:5px;' : '') }} margin-top: 19px;margin-bottom: 10px; {{ (count($match->getGames()) == $game->gameNumber ? 'margin-bottom:19px;' : '') }}">

            @if(count($match->getGames()) > 1)

                <h1 style="font-size:18px; text-align:left; margin-top:5px; margin-bottom:5px;">

                    @if($game->gameLength !== null)
                    <i class="fa fa-angle-double-down" style="cursor:pointer;" onclick="gameToggle(this);" data-toggle="tooltip" data-placement="top" title="Toggle Game {{ $game->gameNumber }}"></i>
                    @endif

                    Game {{ $game->gameNumber }} -


                    @if($game->winnerId !== null)
                        {{ ($game->winnerId == $match->blueId ? $match->blueAcronym : $match->redAcronym) }}
                    @else
                        In Progress
                    @endif

                    @if(count($game->teams()) > 0)
                        - {{ ($game->gameLength/60) > 59  ? gmdate('G:i:s', $game->gameLength) : gmdate('i:s', $game->gameLength)  }}
                    @endif

                    @if($game->vodType != null)
                        <span class="pull-right" onclick="getGameVod('{{ $game->gameId }}');" style="cursor: pointer;" data-toggle="tooltip" data-placement="left" title="Watch Game{{ (count($match->getGames()) > 1 ? ' ' . $game->gameNumber : '') }}"><i id='game-{{ $game->gameId }}-play'class="fa fa-youtube-play blueIcon"></i></span>
                    @endif

                </h1>

            @endif

    @endif

    @if(count($game->teams()) == 2)
        @foreach($game->teams() as $teamKey => $team)
            <div class="table-responsive" style="cursor: default; {{ ($game->winnerId == $teamKey ? 'border:1px solid #60C060;' : '') }} {{ ($tblCount == 0 ? 'margin-bottom:20px; margin-top:18px;' : '') }}  {{ (count($match->getGames()) > 1 ? 'display:none;' : '') }}">
            <table class="hover">
                    <thead class="no-border">
                            <tr>
                                    <th>Team: {{ ($teamKey == $game->blueId ? $game->blueName : $game->redName) }} ({{ ($teamKey == $game->blueId ? $match->blueAcronym : $match->redAcronym) }})</th>
                                    <th class="text-center" style="border-left: none;"></th>
                                    <th class="text-center" style="border-left: none;"></th>
                                    <th class="text-center" style="border-left: none;"></th>
                                    <th class="text-center" style="border-left: none;"></th>
                                    <th class="text-center" style="border-left: none;"></th>
                                    @if($team[0]->fantasyPlayer !== null)
                                        <th class="text-center" style="border-left: none;"></th>
                                    @endif
                                    <th class="text-center" style="border-left: none;"></th>
                            </tr>
                            <tr>
                                    <th>Player</th>
                                    <th class="text-center">Champ</th>
                                    <th class="text-center">KDA</th>
                                    <th class="text-center">Gold</th>
                                    <th class="text-center">CS</th>
                                    @if($team[0]->fantasyPlayer !== null)
                                        <th class="text-center" style="font-weight:bold;">Fantasy</th>
                                    @endif
                                    <th class="text-center">Spells</th>
                                    <th class="text-center" style="width: 224px;">Items</th>
                            </tr>
                    </thead>
                    <tbody class="no-border-y">
                    @foreach($team as $player)
                            <tr>
                                    <td> <img class="img-rounded" style="border:1px solid black; width:70px; height:50px;" src="{{ $player->photoURL }}"> <span data-toggle="tooltip" title="Position: {{ ( $player->fantasyPlayer !== null ? $player->fantasyPlayer->role : 'N/A') }}"> &nbsp;{{ $player->name }}</span></td>
                                    <td class="text-center"> <img class="img-rounded" style="border:1px solid black;" src="http://lkimg.zamimg.com/shared/riot/images/champions/{{ $player->championId }}_32.png"> &nbsp;({{ $player->endLevel }})</td>
                                    <td class="text-center"><span data-toggle="tooltip" title="{{ floatval((double)$player->kda) }}">{{ $player->kills }}/{{ $player->deaths }}/{{ $player->assists }}</span></td>
                                    <td class="text-center"><span data-toggle="tooltip" title="{{ number_format($player->totalGold) }}">{{ GamePlayer::count_format($player->totalGold) }}</span></td>
                                    <td class="text-center">{{ number_format($player->minionsKilled) }}</td>
                                    @if($player->fantasyPlayer !== null)
                                        <td class="text-center" style="font-weight:bold;"> <div style="border-bottom: 1px dashed #999;display: inline; cursor:pointer;" data-placement="left" data-template="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title text-center'></h3><div class='popover-content'></div></div>" data-toggle="popover" data-trigger='hover' data-html="true" data-content="{{ $player->fantasyPlayer->generatePopover() }}" title="Fantasy Points Breakdown">{{ $player->fantasyPlayer->fantasyPoints() }}</div></td>
                                    @endif
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

            @if($team[0]->fantasyPlayer !== null)
                <div class="table-responsive" style="cursor: default;  {{ ($tblCount == 0 ? 'margin-top: -10px; margin-bottom: 35px;' : ' margin-top:10px; margin-bottom:10px;') }} {{ ($game->winnerId == $teamKey ? 'border:1px solid #60C060;' : '') }} {{ (count($match->getGames()) > 1 ? 'display:none;' : '') }}">
                <table  class="hover">

                    <thead class="no-border">

                        <tr style="background: rgb(248, 248, 248);">

                            <th>Team</th>
                            <th class="text-center">Win</th>
                            <th class="text-center">Barons</th>
                            <th class="text-center">Dragons</th>
                            <th class="text-center">1st Blood</th>
                            <th class="text-center">1st Tower</th>
                            <th class="text-center">1st Inhib</th>
                            <th class="text-center">Towers</th>
                            <th class="text-center" style="font-weight:bold;">Fantasy</th>

                        </tr>

                    </thead>

                    <tbody class="no-border-y">

                        <tr>

                            <td> <img class="img-rounded" style="padding:2px; border:1px solid black; width:32; height:32px; background: #1A1A1A;" src="{{ ($teamKey == $game->blueId ? $game->blueLogoURL : $game->redLogoURL) }}"> &nbsp;{{ ($teamKey == $game->blueId ? $game->blueName : $game->redName) }}</td>
                            @if(count($game->fantasyTeams()) == 2)
                                <td class="text-center">{{ ($game->fantasyTeams()[$teamKey]->matchVictory == 1 ? '<i style="color: rgb(0, 128, 0);" class="fa fa-check"></i>' : '<i style="color: rgb(237, 91, 86);" class="fa fa-times"></i>') }}</td>
                                <td class="text-center">{{ $game->fantasyTeams()[$teamKey]->baronsKilled }}</td>
                                <td class="text-center">{{ $game->fantasyTeams()[$teamKey]->dragonsKilled }}</td>
                                <td class="text-center">{{ ($game->fantasyTeams()[$teamKey]->firstBlood == 1 ? '<i style="color: rgb(0, 128, 0);" class="fa fa-check"></i>' : '<i style="color: rgb(237, 91, 86);" class="fa fa-times"></i>') }}</td>
                                <td class="text-center">{{ ($game->fantasyTeams()[$teamKey]->firstTower == 1 ? '<i style="color: rgb(0, 128, 0);" class="fa fa-check"></i>' : '<i style="color: rgb(237, 91, 86);" class="fa fa-times"></i>') }}</td>
                                <td class="text-center">{{ ($game->fantasyTeams()[$teamKey]->firstInhibitor == 1 ? '<i style="color: rgb(0, 128, 0);" class="fa fa-check"></i>' : '<i style="color: rgb(237, 91, 86);" class="fa fa-times"></i>') }}</td>
                                <td class="text-center">{{ $game->fantasyTeams()[$teamKey]->towersKilled }}</td>
                                <td class="text-center" style="font-weight:bold;">
                                    <div style="border-bottom: 1px dashed #999;display: inline; cursor:pointer;" data-placement="left" data-template="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title text-center'></h3><div class='popover-content'></div></div>" data-toggle="popover" data-trigger='hover' data-html="true" data-content="{{ $game->fantasyTeams()[$teamKey]->generatePopover() }}" title="Fantasy Points Breakdown">{{ $game->fantasyTeams()[$teamKey]->fantasyPoints() }}</div>
                                </td>
                            @else
                                <td class="text-center">N/A</td>
                                <td class="text-center">N/A</td>
                                <td class="text-center">N/A</td>
                                <td class="text-center">N/A</td>
                                <td class="text-center">N/A</td>
                                <td class="text-center">N/A</td>
                                <td class="text-center">N/A</td>
                                <td class="text-center" style="font-weight:bold;">N/A</td>
                            @endif

                        </tr>

                    </tbody>

                </table>

                </div>

                <?php $tblCount++; ?>

            @endif

        @endforeach

    @endif

    @if(count($match->getGames()) > 1)
        <button type="button" class="btn btn-default btn-flat" style="width:100%; margin:0px; outline: none; {{ (count($match->getGames()) > 1 ? 'display:none;' : '') }}" onclick="gameToggle(this);"><i class="fa fa-angle-double-up"></i> Hide Game {{ $game->gameNumber }}</button>
    @endif
    </div>

@endforeach

<button type="button" class="btn btn-default btn-flat" style="width:100%; margin:0px; outline: none; font-size:18px; font-weight:300; padding-top: 8px;padding-bottom: 8px;" onclick="closeDetails('{{ $match->id }}');"><i class="fa fa-angle-double-up" style="font-size:18px; font-weight:300;"></i> Hide game{{ (count($match->getGames()) > 1 ? 's' : '') }}</button>
