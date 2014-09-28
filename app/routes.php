<?php

Route::controller('ajax', 'AjaxController');

Route::get('/', function()
{

    if(Cookie::has(Config::get('cookie.fantasyTeams')))
    {
        if(is_array(Cookie::get(Config::get('cookie.fantasyTeams'))))
        {
            unset($_COOKIE[Config::get('cookie.fantasyTeams')]);
            Cookie::queue(Config::get('cookie.fantasyTeams'), null, (60 * 24 * 360));
        }
    }

    return View::make('html.home')->with('block', Block::currentBlock());
});


Route::get('/test', function()
{

    $datetime = new DateTime('now', new DateTimeZone(Block::defaultTimezone()));
    $dateplus = new DateTime('+1 day', new DateTimeZone(Block::defaultTimezone()));

    dd($blocks = Block::where('blocks.dateTime', '>=', $datetime->format('Y-m-d H:i:s'))
                        ->where('blocks.dateTime', '<=', $dateplus->format('Y-m-d H:i:s'))
                        ->join('matches', 'matches.blockId', '=', 'blocks.blockId')
                        ->select('blocks.*', 'matches.isFinished', 'matches.isLive')
                        ->toSql());

});

Route::group(array('prefix' => 'reset'), function()
{

    Route::get('all', function()
    {

        $tableNames = DB::select('SHOW TABLES');

        foreach ($tableNames as $name)
        {
            if ($name->Tables_in_flcshub == 'migrations')
            {
                continue;
            }

            DB::statement("TRUNCATE {$name->Tables_in_flcshub}");
        }

    });

});

Route::group(array('prefix' => 'insert'), function()
{

    $methods = get_class_methods('InsertController');

    foreach($methods as $methodName)
    {
        Route::get($methodName, 'InsertController@' . $methodName);
    }


});

