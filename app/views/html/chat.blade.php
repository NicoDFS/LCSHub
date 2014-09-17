<div class="col-md-12" style="height: 66px;" id="twitchCol">
    <div class="block-flat" id="twitchBlock" style="border: 1px solid #DDD;padding-top: 10px;text-align: center;height: 60px;">

        <span style="float:left;padding: 10px;font-size: 15px; display:none;" id="viewerCount"><i class="fa fa-user" style="color:red;"></i></span>

        @if($block->isCurrentBlock() && (!isset($block->newMatchId) || $block->isLiveMatch()))

            @if(Cookie::has(Config::get('cookie.player')))

                @if(Cookie::get(Config::get('cookie.player')) == 'twitch')

                    <div class="btn-group" style="float:right;">
                        <button type="button" class="btn btn btn-default btn-youtube" style=" padding: 2px; ">
                            <img src="https://s.jtvnw.net/jtv_user_pictures/hosted_images/GlitchIcon_purple.png" style="height: 28px;">
                        </button>
                        <button type="button" class="btn btn btn-default">{{ $block->twitchUsername() }}</button>
                    </div>

                @elseif(Cookie::get(Config::get('cookie.player')) == 'youtube')

                    <div class="btn-group" style="float:right;">
                        <button type="button" class="btn btn btn-default btn-youtube" style="padding-top: 5px; padding-bottom: 5px; padding-left: 7px; padding-right: 5px;"><i class="fa fa-youtube-play" style=" font-size: 21px; "></i></button>
                            <button type="button" class="btn btn btn-default">LoLChampSeries</button>
                    </div>

                @elseif(Cookie::get(Config::get('cookie.player')) == 'azubu')

                    <div class="btn-group" style="float:right;">
                        <button type="button" class="btn btn btn-default btn-youtube" style=" padding: 2px; ">
                            <img src="https://www.azubu.tv/favicon.ico" style="height: 28px; padding:3px;">
                        </button>
                        <button type="button" class="btn btn btn-default">Riot_Games</button>
                    </div>

                @endif

                <button type="button" class="btn btn-default btn-rad" style="margin-top:2px;" onclick="twitchToggle();"><i class="fa fa-bars"></i> &nbsp;Toggle Twitch Chat</button>

            @else
                <div class="btn-group" style="float:right;">
                    <button type="button" class="btn btn btn-default btn-youtube" style=" padding: 2px; ">
                        <img src="https://s.jtvnw.net/jtv_user_pictures/hosted_images/GlitchIcon_purple.png" style="height: 28px;">
                    </button>
                    <button type="button" class="btn btn btn-default">{{ $block->twitchUsername() }}</button>
                </div>
            @endif

        @else

            <div class="btn-group" style="float:right;">
                <button type="button" class="btn btn btn-default btn-youtube" style="padding-top: 5px; padding-bottom: 5px; padding-left: 7px; padding-right: 5px;"><i class="fa fa-youtube-play" style=" font-size: 21px; "></i></button>
                    <button type="button" class="btn btn btn-default">LoLChampSeries</button>
            </div>

        @endif

        <iframe frameborder="0" scrolling="no" id="twitchChatEmbed" src="https://secure.twitch.tv/chat/embed?channel={{ $block->twitchUsername() }}&amp;popout_chat=true" height="404" width="100%" style="display:none; margin-top:5px;"></iframe>

    </div>
</div>


<script type="text/javascript">
$(document).ready(function()
{
    updateViewerCount();

    setInterval(function() { updateViewerCount(); }, 15000);

});

function updateViewerCount()
{
    $.ajax({
        url: 'https://api.twitch.tv/kraken/streams/{{ $block->twitchUsername() }}',
        type: 'GET',
        contentType: 'application/json',
        dataType: 'jsonp',
        success: function(data) {

            if (data.stream == null)
            {
                $('#viewerCount').html("");
            }
            else
            {
                $('#viewerCount').html('<i class="fa fa-user" style="color:red;"></i> &nbsp;' + data.stream.viewers.toLocaleString("en"));
            }

            if ($("#viewerCount").css('display') == 'none')
            {
                $("#viewerCount").fadeIn('slow');
            }

        }
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
</script>
