<?php
include_once 'header.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="roomTitle" data-localize="room"></h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>

<div class="row">
    <div class="col-sm-6">
        <div class="p-1">

            <form class="user">

                <div class="form-group">
                    <label for="roomName"><h6 data-localize="room_id"></h6></label>
                    <input type="text" class="form-control" id="roomName" aria-describedby="roomName">
                </div>
                <div class="form-group">
                    <label for="names"><h6 data-localize="agent_name"></h6></label>
                    <input type="text" class="form-control" id="names" aria-describedby="names">
                </div>
                <div class="form-group">
                    <label for="visitorName"><h6 data-localize="visitor_name"></h6></label>
                    <input type="text" class="form-control" id="visitorName" aria-describedby="visitorName">
                </div>
                <div class="form-group">
                    <label for="shortagent" data-localize="agent_shorturl"><h6></h6></label>
                    <input type="text" class="form-control" id="shortagent" aria-describedby="shortagent">
                </div>
                <div class="form-group">
                    <label for="shortvisitor"><h6 data-localize="visitor_shorturl"></h6></label>
                    <input type="text" class="form-control" id="shortvisitor" aria-describedby="shortvisitor" >
                </div>
                <div class="form-group">
                    <label for="roomPass"><h6 data-localize="password"></h6></label>
                    <input type="password" class="form-control" id="roomPass" aria-describedby="roomPass" autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label for="config"><h6 data-localize="room_config"></h6></label>
                    <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                        <select class="form-control" name="config" id="config"><option value="">-</option>
                            <?php
                            if ($handle = opendir('../config')) {

                                while (false !== ($entry = readdir($handle))) {

                                    if ($entry != "." && $entry != ".." && substr($entry, -3) != "zip") {
                                        $entryValue = substr($entry, 0, -5);
                                        echo '<option value="' . $entry . '">' . $entryValue . '</option>';
                                    }
                                }

                                closedir($handle);
                            }
                            ?>
                        </select>

                    <?php } else { ?>
                        <input type="text" class="form-control" id="config" aria-describedby="config" >
                    <?php } ?>

                </div>
                <div class="form-group">
                    <label for="datetime"><h6 data-localize="date_time"></h6></label>
                    <input type="text" class="form-control" id="datetime" aria-describedby="datetime">
                </div>


                <div class="form-group">
                    <label for="duration"><h6 data-localize="duration"></h6></label>
                    <select class="form-control" name="duration" id="duration"><option value="">-</option><option value="15">15</option><option value="30">30</option><option value="45">45</option></select>
                    <span data-localize="or"></span>
                    <br/>
                    <input type="text" class="form-control w-25" id="durationtext" aria-describedby="shortagent">
                </div>

                <div class="form-group">
                    <h6 data-localize="disable"></h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableVideo">
                        <label class="custom-control-label" for="disableVideo" data-localize="disable_video"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableAudio">
                        <label class="custom-control-label" for="disableAudio" data-localize="disable_audio"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableScreenShare">
                        <label class="custom-control-label" for="disableScreenShare" data-localize="disable_screen_share"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableWhiteboard">
                        <label class="custom-control-label" for="disableWhiteboard" data-localize="disable_whiteboard"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="disableTransfer">
                        <label class="custom-control-label" for="disableTransfer" data-localize="disable_file_transfer"></label>
                    </div>
                </div>
                <div class="form-group">
                    <h6 data-localize="auto_accept"></h6>

                    <div class="custom-control custom-checkbox">

                        <input type="checkbox" class="custom-control-input" id="autoAcceptVideo">
                        <label class="custom-control-label" for="autoAcceptVideo" data-localize="auto_accept_video"></label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="autoAcceptAudio">
                        <label class="custom-control-label" for="autoAcceptAudio"data-localize="auto_accept_audio"></label>
                    </div>
                </div>
                <div class="form-group">
                    <h6 data-localize="room_active"></h6>

                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="active" checked="checked">
                        <label class="custom-control-label" for="active" data-localize="active"></label>
                    </div>
                </div>


                <a href="javascript:void(0);" id="saveRoom" class="btn btn-primary btn-user btn-block" data-localize="save">
                    Save
                </a>
                <hr>


            </form>

        </div>

    </div>
    <div class="col-sm-6">
        <div class="p-1">
            <h6 data-localize="room_info"></h6>
            <a href="javascript:void(0);" id="generateLink" class="btn btn-primary btn-user btn-block" data-localize="start_video">
                
            </a>
            <hr>
            <a href="javascript:void(0);" id="generateBroadcastLink" class="btn btn-primary btn-user btn-block" data-localize="start_broadcast">
                
            </a>
            <hr>

        </div>
    </div>

</div>

<?php
include_once 'footer.php';
