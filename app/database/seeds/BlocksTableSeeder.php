<?php

class BlocksTableSeeder extends Seeder {


    public function run()
    {
        Block::truncate();

        Block::create([
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

        Block::create([
            'blockId' => 1847,
            'dateTime' => '2014-07-19T19:00:00+00:00',
            'tickets' => 'https://www.eventbrite.com/e/league-of-legends-lcs-na-summer-week-9-tickets-11487398117?ref=ebtn',
            'leagueId' => 1,
            'tournamentId' => 104,
            'tournamentName' => 'NA Summer Split',
            'significance' => 0,
            'tbdTime' => 0,
            'leagueColor' => '#1376A4',
            'week' => 9,
            'label' => 'NA Summer Split - Week 9 Day 1'
        ]);
    }


}
