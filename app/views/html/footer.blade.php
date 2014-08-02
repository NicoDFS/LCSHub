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
        $("#streamContainer").fitVids({ customSelector: "object[data^='http://www.twitch.tv/widgets/live_embed_player.swf?channel=riotgames']"});

        $(".fancySelect").select2({
            matcher: function(term, text, opt){
                return text.toUpperCase().indexOf(term.toUpperCase())>=0 || opt.parent("optgroup").attr("label").toUpperCase().indexOf(term.toUpperCase())>=0
            }
        });


        $("#spoilersRadio").bootstrapSwitch();
        $("#autoupdateRadio").bootstrapSwitch();
    });

    function getMatch (id)
    {
        $.get("/ajax/match/" + id, function(data)
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
    }

    function refreshDetail(id)
    {
        //$("#refresh-match-" + id).addClass('fa-spin');
        $("#refresh-match-" + id).attr('disabled', true);

        $.get("/ajax/details/" + id + "/", function(data) {

            var obj = jQuery.parseJSON(data);
            var cnt = $(obj.match).contents();
            $("#match-" + id + "-details").height($("#match-" + id + "-details").height());
            obj.match = cnt;
            $("#match-" + id + "-details").html(obj.match);
            $("#refresh-match-" + id).attr('disabled', false);
            $('.ttip, [data-toggle="tooltip"]').tooltip();
            $("[data-toggle=popover]").popover();
            //$("#refresh-match-" + id).removeClass('fa-spin');

        });
    }

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
                scrollTop: $("#streamContainer").offset().top - 60
            }, 1000);
            $('.ttip, [data-toggle="tooltip"]').tooltip();

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
                $("[data-toggle=popover]").popover();


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

    function getLiveGame(id)
    {
        $.get("/ajax/match/" + id, function(data) {

            var obj = jQuery.parseJSON(data);
            $("#streamContainer").html(obj.streamContainer);
            $("#scheduleBlock").html(obj.scheduleBlock);
            $("#pageHeader").html(obj.pageHeader);
            $("#streamContainer").fitVids();
            $('.ttip, [data-toggle="tooltip"]').tooltip();
            $('html, body').animate({
                scrollTop: $("#streamContainer").offset().top - 60
            }, 1000);

        });
    }

    function refreshInfo(id)
    {
        $.get("/ajax/refreshmatch/" + id, function(data) {

            var obj = jQuery.parseJSON(data);
            var scrl = $(document).scrollTop();

            $("#streamContainer").html(obj.streamContainer);
            $("#scheduleBlock").html(obj.scheduleBlock);
            $("#pageHeader").html(obj.pageHeader);
            $("#streamContainer").fitVids();
            $('.ttip, [data-toggle="tooltip"]').tooltip();
            $('body').scrollTop(scrl);

        });
    }

    function saveSettings()
    {

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
@if(!Cookie::has(Config::get('cookie.timezone')))
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.4/jstz.js"></script>
<script type="text/javascript">
    $(function() {

        var timezone = jstz.determine();
        $('[id="{{ Config::get('cookie.timezone') }}"]').select2('val', timezone.name());

        $.post('/ajax/timezone/', { timezone: timezone.name() }, function(data)
        {
            if(data.timezone == timezone.name())
            {
                $.gritter.add({
                    title: 'Timezone Updated',
                    text: 'Timezone: <code>' + data.timezone +'</code> If this is wrong, change it in your <a href="#" class="md-trigger" data-modal="settingsModal" onclick="return false;" style="color: yellow !important;text-decoration: underline;">settings</a>.',
                    class_name: 'primary',
                    sticky: true
                });

                $(".md-trigger").modalEffects();

                refreshInfo('{{ $block->getMatches()[0]->matchId }}');
            }

        }, "json");
    });
</script>
@endif
