<?php

class MatchesTableSeeder extends Seeder {


    public function run()
    {
        Match::truncate();

        Match::create([
            'dateTime'      => '2014-07-13T19:00Z',
            'matchName'     => 'Curse vs Evil Geniuses',
            'winnerId'      => '',
            'matchId'       => 2593,
            'url'           => '/tourney/match/2593',
            'maxGames'      => 1,
            'isLive'        => false,
            'isFinished'    => false,
            'liveStreams'   => false,
            'polldaddyId'   => '8157255:37105063:37105064',

            'tournamentId'      => 104,
            'tournamentName'    => 'NA Summer Split',
            'tournamentRound'   => 8,

            'blueId'        => 4,
            'blueName'      => 'Curse',
            'blueLogoURL'   => '/sites/default/files/styles/grid_medium_square/public/CurseLogos_RGB.png?itok=U7znOoOr',
            'blueAcronym'   => 'CRS',
            'blueWins'      => 8,
            'blueLosses'    => 11,

            'redId'        => 426,
            'redName'      => 'Evil Geniuses',
            'redLogoURL'   => '/sites/default/files/styles/grid_medium_square/public/eglogo.png?itok=w5lGjHor',
            'redAcronym'   => 'EG',
            'redWins'      => 4,
            'redLosses'    => 15,

            'gameId'       => 3076,
            'gameNoVods'   => false,
            'gameHasVod'   => false,

        ]);

    }


}
