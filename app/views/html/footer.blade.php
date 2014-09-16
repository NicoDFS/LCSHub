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

        $("#streamContainer").fitVids({ customSelector: "object[data^='http://www.twitch.tv/widgets/live_embed_player.swf?channel=']"});

        window.liveUpdates = {{ (Cookie::has(Config::get('cookie.updates')) ? Cookie::get(Config::get('cookie.updates')) : 0) }};

        setInterval(function()
        {
            if(window.liveUpdates == 1)
            {
                $.getJSON("/ajax/refresh/", function(data)
                {
                    var scrl = $(document).scrollTop();

                    $detailz = $(".match-detail");

                    $('.ttip, [data-toggle="tooltip"]').tooltip('hide');
                    $("[data-toggle=popover]").popover('hide');
                    $("#scheduleBlock").height($("#scheduleBlock").height());
                    $("#scheduleBlock").html(data.scheduleBlock);
                    $("#scheduleBlock").css('height', 'auto');

                    $.each($detailz, function(index, value) {

                        $("#match-" + $(value).attr('matchid')).after($(value));

                        if ( $("#refresh-match-" + $(value).attr('matchid')).length )
                        {
                            if ($(value).css('display') == 'block')
                            {
                                refreshDetail($(value).attr('matchid'));
                            }
                        }

                    });

                    $("#pageHeader").height($("#pageHeader").height());
                    $("#pageHeader").html(data.pageHeader);
                    $("#pageHeader").css('height', 'auto');
                    $('.ttip, [data-toggle="tooltip"]').tooltip();
                    $("[data-toggle=popover]").popover();

                });
            }

        }, 15000);

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

        <?php

        if(Cookie::has(Config::get('cookie.fantasyTeams')))
        {
            $teams = Cookie::get(Config::get('cookie.fantasyTeams'));
            echo "window.fantasyTeams = jQuery.parseJSON('$teams');";
        }

        ?>



        $('[id="{{ Config::get('cookie.spoilers') }}"]').bootstrapSwitch();
        $('[id="{{ Config::get('cookie.updates') }}"]').bootstrapSwitch();
    });

    function getMatch (id)
    {
        $.getJSON("/ajax/match/" + id, function(data)
        {
            var scrl = $(document).scrollTop();

            $("#scheduleBlock").html(data.scheduleBlock);
            $('body').scrollTop(scrl);
            $("#pageHeader").html(data.pageHeader);
            $("#streamContainer").html(data.streamContainer);
            $("#chatContainer").html(data.chatContainer);
            $('.ttip, [data-toggle="tooltip"]').tooltip();
            $("#streamContainer").fitVids();

        });
    }

    function refreshDetail(id)
    {

        $("#refresh-match-" + id).attr('disabled', true);
        $("#refresh-match-" + id + " i").addClass("fa-spin");

        $.getJSON("/ajax/details/" + id + "/", function(data) {

            var cnt = $(data.match).contents();
            data.match = cnt;

            var arrOpen = new Array();

            $(".gamesContainer").each(function(index, ele) {

                if($(ele).children('.table-responsive').first().css('display') == 'block')
                {
                    arrOpen[arrOpen.length] = index;
                }

            });

            $("#match-" + id + "-details").height($("#match-" + id + "-details").height());
            $("#match-" + id + "-details").html(data.match);
            $("#match-" + id + "-details").css('height', 'auto');

            $.each(arrOpen, function(index, value) {

                $(".gamesContainer").each(function(ind, ele) {

                    if(ind == value)
                    {
                        $(ele).children('h1').children().first().click();
                    }

                });

            });

            $("#refresh-match-" + id).attr('disabled', false);
            $("#refresh-match-" + id + " i").removeClass("fa-spin");
            $('.ttip, [data-toggle="tooltip"]').tooltip();
            $("[data-toggle=popover]").popover();


        });
    }

    function getBlock(id, dir)
    {
        dir = typeof dir !== 'undefined' ? dir : '';

        if (dir == 'prev' || dir == 'next')
        {
            $("#" + dir + "Blocks").removeAttr('onclick');

            if (dir == 'prev')
            {
                $("#" + dir + "Blocks").removeClass('fa-angle-double-left');

            } else if (dir == 'next')
            {
                $("#" + dir + "Blocks").removeClass('fa-angle-double-right');
            }

            $("#" + dir + "Blocks").addClass("fa-refresh fa-spin");

            $("#" + dir + "Blocks").css({
                    'font-size': '16px',
                    'margin-top': '6px',
                    'cursor': 'default'
            });

            $("#" + dir + "Blocks").tooltip('hide');
        }

        if (id == 'current')
        {
            $("#currentBlock").removeAttr('onclick');
            $("#currentBlock").removeClass('fa-reply fa-share');
            $("#currentBlock").addClass("fa-refresh fa-spin");

            $("#currentBlock").css({
                    'font-size': '16px',
                    'margin-top': '6px',
                    'cursor': 'default'
            });

            $("#currentBlock").tooltip('hide');
        }

        $.getJSON("/ajax/block/" + id + "/" + dir, function(data) {

            var scrl = $(document).scrollTop();
            $("#scheduleBlock button").tooltip('hide');
            $("#scheduleBlock").html(data.scheduleBlock);
            $('body').scrollTop(scrl);
            $('.ttip, [data-toggle="tooltip"]').tooltip();

            if(id == 'current')
            {
                //window.liveUpdates = 1;
            }

        });

    }

    function getGameVod(id)
    {

        if ($("#game-" + id + "-play").length)
        {
            $("#game-" + id + "-play").removeClass('fa-youtube-play blueIcon').addClass('fa-refresh fa-spin');
        }

        $.getJSON('/ajax/gamevod/' + id + '/', function(data)
        {
            var scrl = $(document).scrollTop();
            $("#streamContainer").html(data.streamContainer);
            $("#pageHeader").html(data.pageHeader);
            $("#chatContainer").html(data.chatContainer);
            $('body').scrollTop(scrl);
            $("#streamContainer").fitVids();
            $('html, body').animate({
                scrollTop: $("#streamContainer").offset().top - 60
            }, 1000);
            $('.ttip, [data-toggle="tooltip"]').tooltip();

            if ($("#game-" + id + "-play").length)
            {
                $("#game-" + id + "-play").removeClass('fa-refresh fa-spin').addClass('fa-youtube-play blueIcon');
            }

        });
    }

    function getVod(id, scroll)
    {
        $.get('/ajax/vod/' + id + '/', function(data)
        {
            var scrl = $(document).scrollTop();
            $("#streamContainer").html(data.streamContainer);
            $("#pageHeader").html(data.pageHeader);
            $("#chatContainer").html(data.chatContainer);
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

                $("#match-" + id + "-button").text("Hide match result");
            }
            else
            {
                $("#match-" + id + "-details").slideUp();
                $("#match-" + id + "-button").text("View match result");
            }

        }
        else
        {

            if ($("#match-" + id).length)
            {
                $("#match-" + id + " button").each(function(index) {

                    if (index == 1)
                    {
                        //$(this).children('span').first().removeClass('caret').addClass('fa fa-refresh fa-spin').css('font-size', '12px');
                        //$(this).children('span').first().replaceWith('<i class="fa fa-refresh fa-spin" style=" font-size: 12px; "></i>');
                    }

                    $(this).focus();
                    $(this).attr('disabled', 'disabled');

                });
            }

            $.getJSON("/ajax/details/" + id + "/", function(data)
            {
                $("#match-" + id).after(data.match);
                $("#match-" + id + "-details").hide();
                $("#match-" + id + "-details").slideDown('slow');
                $('html, body').animate({
                    scrollTop: $("#match-" + id).offset().top - 50
                }, 1000);
                $("#match-" + id + "-button").text("Hide match result");
                $('.ttip, [data-toggle="tooltip"]').tooltip();
                $("[data-toggle=popover]").popover();

                if ($("#match-" + id).length)
                {
                    $("#match-" + id + " button").each(function(index) {

                        $(this).removeAttr('disabled');

                        if (index == 1)
                        {
                            //$(this).children('i').first().removeClass('fa fa-refresh fa-spin').addClass('caret');
                            $(this).children('i').first().replaceWith('<span class="caret"></span>');
                        }

                    });
                }


            });
        }

        $("#match-" + id).children('div').children('button').first().blur();

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

                $("#match-" + id + "-button").text("Hide match result");
            }
            else
            {
                $("#match-" + id + "-details").slideUp();
                $("#match-" + id + "-button").text("View match result");
            }

        }
    }

    function getLiveGame(id)
    {
        $.getJSON("/ajax/match/" + id, function(data)
        {
            $("#streamContainer").html(data.streamContainer);
            $("#scheduleBlock").html(data.scheduleBlock);
            $("#chatContainer").html(data.chatContainer);
            $("#pageHeader").html(data.pageHeader);
            $("#streamContainer").fitVids();
            $('.ttip, [data-toggle="tooltip"]').tooltip();
            $('html, body').animate({
                scrollTop: $("#streamContainer").offset().top - 60
            }, 1000);
            window.liveUpdates = 1;

        });
    }


    function refreshInfo(id)
    {
        $.getJSON("/ajax/refreshmatch/" + id, function(data)
        {
            var scrl = $(document).scrollTop();

            $("#streamContainer").html(data.streamContainer);
            $("#chatContainer").html(data.chatContainer);
            $("#scheduleBlock").html(data.scheduleBlock);
            $("#pageHeader").html(data.pageHeader);
            $("#streamContainer").fitVids();
            $('.ttip, [data-toggle="tooltip"]').tooltip();
            $('body').scrollTop(scrl);

        });
    }

    function refreshTitleSchedule(id)
    {
        $.getJSON("/ajax/refreshmatch/" + id, function(data)
        {

            $("#scheduleBlock").height($("#scheduleBlock").height());
            $("#scheduleBlock").html(data.scheduleBlock);
            $("#scheduleBlock").css('height', 'auto');

            $("#pageHeader").height($("#pageHeader").height());
            $("#pageHeader").html(data.pageHeader);
            $("#pageHeader").css('height', 'auto');

            $('.ttip, [data-toggle="tooltip"]').tooltip();
            $("[data-toggle=popover]").popover();

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
            window.fantasyTeams[posX].fantasyName = teamName;

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
            $("#fantasyTeamSelect").select2().select2('val', posX);

        }

    }

    function gameToggle(ele)
    {
        if($(ele).is('i'))
        {
            $(ele).parent().parent().children('div').fadeToggle();
            $(ele).parent().parent().children('button').fadeToggle();
        }
        else if($(ele).is('button'))
        {
            $(ele).parent().children('div').fadeToggle();
            $(ele).parent().children('button').fadeToggle();

            ele = $(ele).parent().children('h1').children();
        }

        if($(ele).hasClass('fa-angle-double-up'))
        {
            $(ele).removeClass('fa-angle-double-up');
            $(ele).addClass('fa-angle-double-down');
        }
        else
        {
            $(ele).removeClass('fa-angle-double-down');
            $(ele).addClass('fa-angle-double-up');
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

                if(qIndex == 'fantasyName')
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
        window.liveUpdates = updatesRes;
        var spoilersRes = ($('[id="{{ Config::get('cookie.spoilers') }}"]').is(':checked') ? 1 : 0);

        $.post('/ajax/settings/',
        {
            timezone: $('[id="{{ Config::get('cookie.timezone') }}"]').select2('val'),
            updates: updatesRes,
            spoilers: spoilersRes,
            player: $('[id="{{ Config::get('cookie.player') }}"]').select2('val'),
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
