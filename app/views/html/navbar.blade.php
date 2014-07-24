<div id="head-nav" class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="fa fa-gear"></span>
        </button>
        <a class="navbar-brand" href="#"><span>lcshub.tv</span></a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right not-nav">
          <li class="button md-trigger" data-toggle="modal" data-target="#settingsModal"><a href="#" style="width:96px;" onclick="$('#settingsModal').toggle();"><i class="fa fa-cog">&nbsp;&nbsp;Settings</i></a></li>
        </ul>

      </div><!--/.nav-collapse -->
    </div>
</div>

<?php
function formatOffset($offset) {
        $hours = $offset / 3600;
        $remainder = $offset % 3600;
        $sign = $hours > 0 ? '+' : '-';
        $hour = (int) abs($hours);
        $minutes = (int) abs($remainder / 60);

        if ($hour == 0 AND $minutes == 0) {
            $sign = ' ';
        }
        return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) .':'. str_pad($minutes,2, '0');

}

$utc = new DateTimeZone('UTC');
$dt = new DateTime('now', $utc);
?>


<div class="md-modal colored-header custom-width" id="settingsModal" style="display:none;">
    <div class="md-content">
      <div class="modal-header">
        <h3>My Settings</h3>
        <button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body form">
        <div class="form-group" style="margin-top:0px;">
            <label style="margin-right:89px;">Timezone</label>
            <select id="easySelect" style="width:350px;">
            <option></option>
            <?php
                $opt = '';

                $regions = array('America', 'Australia', 'Europe');
                $tzs = timezone_identifiers_list();
                $optgroup = '';
                sort($tzs);
                foreach ($tzs as $tz) {
                    $z = explode('/', $tz, 2);
                    if (count($z) != 2 || !in_array($z[0], $regions)) continue;
                    if ($optgroup != $z[0]) {
                        if ($optgroup !== '') $opt .= '</optgroup>';
                        $optgroup = $z[0];
                        $opt .= '<optgroup label="' . htmlentities($z[0]) . '">';
                    }
                    $opt .= '<option value="' . htmlentities($tz) . '" label="' . htmlentities(str_replace('_', ' ', $z[1])) . '">' . htmlentities(str_replace('_', ' ', $tz)) . '</option>';
                }
                if ($optgroup !== '') $opt .= '</optgroup>';

                echo $opt;
            ?>
            </select>
        </div>
        <div class="form-group" style="margin-top:17px;">
            <label style="margin-right:100px;">Spoilers </label> <input type="radio" name="spoilers" id="spoilersRadio" checked>
        </div>
        <div class="form-group" style="margin-top:14px;">
            <label style="margin-right:70px;">Auto Update </label> <input type="radio" name="autoupdate" id="autoupdateRadio" checked>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary btn-flat md-close" data-dismiss="modal">Save</button>
      </div>
    </div>
</div>
