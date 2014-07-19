<!DOCTYPE html>
<html lang="en">
    <head>
        @include('html.meta', array('title' => 'Home'))
    </head>

    <body>

        <!-- Start Fixed navbar -->
        @include('html.navbar')
        <!-- End Fixed navbar -->


        <div id="cl-wrapper" class="fixed-menu">

            @include('html.sidebar')

            <div class="container-fluid" id="pcont">
                <div class="page-head text-center" id="pageHeader">

                    <h2>
                        @if(($activeMatch = $block->activeMatch()) !== null)
                            <img src="http://na.lolesports.com{{ $activeMatch->blueLogoURL }}" width='50' height='50'>
                            {{ $activeMatch->matchName }}
                            <img src="http://na.lolesports.com{{ $activeMatch->redLogoURL }}" width='50' height='50'>
                        @else
                            Currently No Live Games
                        @endif
                    </h2>

                    <ol class="breadcrumb">
                        <li><a href="#">{{ $block->getLeague()->shortName }}</a></li>
                        <li><a href="#">{{ $block->tournamentName }}</a></li>
                        <li><a href="#">{{ substr($block->label, strpos($block->label, " - ") + 3) }}</a></li>
                        <li class="active">
                            @if($activeMatch !== null)
                                <a href="#">{{ $activeMatch->matchName }}</a>
                            @else
                                None
                            @endif
                        </li>
                    </ol>
                </div>
                <div class="cl-mcont">
                    <div id="streamContainer">
                        <iframe width="1280" height="720" src="//www.youtube-nocookie.com/embed/MiIh5cSOMSE?autoplay=1" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="scheduleBlock">
                            @include('html.schedule', array('block' => $block))
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @include('html.footer')

    </body>

</html>
