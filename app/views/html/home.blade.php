<!DOCTYPE html>
<html lang="en">
    <head>
        @include('html.meta', array('title' => 'Home'))
    </head>

    <body>

        @include('html.navbar')

        <div id="cl-wrapper" class="fixed-menu">

            @include('html.sidebar')

            <div class="container-fluid" id="pcont">
                <div class="page-head text-center" id="pageHeader">

                    @include('html.titlebar', array('block' => $block))

                </div>
                <div class="cl-mcont">
                    <div id="streamContainer" class="embed-responsive embed-responsive-16by9">

                        @include('html.stream', array('block' => $block))

                    </div>

                    <div class="row" style="margin-bottom: -20px; margin-top: 8px;" id="chatContainer">

                        @include('html.chat', array('block' => $block))

                    </div>

                    <div class="row" id="scheduleBlock">

                        @include('html.schedule', array('block' => $block))

                    </div>
                </div>
            </div>

        </div>

        @include('html.footer', array('block' => $block))

    </body>

</html>
