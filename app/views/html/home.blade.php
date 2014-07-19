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

        <script type="text/javascript">
            $(function() {
                setInterval(function()
                {
                    $.get("/ajax/refresh", function(data)
                    {
                        var obj = jQuery.parseJSON(data);
                        var scrl = $(document).scrollTop();

                        $("#scheduleBlock").html(obj.scheduleBlock);
                        $('body').scrollTop(scrl);
                        $("#pageHeader").html(obj.pageHeader);

                    });

                }, 3000);
            });
        </script>

    </body>

</html>
