<div class="block-flat">
    <div class="content">
        <h3 class="text-center" style="margin-top:-15px; padding-bottom:10px;">{{ substr($block->tournamentName, 0, 2) }} LCS {{ substr($block->label, strpos($block->label, " - ") + 3) }} - {{ date('M j Y') }}</h3>
        <div class="list-group">
            @foreach($block->matches as $match)
                <?php $tempZone = new DateTime($match->dateTime); $tempZone->setTimezone(new DateTimeZone(Cookie::get('timezone'))); ?>
                <li href="#" class="list-group-item" style="font-size: 20px;"><img src="http://na.lolesports.com{{ $match->blueLogoURL }}" width='50' height='50'> {{ $match->matchName }}  <img src="http://na.lolesports.com{{ $match->redLogoURL }}" width='50' height='50'> <span class="pull-right" style="padding:inherit;">{{ $tempZone->format('g:i A') }}</span></li>
            @endforeach
        </div>
    </div>
</div>
