<script src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.nanoscroller/jquery.nanoscroller.js"></script>
<script type="text/javascript" src="js/jquery.sparkline/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="js/jquery.easypiechart/jquery.easy-pie-chart.js"></script>
<script type="text/javascript" src="js/behaviour/general.js"></script>
<script src="js/jquery.ui/jquery-ui.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.nestable/jquery.nestable.js"></script>
<script type="text/javascript" src="js/bootstrap.switch/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="js/jquery.select2/select2.min.js" type="text/javascript"></script>
<script src="js/bootstrap.slider/js/bootstrap-slider.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="js/jquery.niftymodals/js/jquery.modalEffects.js"></script>
<script type="text/javascript" src="http://cdn.rawgit.com/davatron5000/FitVids.js/master/jquery.fitvids.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        //initialize the javascript
        App.init();
        $("#streamContainer").fitVids();

        $(".fancySelect").select2({
            matcher: function(term, text, opt){
                return text.toUpperCase().indexOf(term.toUpperCase())>=0 || opt.parent("optgroup").attr("label").toUpperCase().indexOf(term.toUpperCase())>=0
            }
        });

        $("#spoilersRadio").bootstrapSwitch();
        $("#autoupdateRadio").bootstrapSwitch();
    });

    function twitchToggle()
    {


        if( $("#twitchChatEmbed").css('display') == 'none')
        {
            $('#twitchCol').animate({
                height: 490
            }, 350);

            $('#twitchBlock').animate({
                height: 484
            }, 350);

            $('#twitchChatEmbed').slideToggle(250);
        }
        else
        {
            $('#twitchChatEmbed').slideToggle(250);

            $('#twitchCol').animate({
                height: 66
            }, 350);

            $('#twitchBlock').animate({
                height: 60
            }, 350);
        }


    }

    function getBlock(id, dir)
    {
        dir = typeof dir !== 'undefined' ? dir : '';

        $.get("/ajax/block/" + id + "/" + dir, function(data) {

            var obj = jQuery.parseJSON(data);
            var scrl = $(document).scrollTop();
            $("#scheduleBlock button").tooltip('hide');
            $("#scheduleBlock").html(obj.scheduleBlock);
            $('body').scrollTop(scrl);
            $('.ttip, [data-toggle="tooltip"]').tooltip();

        });

    }

    function getVod(id, scroll)
    {
        $.get('/ajax/vod/' + id + '/', function(data)
        {
            var obj = jQuery.parseJSON(data);
            var scrl = $(document).scrollTop();
            $("#streamContainer").html(obj.streamContainer);
            $("#pageHeader").html(obj.pageHeader);
            $('body').scrollTop(scrl);
            $("#streamContainer").fitVids();
            if(scroll)
            $('html, body').animate({
                scrollTop: $("#streamContainer").offset().top - 50
            }, 1000);

        });
    }

    function getVodDetails(id, mid)
    {
        getVod(mid, false);
        getMatchDetails(id);
    }

    function getMatchDetails(id)
    {

        if($("#match-" + id + "-details").length)
        {
            if($("#match-" + id + "-details").css('display') == 'none')
            {
                $("#match-" + id + "-details").slideDown();

                $('html, body').animate({
                    scrollTop: $("#match-" + id).offset().top - 50
                }, 1000);

                $("#match-" + id + "-button").text("Hide game stats");
            }
            else
            {
                $("#match-" + id + "-details").slideUp();
                $("#match-" + id + "-button").text("View game stats");
            }

        }
        else
        {
            $.get("/ajax/details/" + id + "/", function(data) {

                var obj = jQuery.parseJSON(data);
                $("#match-" + id).after(obj.match);
                $("#match-" + id + "-details").hide();
                $("#match-" + id + "-details").slideDown('slow');
                $('html, body').animate({
                    scrollTop: $("#match-" + id).offset().top - 50
                }, 1000);
                $("#match-" + id + "-button").text("Hide game stats");
                $('.ttip, [data-toggle="tooltip"]').tooltip();

            });
        }
    }

    function closeDetails(id)
    {
        if($("#match-" + id + "-details").length)
        {
            if($("#match-" + id + "-details").css('display') == 'none')
            {
                $("#match-" + id + "-details").slideDown();

                $('html, body').animate({
                    scrollTop: $("#match-" + id).offset().top - 50
                }, 1000);

                $("#match-" + id + "-button").text("Hide game stats");
            }
            else
            {
                $("#match-" + id + "-details").slideUp();
                $("#match-" + id + "-button").text("View game stats");
            }

        }
    }
</script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/behaviour/voice-commands.js"></script>
<script src="js/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.flot/jquery.flot.js"></script>
<script type="text/javascript" src="js/jquery.flot/jquery.flot.pie.js"></script>
<script type="text/javascript" src="js/jquery.flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="js/jquery.flot/jquery.flot.labels.js"></script>
