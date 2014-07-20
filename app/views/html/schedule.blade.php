<div class="block-flat">
    <div class="content">
        <h3 class="text-center" style="margin-top:-15px; padding-bottom:10px;">{{ substr($block->tournamentName, 0, 2) }} LCS {{ substr($block->label, strpos($block->label, " - ") + 3) }} - {{ date('M j Y', strtotime($block->dateTime)) }}</h3>
        <div class="list-group">
            @foreach($block->matches as $tempCntr => $match)
                <?php $tempZone = new DateTime($match->dateTime); $tempZone->setTimezone(new DateTimeZone(Cookie::get('timezone'))); ?>
                <li href="#" class="list-group-item" style="font-size: 20px; {{ ($tempCntr % 2 == 0 ? ' background: #F8F8F8; ' : '' ) }}"><img src="http://na.lolesports.com{{ $match->blueLogoURL }}" width='50' height='50'> {{ $match->matchName }}  <img src="http://na.lolesports.com{{ $match->redLogoURL }}" width='50' height='50'>

                <div class="btn-group pull-right">
                <button type="button" class="btn btn-{{ $match->cssClass() }} btn-lg ">{{ $tempZone->format('g:i A') }}</button>
                <button type="button" class="btn btn-{{ $match->cssClass() }} btn-lg dropdown-toggle" data-toggle="dropdown">
                  <span class="caret"></span>
                  <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                </ul>
              </div>
                </span></li>
            @endforeach
        </div>
    </div>
</div>
