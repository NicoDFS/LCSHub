<?php $tempZone = new DateTime($block->dateTime); $tempZone->setTimezone(new DateTimeZone($block->timezone())); ?>

<div class="col-md-12">
    <div class="block-flat" style="border: 1px solid #DDD; margin-bottom:-15px; padding-bottom:5px;">
        <div class="content">
            <h3 class="text-center" style="margin-top:-15px; padding-bottom:10px;">
                @if($block->previousBlocks())
                    <i id='prevBlocks' onclick="getBlock('{{ $block->id }}','prev')" class="fa  fa-angle-double-left" style="cursor: pointer; font-size: 26px;float: left;opacity: .9;" title="Previous" data-toggle='tooltip'></i>
                @endif

                @if(!$block->isLatestBlock() && $block->isFutureBlock())
                     <i id='currentBlock' onclick="getBlock('current')" class="fa fa-reply blueHover" style="float: left;margin-left: 25px;margin-top: 6px;font-size: 15px; cursor: pointer;" title="Jump to Current" data-toggle='tooltip'></i>
                @endif

                {{ $block->label }} - {{ $tempZone->format('M j, Y') }}

                @if($block->futureBlocks())
                    <i id='nextBlocks' onclick="getBlock('{{ $block->id }}','next')" class="fa  fa-angle-double-right" style="cursor: pointer; font-size: 26px;float: right;opacity: .9;" title="Next" data-toggle='tooltip'></i>
                @endif

                @if(!$block->isLatestBlock() && !$block->isFutureBlock())
                    <i id='currentBlock' onclick="getBlock('current')" class="fa fa-share blueHover" style="float: right;margin-right: 37px;margin-top: 6px;font-size: 15px; cursor: pointer;" title="Jump to Current" data-toggle='tooltip'></i>
                @endif
            </h3>

            <div class="list-group">

                @include('html.team.schedule', array('block' => $block))

            </div>
        </div>
    </div>
</div>
