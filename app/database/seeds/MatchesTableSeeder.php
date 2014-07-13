<?php

class MatchesTableSeeder extends Seeder {


    public function run()
    {
        Match::truncate();

        Match::create([
            'dateTime'          => date('Y-m-d H:i:s', strtotime('2014-07-13T19:00Z')),
            'matchName'         => 'Curse vs Evil Geniuses',
            'winnerId'          => '',
            'matchId'           => 2593,
            'url'               => '/tourney/match/2593',
            'maxGames'          => 1,
            'isLive'            => false,
            'isFinished'        => false,
            'liveStreams'       => false,
            'polldaddyId'       => '8157255:37105063:37105064',
            'blockId'           => 1846,

            'tournamentId'      => 104,
            'tournamentName'    => 'NA Summer Split',
            'tournamentRound'   => 8,

            'blueId'            => 4,
            'blueName'          => 'Curse',
            'blueLogoURL'       => '/sites/default/files/styles/grid_medium_square/public/CurseLogos_RGB.png?itok=U7znOoOr',
            'blueAcronym'       => 'CRS',
            'blueWins'          => 8,
            'blueLosses'        => 11,

            'redId'             => 426,
            'redName'           => 'Evil Geniuses',
            'redLogoURL'        => '/sites/default/files/styles/grid_medium_square/public/eglogo.png?itok=w5lGjHor',
            'redAcronym'        => 'EG',
            'redWins'           => 4,
            'redLosses'         => 15,

            'gameId'            => 3076,
            'gameNoVods'        => false,
            'gameHasVod'        => false,
        ]);

        Match::create([
            'dateTime'          => date('Y-m-d H:i:s', strtotime('2014-07-19T21:00Z')),
            'matchName'         => 'Team SoloMid vs LMQ',
            'winnerId'          => '',
            'matchId'           => 2595,
            'url'               => '/tourney/match/2595',
            'maxGames'          => 1,
            'isLive'            => false,
            'isFinished'        => false,
            'liveStreams'       => false,
            'polldaddyId'       => '8157255:37105063:37105064',
            'blockId'           => 1847,

            'tournamentId'      => 104,
            'tournamentName'    => 'NA Summer Split',
            'tournamentRound'   => 9,

            'blueId'            => 1,
            'blueName'          => 'Team SoloMid',
            'blueLogoURL'       => '/sites/default/files/styles/grid_medium_square/public/TSM%20-white.png?itok=aXQo1ezZ',
            'blueAcronym'       => 'TSM',
            'blueWins'          => 11,
            'blueLosses'        => 8,

            'redId'             => 1250,
            'redName'           => 'LMQ',
            'redLogoURL'        => '/sites/default/files/styles/grid_medium_square/public/LMQ_NA_1-9-14_1.png?itok=fgQ38CVQ',
            'redAcronym'        => 'LMQ',
            'redWins'           => 12,
            'redLosses'         => 7,

            'gameId'            => 3078,
            'gameNoVods'        => false,
            'gameHasVod'        => false,
        ]);

    }


}
