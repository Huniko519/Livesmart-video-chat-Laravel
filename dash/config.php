<?php
include_once 'header.php';
?>

<h1 class="h3 mb-2 text-gray-800" id="configTitle"><span data-localize="configurations"></span> - <?php
    if (isset($_GET['file'])) {
        $fileConfig = $_GET['file'] . '.json';
    } else {
        $fileConfig = 'config.json';
    }
    $fileConfig = substr($fileConfig, 0, -5);
    echo $fileConfig;
    ?></h1>
<div id="error" style="display:none;" class="alert alert-danger"></div>
<?php if ($_SESSION["tenant"] == 'lsv_mastertenant' || @$_GET['id'] == $_SESSION["agent"]['agent_id']) { ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="p-1">
                <h6 data-localize="config_info"></h6>
                <br/>
                <form class="user">

                    <div class="form-group">
                        <label for="roomName"><h6 data-localize="config_server_url"></h6></label>
                        <input type="text" class="form-control" id="appWss" aria-describedby="appWss">
                    </div>
                    <div class="form-group">
                        <label for="roomName"><h6 data-localize="config_agent_name"></h6></label>
                        <input type="text" class="form-control" id="agentName" aria-describedby="agentName">
                    </div>
                    <div class="form-group">
                        <label for="names"><h6 data-localize="config_language"></h6></label>
                        <select class="form-control" name="smartVideoLanguage" id="smartVideoLanguage">
                            <?php
                            if ($handle = opendir('../locales')) {

                                while (false !== ($entry = readdir($handle))) {
                                    
                                    if ($entry != "." && $entry != ".." && substr($entry, -3) != "zip") {
                                        $entry = substr($entry, 0, -5);
                                        echo '<option value="' . $entry . '">' . $entry . '</option>';
                                    }
                                }

                                closedir($handle);
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="names"><h6 data-localize="config_anon_user"></h6></label>
                        <input type="text" class="form-control" id="anonVisitor" aria-describedby="anonVisitor">
                    </div>
                    <div class="form-group">
                        <h6 data-localize="config_entry_form"></h6>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="entryForm_enabled">
                            <label class="custom-control-label" for="entryForm_enabled" data-localize="config_enabled"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="entryForm_required">
                            <label class="custom-control-label" for="entryForm_required" data-localize="config_required"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="entryForm_private">
                            <label class="custom-control-label" for="entryForm_private" data-localize="config_private"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="entryForm_showEmail">
                            <label class="custom-control-label" for="entryForm_showEmail" data-localize="config_email"></label>
                        </div>

                    </div>
                    <div class="form-group">
                        <h6 data-localize="config_recordings"></h6>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="recording_enabled">
                            <label class="custom-control-label" for="recording_enabled" data-localize="config_enabled"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="recording_download">
                            <label class="custom-control-label" for="recording_download" data-localize="config_download"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="recording_saveServer">
                            <label class="custom-control-label" for="recording_saveServer" data-localize="config_saveserver"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="recording_autoStart">
                            <label class="custom-control-label" for="recording_autoStart" data-localize="config_autostart"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="recording_screen">
                            <label class="custom-control-label" for="recording_screen" data-localize="config_screen"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="recording_oneWay">
                            <label class="custom-control-label" for="recording_oneWay" data-localize="config_oneway"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="recording_transcode">
                            <label class="custom-control-label" for="recording_transcode" data-localize="config_transcode"></label>
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="recording_filename" data-localize="config_filename"></label>
                            <input type="text" class="form-control" id="recording_filename" aria-describedby="recording_filename">
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="recording_recordingConstraints" data-localize="config_recordingconstraints"></label>
                            <textarea class="form-control" id="recording_recordingConstraints"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <h6 data-localize="config_whiteboard"></h6>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="whiteboard_enabled">
                            <label class="custom-control-label" for="whiteboard_enabled" data-localize="config_enabled"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="whiteboard_allowAnonymous">
                            <label class="custom-control-label" for="whiteboard_allowAnonymous" data-localize="config_allowanon"></label>
                        </div>

                    </div>
                    <div class="form-group">
                        <h6 data-localize="config_videopanel"></h6>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_greenRoom">
                            <label class="custom-control-label" for="videoScreen_greenRoom" data-localize="config_greenroom"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_videoConference">
                            <label class="custom-control-label" for="videoScreen_videoConference" data-localize="config_conferencestyle"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_onlyAgentButtons">
                            <label class="custom-control-label" for="videoScreen_onlyAgentButtons" data-localize="config_onlyagents"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_getSnapshot">
                            <label class="custom-control-label" for="videoScreen_getSnapshot" data-localize="config_snapshot"></label>
                        </div>                    
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_separateScreenShare">
                            <label class="custom-control-label" for="videoScreen_separateScreenShare" data-localize="config_separatescreenshare"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_broadcastAttendeeVideo">
                            <label class="custom-control-label" for="videoScreen_broadcastAttendeeVideo" data-localize="config_broadcastattendeevideo"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_allowOtherSee">
                            <label class="custom-control-label" for="videoScreen_allowOtherSee" data-localize="config_allowothersee"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_localFeedMirrored">
                            <label class="custom-control-label" for="videoScreen_localFeedMirrored" data-localize="config_localfeedmirrored"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_exitMeetingOnTime">
                            <label class="custom-control-label" for="videoScreen_exitMeetingOnTime" data-localize="config_exitmeetingontime"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_waitingRoom">
                            <label class="custom-control-label" for="videoScreen_waitingRoom" data-localize="config_systemmessages"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="videoScreen_enableLogs">
                            <label class="custom-control-label" for="videoScreen_enableLogs" data-localize="config_logs"></label>
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="videoScreen_videoFileStream" data-localize="config_videofilestream"></label>
                            <input type="text" class="form-control" id="videoScreen_videoFileStream" aria-describedby="videoScreen_videoFileStream">
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="videoScreen_exitMeetingDrop" data-localize="config_exitMeeting"></label>
                            <select class="form-control" name="videoScreen_exitMeetingDrop" id="videoScreen_exitMeetingDrop"><option value="1">Show entry form</option><option value="2">Go to home page</option><option value="3">Go to specific URL</option></select>
                            <input type="text" class="form-control" id="videoScreen_exitMeeting" aria-describedby="videoScreen_exitMeeting">
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="videoScreen_primaryCamera" data-localize="config_primarycamera"></label>
                            <select class="form-control" name="videoScreen_primaryCamera" id="videoScreen_primaryCamera"><option value="user">Front</option><option value="environment">Back</option></select>
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="videoScreen_videoConstraint" data-localize="config_videoconstraint"></label>
                            <textarea class="form-control" id="videoScreen_videoConstraint"></textarea>
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="videoScreen_audioConstraint" data-localize="config_audioconstraint"></label>
                            <textarea class="form-control" id="videoScreen_audioConstraint"></textarea>
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="videoScreen_screenConstraint" data-localize="config_screenshareconstraint"></label>
                            <textarea class="form-control" id="videoScreen_screenConstraint"></textarea>
                        </div>

                    </div>

                    <div class="form-group">
                        <h6 data-localize="config_serverside"></h6>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="serverSide_loginForm">
                            <label class="custom-control-label" for="serverSide_loginForm" data-localize="config_loginform"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="serverSide_chatHistory">
                            <label class="custom-control-label" for="serverSide_chatHistory" data-localize="config_chathistory"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="serverSide_feedback">
                            <label class="custom-control-label" for="serverSide_feedback" data-localize="config_feedbackform"></label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="serverSide_checkRoom">
                            <label class="custom-control-label" for="serverSide_checkRoom" data-localize="config_roomaccess"></label>
                        </div>

                    </div>
                    
                    <div class="form-group">
                        <h6 data-localize="config_speechtranslate"></h6>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="transcribe_enabled">
                            <label class="custom-control-label" for="transcribe_enabled" data-localize="config_enabled"></label>
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="transcribe_enabled" data-localize="config_language_from"></label>
                            <select class="form-control" name="transcribe_language" id="transcribe_language"><option value="af">Afrikaans (af)</option><option value="sq">Albanian (sq)</option><option value="am">Amharic (am)</option><option value="ar">Arabic (ar)</option><option value="hy">Armenian (hy)</option><option value="az">Azerbaijani (az)</option><option value="eu">Basque (eu)</option><option value="be">Belarusian (be)</option><option value="bn">Bengali (bn)</option><option value="bs">Bosnian (bs)</option><option value="bg">Bulgarian (bg)</option><option value="ca">Catalan (ca)</option><option value="ceb">Cebuano (ceb)</option><option value="ny">Chichewa (ny)</option><option value="zh">Chinese (Simplified) (zh)</option><option value="zh-TW">Chinese (Traditional) (zh-TW)</option><option value="co">Corsican (co)</option><option value="hr">Croatian (hr)</option><option value="cs">Czech (cs)</option><option value="da">Danish (da)</option><option value="nl">Dutch (nl)</option><option value="en" selected="selected">English (en)</option><option value="eo">Esperanto (eo)</option><option value="et">Estonian (et)</option><option value="tl">Filipino (tl)</option><option value="fi">Finnish (fi)</option><option value="fr">French (fr)</option><option value="fy">Frisian (fy)</option><option value="gl">Galician (gl)</option><option value="ka">Georgian (ka)</option><option value="de">German (de)</option><option value="el">Greek (el)</option><option value="gu">Gujarati (gu)</option><option value="ht">Haitian Creole (ht)</option><option value="ha">Hausa (ha)</option><option value="haw">Hawaiian (haw)</option><option value="iw">Hebrew (iw)</option><option value="hi">Hindi (hi)</option><option value="hmn">Hmong (hmn)</option><option value="hu">Hungarian (hu)</option><option value="is">Icelandic (is)</option><option value="ig">Igbo (ig)</option><option value="id">Indonesian (id)</option><option value="ga">Irish (ga)</option><option value="it">Italian (it)</option><option value="ja">Japanese (ja)</option><option value="jw">Javanese (jw)</option><option value="kn">Kannada (kn)</option><option value="kk">Kazakh (kk)</option><option value="km">Khmer (km)</option><option value="ko">Korean (ko)</option><option value="ku">Kurdish (Kurmanji) (ku)</option><option value="ky">Kyrgyz (ky)</option><option value="lo">Lao (lo)</option><option value="la">Latin (la)</option><option value="lv">Latvian (lv)</option><option value="lt">Lithuanian (lt)</option><option value="lb">Luxembourgish (lb)</option><option value="mk">Macedonian (mk)</option><option value="mg">Malagasy (mg)</option><option value="ms">Malay (ms)</option><option value="ml">Malayalam (ml)</option><option value="mt">Maltese (mt)</option><option value="mi">Maori (mi)</option><option value="mr">Marathi (mr)</option><option value="mn">Mongolian (mn)</option><option value="my">Myanmar (Burmese) (my)</option><option value="ne">Nepali (ne)</option><option value="no">Norwegian (no)</option><option value="ps">Pashto (ps)</option><option value="fa">Persian (fa)</option><option value="pl">Polish (pl)</option><option value="pt">Portuguese (pt)</option><option value="pa">Punjabi (pa)</option><option value="ro">Romanian (ro)</option><option value="ru">Russian (ru)</option><option value="sm">Samoan (sm)</option><option value="gd">Scots Gaelic (gd)</option><option value="sr">Serbian (sr)</option><option value="st">Sesotho (st)</option><option value="sn">Shona (sn)</option><option value="sd">Sindhi (sd)</option><option value="si">Sinhala (si)</option><option value="sk">Slovak (sk)</option><option value="sl">Slovenian (sl)</option><option value="so">Somali (so)</option><option value="es">Spanish (es)</option><option value="su">Sundanese (su)</option><option value="sw">Swahili (sw)</option><option value="sv">Swedish (sv)</option><option value="tg">Tajik (tg)</option><option value="ta">Tamil (ta)</option><option value="te">Telugu (te)</option><option value="th">Thai (th)</option><option value="tr">Turkish (tr)</option><option value="uk">Ukrainian (uk)</option><option value="ur">Urdu (ur)</option><option value="uz">Uzbek (uz)</option><option value="vi">Vietnamese (vi)</option><option value="cy">Welsh (cy)</option><option value="xh">Xhosa (xh)</option><option value="yi">Yiddish (yi)</option><option value="yo">Yoruba (yo)</option><option value="zu">Zulu (zu)</option></select>
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="transcribe_languageTo" data-localize="config_secondlanguage"></label>
                            <select class="form-control" name="transcribe_languageTo" id="transcribe_languageTo"><option value="af">Afrikaans (af)</option><option value="sq">Albanian (sq)</option><option value="am">Amharic (am)</option><option value="ar">Arabic (ar)</option><option value="hy">Armenian (hy)</option><option value="az">Azerbaijani (az)</option><option value="eu">Basque (eu)</option><option value="be">Belarusian (be)</option><option value="bn">Bengali (bn)</option><option value="bs">Bosnian (bs)</option><option value="bg">Bulgarian (bg)</option><option value="ca">Catalan (ca)</option><option value="ceb">Cebuano (ceb)</option><option value="ny">Chichewa (ny)</option><option value="zh">Chinese (Simplified) (zh)</option><option value="zh-TW">Chinese (Traditional) (zh-TW)</option><option value="co">Corsican (co)</option><option value="hr">Croatian (hr)</option><option value="cs">Czech (cs)</option><option value="da">Danish (da)</option><option value="nl">Dutch (nl)</option><option value="en" selected="selected">English (en)</option><option value="eo">Esperanto (eo)</option><option value="et">Estonian (et)</option><option value="tl">Filipino (tl)</option><option value="fi">Finnish (fi)</option><option value="fr">French (fr)</option><option value="fy">Frisian (fy)</option><option value="gl">Galician (gl)</option><option value="ka">Georgian (ka)</option><option value="de">German (de)</option><option value="el">Greek (el)</option><option value="gu">Gujarati (gu)</option><option value="ht">Haitian Creole (ht)</option><option value="ha">Hausa (ha)</option><option value="haw">Hawaiian (haw)</option><option value="iw">Hebrew (iw)</option><option value="hi">Hindi (hi)</option><option value="hmn">Hmong (hmn)</option><option value="hu">Hungarian (hu)</option><option value="is">Icelandic (is)</option><option value="ig">Igbo (ig)</option><option value="id">Indonesian (id)</option><option value="ga">Irish (ga)</option><option value="it">Italian (it)</option><option value="ja">Japanese (ja)</option><option value="jw">Javanese (jw)</option><option value="kn">Kannada (kn)</option><option value="kk">Kazakh (kk)</option><option value="km">Khmer (km)</option><option value="ko">Korean (ko)</option><option value="ku">Kurdish (Kurmanji) (ku)</option><option value="ky">Kyrgyz (ky)</option><option value="lo">Lao (lo)</option><option value="la">Latin (la)</option><option value="lv">Latvian (lv)</option><option value="lt">Lithuanian (lt)</option><option value="lb">Luxembourgish (lb)</option><option value="mk">Macedonian (mk)</option><option value="mg">Malagasy (mg)</option><option value="ms">Malay (ms)</option><option value="ml">Malayalam (ml)</option><option value="mt">Maltese (mt)</option><option value="mi">Maori (mi)</option><option value="mr">Marathi (mr)</option><option value="mn">Mongolian (mn)</option><option value="my">Myanmar (Burmese) (my)</option><option value="ne">Nepali (ne)</option><option value="no">Norwegian (no)</option><option value="ps">Pashto (ps)</option><option value="fa">Persian (fa)</option><option value="pl">Polish (pl)</option><option value="pt">Portuguese (pt)</option><option value="pa">Punjabi (pa)</option><option value="ro">Romanian (ro)</option><option value="ru">Russian (ru)</option><option value="sm">Samoan (sm)</option><option value="gd">Scots Gaelic (gd)</option><option value="sr">Serbian (sr)</option><option value="st">Sesotho (st)</option><option value="sn">Shona (sn)</option><option value="sd">Sindhi (sd)</option><option value="si">Sinhala (si)</option><option value="sk">Slovak (sk)</option><option value="sl">Slovenian (sl)</option><option value="so">Somali (so)</option><option value="es">Spanish (es)</option><option value="su">Sundanese (su)</option><option value="sw">Swahili (sw)</option><option value="sv">Swedish (sv)</option><option value="tg">Tajik (tg)</option><option value="ta">Tamil (ta)</option><option value="te">Telugu (te)</option><option value="th">Thai (th)</option><option value="tr">Turkish (tr)</option><option value="uk">Ukrainian (uk)</option><option value="ur">Urdu (ur)</option><option value="uz">Uzbek (uz)</option><option value="vi">Vietnamese (vi)</option><option value="cy">Welsh (cy)</option><option value="xh">Xhosa (xh)</option><option value="yi">Yiddish (yi)</option><option value="yo">Yoruba (yo)</option><option value="zu">Zulu (zu)</option></select>
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="transcribe_direction" data-localize="config_secondlanguage" data-localize="config_direction"></label>
                            <select class="form-control" name="transcribe_direction" id="transcribe_direction"><option value="agent" data-localize="config_fromagent"></option><option value="visitor" data-localize="config_fromvisitor"></option><option value="both" data-localize="config_both"></option></select>
                        </div>
                        <hr>
                        <div class="custom-control">
                            <label for="transcribe_apiKey" data-localize="config_apikey"></label>
                            <input type="text" class="form-control" id="transcribe_apiKey" aria-describedby="transcribe_apiKey">
                        </div>


                    </div>
                    <hr>
                    <div class="form-group">
                        <h6 data-localize="config_stunturn"></h6>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" value="1" id="iceServers_requirePass">
                            <label class="custom-control-label" for="iceServers_requirePass" data-localize="config_encrypted"></label>
                        </div>
                        <br/>
                        <label for="videoScreen_screenConstraint" data-localize="config_stunvalues"></label>

                        <div class="custom-control">
                            <textarea class="form-control" id="iceServers" rows="8"></textarea>
                        </div>

                    </div>

                    <a href="javascript:void(0);" id="saveConfig" class="btn btn-primary btn-user btn-block">
                        Save
                    </a>
                    <hr>


                </form>

            </div>

        </div>
        <div class="col-sm-6">
            <div class="p-1">
                <h6 data-localize="config_desc">
                </h6>
                <br/>
                <div class="form-group">
                    <label for="roomName"><h6 data-localize="config_name"></h6></label>
                    <input type="text" class="form-control" id="fileName" aria-describedby="fileName">
                </div>
                <a href="javascript:void(0);" id="addConfig" class="btn btn-primary btn-user btn-block" data-localize="config_add">
                </a>
                <br/>
                <?php
                if ($handle = opendir('../config')) {
                    echo '<a href="config.php" class="btn btn-light">config</a><hr>';
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != ".." && $entry != "config.json" && substr($entry, -3) != "zip") {
                            $entry = substr($entry, 0, -5);
                            $delete = '| <a href="deleteconfig.php?file=' . $entry . '" id="deleteConfig' . $entry . '" class="btn btn-light">Delete</a>';
                            echo '<a href="config.php?file=' . $entry . '" class="btn btn-light">' . $entry . '</a>' . $delete . '<hr>';
                        }
                    }

                    closedir($handle);
                }
                ?>

            </div>
        </div>

    </div>

<?php } ?>
<?php
include_once 'footer.php';
