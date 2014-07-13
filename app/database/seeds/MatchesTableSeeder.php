<?php

class MatchesTableSeeder extends Seeder {


    public function run()
    {
        Match::truncate();

        Match::create([
            'blockId' => 1846,
            'dateTime' => '2014-07-13T19:00:00+00:00',
            'tickets' => '',
            'leagueId' => 1,
            'tournamentId' => 104,
            'tournamentName' => 'NA Summer Split',
            'significance' => 0,
            'tbdTime' => 0,
            'leagueColor' => '#1376A4',
            'week' => 8,
            'label' => 'NA Summer Split - Week 8 Day 2'
        ]);

    }


}
