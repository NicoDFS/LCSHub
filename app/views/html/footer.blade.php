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

        App.init();
        $("#streamContainer").fitVids({ customSelector: "object[data^='http://www.twitch.tv/widgets/live_embed_player.swf?channel=riotgames']"});

        $(".fancySelect").select2({
            matcher: function(term, text, opt){
                return text.toUpperCase().indexOf(term.toUpperCase())>=0 || opt.parent("optgroup").attr("label").toUpperCase().indexOf(term.toUpperCase())>=0
            }
        });

        window.fantasyPositions = ['top', 'jungle', 'mid', 'adc', 'support', 'flex', 'team'];

        $("#fantasyTeamSelect").on("change", function() {
            var $this = $(this);

            if($this.val() in window.fantasyTeams)
            {
                $.each(window.fantasyPositions, function(index, value) {

                    $("#" + value + "Select").select2('val', window.fantasyTeams[$this.val()][value]);

                });

                $(".positionSelect").attr('disabled', false);

                if($("#fantasyDelete").length <= 0)
                {
                    $("#fantasyForm").append('<button type="button" class="btn btn-danger btn-flat" style="width:100%;margin-bottom: 18px !important;margin-top: 12px;" id="fantasyDelete" >Delete Fantasy Team</button>');
                }

                $("#fantasyDelete").unbind( "click" );
                $("#fantasyDelete").click(function()
                {

                    deleteFantasyTeam($this.val());

                });

            }
        });

        $(".positionSelect").on("change", function() {
            var $this = $(this);
            var $fIndex = $("#fantasyTeamSelect").select2('val');

            if($fIndex !== null)
            {
                if($fIndex in window.fantasyTeams)
                {

                    $.each(window.fantasyPositions, function(index, value) {

                        window.fantasyTeams[$fIndex][value] = $("#" + value + "Select").select2('val');

                    });

                }
            }

        });

        window.fantasyTeams = [];

        @if(Cookie::has(Config::get('cookie.fantasyTeams')))
            <?php $fTeams = Cookie::get(Config::get('cookie.fantasyTeams'));  ?>
            @foreach($fTeams as $fKey => $fValue)
                @if($fValue !== null)
                    window.fantasyTeams[{{ $fKey + 1}}] = new Object();
                    @foreach($fValue as $pKey => $pValue)
                        window.fantasyTeams[{{ $fKey + 1 }}].{{ $pKey }} = '{{ $pValue }}';
                    @endforeach
                @endif
            @endforeach
        @endif


        $('[id="{{ Config::get('cookie.spoilers') }}"]').bootstrapSwitch();
        $('[id="{{ Config::get('cookie.updates') }}"]').bootstrapSwitch();
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

    function refreshTitleSchedule(id)
    {
        $.get("/ajax/refreshmatch/" + id, function(data) {

            var obj = jQuery.parseJSON(data);

            $("#scheduleBlock").html(obj.scheduleBlock);
            $("#pageHeader").html(obj.pageHeader);
            $('.ttip, [data-toggle="tooltip"]').tooltip();

        });
    }

    function resetSelects()
    {
        $(".positionSelect").select2('val', null);
        $(".positionSelect").attr('disabled', true);
    }

    function addFantasyTeam()
    {
        var teamName = prompt('Fantasy Team Name?');
        if(teamName)
        {
            var posX = window.fantasyTeams.length;
            $("#fantasyTeamSelect").select2('destroy');

            $("#fantasyTeamSelect").append("<option value='" + posX + "'>" + teamName + "</option>");
            $("#fantasyTeamSelect").select2().select2('val', posX);
            window.fantasyTeams[posX] = new Object();
            window.fantasyTeams[posX].fantasyname = teamName;

            $(".positionSelect").attr('disabled', false);
            $(".positionSelect").select2('val', null);

            if($("#fantasyDelete").length <= 0)
            {
                $("#fantasyForm").append('<button type="button" class="btn btn-danger btn-flat" style="width:100%;margin-bottom: 18px !important;margin-top: 12px;" id="fantasyDelete">Delete Fantasy Team</button>');
            }

            $("#fantasyDelete").unbind( "click" );
            $("#fantasyDelete").click(function()
            {

                deleteFantasyTeam(posX);

            });

        }

    }

    function deleteFantasyTeam(id)
    {
        delete window.fantasyTeams[id];

        $("#fantasyTeamSelect").select2('destroy');
        $("#fantasyTeamSelect").find('option').remove();
        $("#fantasyTeamSelect").append("<option></option>");

        $.each(window.fantasyTeams, function(fKey, fValue) {
        if(!(fValue == null))

            $.each(fValue, function (qIndex, qValue) {

                if(qIndex == 'fantasyname')
                {
                    $('#fantasyTeamSelect').append("<option value='" + fKey + "'>" + qValue + "</option>");
                }

            });

        });

        resetSelects();

        $("#fantasyTeamSelect").select2();
        $("#fantasyDelete").fadeOut(200, function() {
            $(this).remove();
        });
    }

    function saveSettings()
    {

        var updatesRes = ($('[id="{{ Config::get('cookie.updates') }}"]').is(':checked') ? 1 : 0);
        var spoilersRes = ($('[id="{{ Config::get('cookie.spoilers') }}"]').is(':checked') ? 1 : 0);

        if(0 in window.fantasyTeams)
        {
            if(window.fantasyTeams[0] == null)
            {
                window.fantasyTeams.shift();
            }
        }

        $.post('/ajax/settings/',
        {
            timezone: $('[id="{{ Config::get('cookie.timezone') }}"]').select2('val'),
            updates: updatesRes,
            spoilers: spoilersRes,
            fantasyTeams: JSON.stringify(window.fantasyTeams)
        },
        function(data)
        {

            if(data.message == 'success')
            {
                $.gritter.add({
                    title: 'Settings Updated!',
                    text: 'Your settings have been updated successfully.',
                    class_name: 'success'
                });

                $("#settingsModalClose").click();
                refreshTitleSchedule('{{ $block->getMatches()[0]->matchId }}');
            }

        }, "json");
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
                window.stickyTimezone = $.gritter.add({
                    title: 'Timezone Updated!',
                    text: 'Your new timezone: <code>' + data.timezone +'</code>. If this is wrong, please change it now in your <a href="#" class="md-trigger" data-modal="settingsModal" onclick="$.gritter.remove(window.stickyTimezone); return false;" style="color: yellow !important;text-decoration: underline;">settings</a>.',
                    class_name: 'primary',
                    sticky: true
                });

                $(".md-trigger").modalEffects();

                refreshTitleSchedule('{{ $block->getMatches()[0]->matchId }}');
            }

        }, "json");
    });
</script>
@endif
