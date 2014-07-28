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
                    <div id="streamContainer" class="embed-responsive embed-responsive-16by9">
                        @include('html.stream', array('block' => $block))
                    </div>

                    <div class="row" style="margin-bottom: -20px;margin-top: 8px;">
                        <div class="col-md-12" style="height: 66px;" id="twitchCol">
                            <div class="block-flat" id="twitchBlock" style="border: 1px solid #DDD;padding-top: 10px;text-align: center;height: 60px;">
                                <button type="button" class="btn btn-primary btn-rad" style="margin-top:2px;" onclick="twitchToggle();"><i class="fa fa-bars"></i> &nbsp;Toggle Twitch Chat</button>
                                <iframe frameborder="0" scrolling="no" id="twitchChatEmbed" src="http://twitch.tv/chat/embed?channel=riotgames&amp;popout_chat=true" height="404" width="100%" style="display:none; margin-top:5px;"></iframe>
                            </div>
                        </div>
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
                //        $("#streamContainer").html(obj.streamContainer);
                //        $('.ttip, [data-toggle="tooltip"]').tooltip();
                //        $("#streamContainer").fitVids();
                //
                //    });
                //
                //}, 30000);

                //
                //$.get("/ajax/match/2515", function(data)
                //{
                //    var obj = jQuery.parseJSON(data);
                //    var scrl = $(document).scrollTop();
                //
                //    $("#scheduleBlock").html(obj.scheduleBlock);
                //    $('body').scrollTop(scrl);
                //    $("#pageHeader").html(obj.pageHeader);
                //    $("#streamContainer").html(obj.streamContainer);
                //    $('.ttip, [data-toggle="tooltip"]').tooltip();
                //    $("#streamContainer").fitVids();
                //
                //});
            });
        </script>

    </body>

</html>
