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

                    @include('html.titlebar', array('block' => $block))

                </div>
                <div class="cl-mcont">
                    <div id="streamContainer">
                        @include('html.stream', array('block' => $block))
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

        <script type="text/javascript">
            $(function() {
                //setInterval(function()
                //{
                //    $.get("/ajax/refresh", function(data)
                //    {
                //        var obj = jQuery.parseJSON(data);
                //        var scrl = $(document).scrollTop();
                //
                //        $("#scheduleBlock").html(obj.scheduleBlock);
                //        $('body').scrollTop(scrl);
                //        $("#pageHeader").html(obj.pageHeader);
                //        //$("#streamContainer").html(obj.streamContainer);
                //        $('.ttip, [data-toggle="tooltip"]').tooltip();
                //        //$("#streamContainer").fitVids();
                //
                //    });
                //
                //}, 3000);

                $.get("/ajax/match/2515", function(data)
                {
                    var obj = jQuery.parseJSON(data);
                    var scrl = $(document).scrollTop();

                    $("#scheduleBlock").html(obj.scheduleBlock);
                    $('body').scrollTop(scrl);
                    $("#pageHeader").html(obj.pageHeader);
                    $("#streamContainer").html(obj.streamContainer);
                    $('.ttip, [data-toggle="tooltip"]').tooltip();
                    $("#streamContainer").fitVids();

                });
            });
        </script>

    </body>

</html>
