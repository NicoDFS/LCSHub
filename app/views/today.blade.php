<!doctype html>
<html>

    <head>

        <meta charset="utf-8">

        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">

    </head>

    <body>

        <div class="container col-md-6 col-md-offset-3">

            <h1>Today's LCS Matches</h1>

            <ul class="list-group">

            @foreach($matches as $match)
                <li class="list-group-item">{{  $match->blueName  }} vs {{ $match->redName }}
                    @if ($match->isLive && !$match->isFinished)
                        <span class='pull-right'>Live</span>
                    @endif

                    @if(!$match->isLive && !$match->isFinished)
                        <span class='pull-right'>Scheduled</span>
                    @endif

                    @if(!$match->isLive && $match->isFinished)
                        <span class='pull-right'>Finished</span>
                    @endif
                </li>
            @endforeach

            </ul>

        </div>

    </body>

</html>
