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

<div class="md-modal colored-header custom-width" id="settingsModal" style="display:none;">
    <div class="md-content">
      <div class="modal-header">
        <h3>My Settings</h3>
        <button type="button" class="close md-close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body form" style="padding-bottom:0px;padding-left: 10px;padding-top: 15px;padding-right: 10px;">

      <div class="tab-container tab-left">
        <ul class="nav nav-tabs flat-tabs">
          <li class="active"><a href="#tab3-1" data-toggle="tab"><i class="fa fa-edit"></i></a></li>
          <li class=""><a href="#tab3-2" data-toggle="tab"><i class="fa fa-users"></i></a></li>
        </ul>
        <div class="tab-content" style="padding-bottom: 0px;margin-bottom: 10px;">

          <div class="tab-pane cont fade active in" id="tab3-1">
                <div class="form-group" style="margin-top:15px;">
                 <label style="margin-right:89px;">Timezone</label>
                 <select class="fancySelect" style="width:250px;" placeholder="Select a timezone">
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


          <div class="tab-pane cont fade" id="tab3-2">

            <form class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label" style="margin-top:-8px;">Fantasy Team</label>
                    <div class="col-sm-10">
                        <select class="fancySelect" style="width:100%;" placeholder="Select a fantasy team">
                            <option></option>
                            <option value="1">Master Baiterz</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Top</label>
                    <div class="col-sm-10">
                        <select class="fancySelect" style="width:100%;" placeholder="Dyrus">
                            <option></option>
                            {{ FPlayer::selectOptions('Top Lane') }}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Jungle</label>
                    <div class="col-sm-10">
                        <select class="fancySelect" style="width:100%;" placeholder="Amazing">
                            <option></option>
                            {{ FPlayer::selectOptions('Jungler') }}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Mid</label>
                    <div class="col-sm-10">
                        <select class="fancySelect" style="width:100%;" placeholder="Bjergsen">
                            <option></option>
                            {{ FPlayer::selectOptions('Mid Lane') }}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">ADC</label>
                    <div class="col-sm-10">
                        <select class="fancySelect" style="width:100%;" placeholder="WildTurtle">
                            <option></option>
                            {{ FPlayer::selectOptions('AD Carry') }}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Support</label>
                    <div class="col-sm-10">
                        <select class="fancySelect" style="width:100%;" placeholder="Lustboy">
                            <option></option>
                            {{ FPlayer::selectOptions('Support') }}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Flex</label>
                    <div class="col-sm-10">
                        <select class="fancySelect" style="width:100%;" placeholder="HotshotGG">
                            <option></option>
                            {{ FPlayer::allOptions() }}
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Team</label>
                    <div class="col-sm-10">
                        <select class="fancySelect" style="width:100%;" placeholder="Fnatic">
                            <option></option>
                            {{ FTeam::allOptions() }}
                        </select>
                    </div>
                </div>
            </form>

          </div>
        </div>
      </div>


      </div>
      <div class="modal-footer" style="margin-top: 0px;padding-top: 5px;padding-bottom: 10px;border:none;">
        <button type="button" class="btn btn-default btn-flat md-close" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary btn-flat md-close" data-dismiss="modal">Save</button>
      </div>
    </div>
</div>
