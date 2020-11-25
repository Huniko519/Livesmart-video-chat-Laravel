<?php
session_start();


if (isset($_GET['wplogin']) && isset($_GET['url'])) {
    include_once '../server/connect.php';
    try {
        $stmt = $pdo->prepare('SELECT * FROM ' . $dbPrefix . 'agents WHERE username = ?');
        $stmt->execute([$_GET['wplogin']]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION["tenant"] = ($user['is_master']) ? 'lsv_mastertenant' : $user['tenant'];
            $_SESSION["username"] = $user['username'];
            $actual_link = base64_decode($_GET['url']);
        } else {
            header("Location:loginform.php");
        }
    } catch (Exception $e) {
        return false;
    }
} else {
    $actual_link = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $actual_link = str_replace('admin/agent.php', '', $actual_link);
}

if (empty($_SESSION["username"])) {
    header("Location:loginform.php");
}


include_once 'header.php';
?>

<div class="content">
    <?php
    if (!isset($_GET['wplogin']) && !isset($_GET['url'])) {
        ?>

        <div class="welcome">Welcome <div id="adminmodal" class="modal"><form method="POST" name="frmAdminEdit" id="frmAdminEdit"><div class="frm-field"><label>First Name</label><div><input autocomplete="off" class="logininput" value="<?php echo $_SESSION["agent"]["first_name"]; ?>" type="text" name="first_name_admin" id="first_name_admin"></div></div><div class="frm-field"><label>Last Name</label><div><input autocomplete="off" class="logininput" value="<?php echo $_SESSION["agent"]["last_name"]; ?>" type="text" name="last_name_admin" id="last_name_admin"></div></div><div class="frm-field"><label>Email</label><div><input autocomplete="off" class="logininput" value="<?php echo $_SESSION["agent"]["email"]; ?>" type="text" name="email_admin" id="email_admin"></div></div><div class="frm-field"><label>tenant</label><div><input autocomplete="off" class="logininput" value="<?php echo $_SESSION["agent"]["tenant"]; ?>" type="text" name="tenant_admin" id="tenant_admin"></div></div><div class="frm-field"><label>Password (leave blank for no change)</label><div><input autocomplete="new-password" class="logininput" type="password" name="password_admin" id="password_admin"></div></div><div class="frm-field"><div><input autocomplete="off" type="submit" value="Save" class="generateButton"></div></div></form><a href="#" rel="modal:close">Close</a></div><a href="#adminmodal" rel="modal:open"><?php echo $_SESSION["username"]; ?></a>. <a class="logout" href="logout.php" tite="Logout">Logout</a></div>
        <script>
            $('#frmAdminEdit').submit(function (event) {
                $.ajax({
                    type: 'POST',
                    url: '../server/script.php',
                    data: {'type': 'editadmin', 'agentId': <?php echo $_SESSION["agent"]["agent_id"]; ?>, 'firstName': $('#first_name_admin').val(), 'lastName': $('#last_name_admin').val(), 'tenant': $('#tenant_admin').val(), 'email': $('#email_admin').val(), 'password': $('#password_admin').val()}
                })
                        .done(function (data) {
                            if (data) {
                                location.reload();
                            }
                        })
                        .fail(function () {
                            console.log(false);
                        });
            });
        </script>
        <?php
    }
    ?>
    <div id="statusbar"></div>

    <div id="tabs">

        <ul>
            <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                <li><a href="#tabs-agents">Agents</a></li>
            <?php } ?>
            <li><a href="#tabs-visitors">Visitors</a></li>
            <li><a href="#tabs-rooms">Rooms</a></li>
            <li><a href="#tabs-chats">Chats</a></li>
            <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
                <li><a href="#tabs-users">Users</a></li>
            <?php } ?>
        </ul>
        <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
            <div id="tabs-agents">
                <script>

                    function addagent() {
                        $.ajax({
                            type: 'POST',
                            url: '../server/script.php',
                            data: {'type': 'addagent', 'username': $('#usernamenew').val(), 'password': $('#passwordnew').val(), 'firstName': $('#first_namenew').val(), 'lastName': $('#last_namenew').val(), 'tenant': $('#tenantnew').val(), 'email': $('#emailnew').val()}
                        })
                                .done(function (data) {
                                    if (data) {
                                        location.reload();
                                    }
                                })
                                .fail(function (e) {
                                    //console.log(e);
                                });
                    }
                </script>
                <div id="exagentnew" class="modal"><div class="frm-field"><label>Username</label><div><input autocomplete="off" class="logininput" type="text" name="usernamenew" id="usernamenew"></div></div><div class="frm-field"><label>Password</label><div><input autocomplete="new-password" class="logininput" type="password" name="passwordnew" id="passwordnew"></div></div><div class="frm-field"><label>First Name</label><div><input autocomplete="off" class="logininput" type="text" name="first_namenew" id="first_namenew"></div></div><div class="frm-field"><label>Last Name</label><div><input autocomplete="off" class="logininput" type="text" name="last_namenew" id="last_namenew"></div></div><div class="frm-field"><label>Email</label><div><input autocomplete="off" class="logininput" type="text" name="emailnew" id="emailnew"></div></div><div class="frm-field"><label>tenant</label><div><input autocomplete="off" class="logininput" type="text" name="tenantnew" id="tenantnew"></div></div><div class="frm-field"><div><input type="button" id="adduserbutton" onclick="addagent();" value="Save" class="generateButton"></div></div><a href="#" rel="modal:close">Close</a></div>

                <a id="addAgent" href="#exagentnew" rel="modal:open" class="generateButton">Add Agent</a>
                <table  id="agents_table" class="table table-bordered table-hover display nowrap dataTable dtr-inline collapsed">
                    <thead>
                        <tr>
                            <th class="text-center">Username</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Tenant</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
                <script>

                    jQuery(document).ready(function ($) {

                        $.ajax({
                            type: 'POST',
                            url: '../server/script.php',
                            data: {'type': 'getagents'}
                        })
                                .done(function (data) {
                                    if (data) {
                                        var result = JSON.parse(data);
                                        $.each(result, function (i, item) {
                                            var form = '<form method="POST" name="frmUser' + item.agent_id + '" id="frmUser' + item.agent_id + '"><div class="frm-field"><label>First Name</label><div><input autocomplete="off" class="logininput" value="' + item.first_name + '" type="text" name="first_name' + item.agent_id + '" id="first_name' + item.agent_id + '"></div></div><div class="frm-field"><label>Last Name</label><div><input autocomplete="off" class="logininput" value="' + item.last_name + '" type="text" name="last_name' + item.agent_id + '" id="last_name' + item.agent_id + '"></div></div><div class="frm-field"><label>Email</label><div><input autocomplete="off" class="logininput" value="' + item.email + '" type="text" name="email' + item.agent_id + '" id="email' + item.agent_id + '"></div></div><div class="frm-field"><label>tenant</label><div><input autocomplete="off" class="logininput" value="' + item.tenant + '" type="text" name="tenant' + item.agent_id + '" id="tenant' + item.agent_id + '"></div></div><div class="frm-field"><label>Password (leave blank for no change)</label><div><input autocomplete="new-password" class="logininput" type="password" name="password' + item.agent_id + '" id="password' + item.agent_id + '"></div></div><div class="frm-field"><div><input autocomplete="off" type="submit" value="Save" class="generateButton"></div></div></form>';
                                            var divModal = '<div id="exagent' + item.agent_id + '" class="modal">' + form + '<a href="#" rel="modal:close">Close</a></div>';
                                            if (item.is_master == 1) {
                                                var deleteEditLink = divModal + '<a href="#exagent' + item.agent_id + '" rel="modal:open">Edit</a>';
                                            } else {
                                                deleteEditLink = divModal + '<a href="#exagent' + item.agent_id + '" rel="modal:open">Edit</a> | <a href="javascript:void(0);" id="deleteagent' + item.agent_id + '">Delete</a>';
                                            }
                                            $('<tr>').append(
                                                    $('<td>').text(item.username),
                                                    $('<td>').text(item.first_name + ' ' + item.last_name),
                                                    $('<td>').text(item.tenant),
                                                    $('<td>').text(item.email),
                                                    $('<td>').html(deleteEditLink)
                                                    ).appendTo('#agents_table');
                                            $('#deleteagent' + item.agent_id).on('click', function () {
                                                deleteItem(item.agent_id, 'agent');
                                            });
                                            $('#frmUser' + item.agent_id).submit(function (event) {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '../server/script.php',
                                                    data: {'type': 'editagent', 'agentId': item.agent_id, 'firstName': $('#first_name' + item.agent_id).val(), 'lastName': $('#last_name' + item.agent_id).val(), 'tenant': $('#tenant' + item.agent_id).val(), 'email': $('#email' + item.agent_id).val(), 'password': $('#password' + item.agent_id).val()}
                                                })
                                                        .done(function (data) {
                                                            if (data) {
                                                                location.reload();
                                                            }
                                                        })
                                                        .fail(function () {
                                                            console.log(false);
                                                        });
                                            });
                                        });
                                        $('#agents_table').dataTable({
                                            "pagingType": "full_numbers"
                                        });

                                    }
                                })
                                .fail(function (e) {
                                    console.log(e);
                                });

                    });
                </script>

            </div>
        <?php } ?>
        <div id="tabs-visitors">
            <div id="visitors"></div> 
            
        </div>
        <div id="tabs-rooms">

            <div class="divGenerate">
                <table style="border:none; border-collapse: collapse; text-align: right">
                    <tr>
                        <td colspan="6" style="border-right: 1px solid #000;"></td>    
                        <td colspan="6" style="text-align: center; border-right: 1px solid #000;">Disable</td>
                        <td style="text-align: center; ">Auto Accept</td>
                    </tr>
                    <tr>
                        <td>Date: </td><td><input autocomplete="off" type="text" id="datetime" /></td>
                        <td>Agent Name: </td><td><input autocomplete="off" type="text" id="names" /></td>
                        <td>Visitor Short URL: </td><td style="border-right: 1px solid #000;"><input autocomplete="off" type="text" id="shortvisitor" /></td>
                          
                        <td>Video: </td><td><input style="width: 15px;" autocomplete="off" type="checkbox" id="disableVideo" value="1" /></td>
                        <td>Audio: </td><td><input style="width: 15px;" autocomplete="off" type="checkbox" id="disableAudio" value="1" /></td>
                        <td>File Transfer: </td><td style="border-right: 1px solid #000;"><input style="width: 15px;" autocomplete="off" type="checkbox" id="disableTransfer" value="1" /></td>
                        <td>Video: </td><td><input style="width: 15px;" autocomplete="off" type="checkbox" id="autoAcceptVideo" value="1" /></td>
                    </tr>
                    <tr>
                        <td>Duration: </td><td><select name="duration" id="duration"><option value="">-</option><option value="15">15</option><option value="30">30</option><option value="45">45</option></select></td>
                        <td>Agent Short URL: </td><td><input autocomplete="off" type="text" id="shortagent" /></td>
                        <td>Password: </td><td style="border-right: 1px solid #000;"><input autocomplete="new-password" type="password" id="roomPass" /></td>
                        <td>ScreenShare: </td><td><input style="width: 15px;" autocomplete="off" type="checkbox" id="disableScreenShare" value="1" /></td>
                        <td>Whiteboard: </td><td><input style="width: 15px;" autocomplete="off" type="checkbox" id="disableWhiteboard" value="1" /></td>
                        <td></td>
                        <td style="border-right: 1px solid #000;"></td>
                        <td>Audio: </td><td><input style="width: 15px;" autocomplete="off" type="checkbox" id="autoAcceptAudio" value="1" /></td>
                    </tr>
                    <tr>
                        <td>Room: </td><td ><input autocomplete="off" type="text" id="roomName" /></td>     
                        <td>Visitor Name: </td><td><input autocomplete="off" type="text" id="visitorName" /></td>
                        <td>Config file: </td><td style="border-right: 1px solid #000;"><input autocomplete="off" type="text" id="config" /></td>
                        <td colspan="13">
                            <div id="generateBroadcastLinkModal" style="max-width: 700px !important; word-wrap: break-word;" class="modal">Broadcaster URL is opened in a new tab and visitor URL is stored in your clipboard, so you can send to your attendees. <br/> You can copy it from here: <br/> [generateBroadcastLink]</div><a href="#" id="generateBroadcastLink" class="generateButton">Start Broadcast</a>
                            <a href="#" id="saveLink" class="generateButton">Save Room</a> 
                            <div id="generateLinkModal" style="max-width: 700px !important; word-wrap: break-word;" class="modal">Agent URL is opened in a new tab and visitor URL is stored in your clipboard, so you can send to your attendees. <br/> You can copy it from here: <br/> [generateLink]</div><a href="#" id="generateLink" class="generateButton">Generate Video</a>
                        </td>
                    </tr>
                </table>
            </div>

            <table  id="rooms_table" class="table table-bordered table-hover display nowrap dataTable dtr-inline collapsed">
                <thead>
                    <tr>
                        <th class="text-center">Room</th>
                        <th class="text-center">Agent</th>
                        <th class="text-center">Visitor</th>
                        <th class="text-center">Agent URL</th>
                        <th class="text-center">Visitor URL</th>
                        <th class="text-center">Date / Duration</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>


            </table>
            <script>

                var agentUrl, visitorUrl, sessionId, shortAgentUrl, shortVisitorUrl, agentBroadcastUrl, viewerBroadcastLink, shortAgentUrl_broadcast, shortVisitorUrl_broadcast;

                jQuery(document).ready(function ($) {

                    $('#saveLink').on('click', function () {
                        generateLink();
                        var datetime = ($('#datetime').val()) ? new Date($('#datetime').val()).toISOString() : '';
                        $.ajax({
                            type: 'POST',
                            url: '../server/script.php',
                            data: {'type': 'scheduling', 'is_active':1, 'agentId': agentId, 'agent': $('#names').val(), 'agenturl': agentUrl, 'visitor': $('#visitorName').val(), 'visitorurl': visitorUrl,
                                'password': $('#roomPass').val(), 'session': sessionId, 'datetime': datetime, 'duration': $('#duration').val(), 'shortVisitorUrl': shortVisitorUrl, 'shortAgentUrl': shortAgentUrl,
                                'agenturl_broadcast': agentBroadcastUrl, 'visitorurl_broadcast': viewerBroadcastLink, 'shortVisitorUrl_broadcast': shortVisitorUrl_broadcast, 'shortAgentUrl_broadcast': shortAgentUrl_broadcast}
                        })
                                .done(function (data) {
                                    if (data == 200) {
                                        location.reload();
                                    } else {
                                        alert(data);
                                    }
                                })
                                .fail(function () {
                                    console.log('failed');
                                });
                    });



                    $('#generateLink').on('click', function () {
                        generateLink(false);
                        window.open(agentUrl);
                        var text = $('#generateLinkModal').html();
                        $('#generateLinkModal').html(text.replace('[generateLink]', visitorUrl));
                        $('#generateLinkModal').modal('toggle');
                    });

                    $('#generateLinkModal').on($.modal.CLOSE, function () {
                        var text = $('#generateLinkModal').html();
                        $('#generateLinkModal').html(text.replace(visitorUrl, '[generateLink]'));
                    });

                    $('#generateBroadcastLink').on('click', function () {
                        generateLink(true);
                        window.open(agentUrl);
                        var text = $('#generateBroadcastLinkModal').html();
                        $('#generateBroadcastLinkModal').html(text.replace('[generateBroadcastLink]', viewerBroadcastLink));
                        $('#generateBroadcastLinkModal').modal('toggle');
                    });

                    $('#generateBroadcastLinkModal').on($.modal.CLOSE, function () {
                        var text = $('#generateBroadcastLinkModal').html();
                        $('#generateBroadcastLinkModal').html(text.replace(viewerBroadcastLink, '[generateBroadcastLink]'));
                    });

                    var d = new Date();
                    $('#datetime').datetimepicker({
                        timeFormat: 'h:mm TT',
                        stepHour: 1,
//                        stepMinute: 15,
                        controlType: 'select',
                        hourMin: 8,
                        hourMax: 21,
                        minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), 0),
                        oneLine: true
                    });


                    $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: {'type': 'getrooms', 'agentId': agentId}
                    })
                            .done(function (data) {
                                if (data) {
                                    var result = JSON.parse(data);
                                    var getCurrentDateFormatted = function (date) {
                                        var currentdate = new Date(date);
                                        if (currentdate.getDate()) {
                                            return ('0' + currentdate.getDate()).slice(-2) + "/"
                                                    + ('0' + (currentdate.getMonth() + 1)).slice(-2) + "/"
                                                    + currentdate.getFullYear() + " "
                                                    + ('0' + currentdate.getHours()).slice(-2) + '.' + ('0' + currentdate.getMinutes()).slice(-2);
                                        } else {
                                            return '';
                                        }
                                    };

                                    $.each(result, function (i, item) {
                                        var datetimest = '';
                                        if (item.datetime) {
                                            datetimest = getCurrentDateFormatted(item.datetime) + ' / ' + item.duration;
                                        }
                                        $('<tr>').append(
                                                $('<td>').text(item.roomId),
                                                $('<td>').text(item.agent),
                                                $('<td>').text(item.visitor),
                                                $('<td>').html('<a target="_blank" href="' + item.agenturl + '"><?php echo $actual_link;?>' + item.shortagenturl + '</a><br/><a target="_blank" href="' + item.agenturl_broadcast + '"><?php echo $actual_link;?>' + item.shortagenturl_broadcast + '</a>'),
                                                $('<td>').html('<a target="_blank" href="' + item.visitorurl + '"><?php echo $actual_link;?>' + item.shortvisitorurl + '</a><br/><a target="_blank" href="' + item.visitorurl_broadcast + '"><?php echo $actual_link;?>' + item.shortvisitorurl_broadcast + '</a>'),
                                                $('<td>').text(datetimest),
                                                $('<td>').html('<a href="javacript:void(0);" id="deleteroom' + item.room_id + '">Delete</a>')
                                                ).appendTo('#rooms_table');
                                        $('#deleteroom' + item.room_id).on('click', function () {
                                            deleteItem(item.room_id, 'room');
                                        });
                                    });
                                    $('#rooms_table').dataTable({
                                        "pagingType": "full_numbers"
                                    });

                                }
                            })
                            .fail(function () {
                                console.log(false);
                            });

                });
            </script>

        </div>
        <div id="tabs-chats">
            <table  id="chats_table" class="table table-bordered table-hover display nowrap dataTable dtr-inline collapsed">
                <thead>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Room</th>
                        <th class="text-center">Messages</th>
                        <th class="text-center">Agent</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>


            </table>

            <script>

                jQuery(document).ready(function ($) {

                    $.ajax({
                        type: 'POST',
                        url: '../server/script.php',
                        data: {'type': 'getchats', 'agentId': agentId}
                    })
                            .done(function (data) {
                                if (data) {
                                    var result = JSON.parse(data);


                                    $.each(result, function (i, item) {
                                        var divModal = '<div id="ex' + item.room_id + '" class="modal">' + item.messages + '<a href="#" rel="modal:close">Close</a></div>';
                                        $('<tr>').append(
                                                $('<td>').text(item.date_created),
                                                $('<td>').text(item.room_id),
                                                $('<td>').html(divModal + '<a href="#ex' + item.room_id + '" rel="modal:open">Messages</a>'),
                                                $('<td>').text(item.agent),
                                                ).appendTo('#chats_table');

                                    });
                                    $('#chats_table').dataTable({
                                        "pagingType": "full_numbers",
                                        "order": [[0, 'desc']],
                                    });

                                }
                            })
                            .fail(function (e) {
                                console.log(e);
                            });

                });
            </script>
        </div>

        <?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
            <div id="tabs-users">
                <script>

                    function adduser() {
                        $.ajax({
                            type: 'POST',
                            url: '../server/script.php',
                            data: {'type': 'adduser', 'username': $('#usernameuser').val(), 'name': $('#nameuser').val(), 'password': $('#passworduser').val()}
                        })
                                .done(function (data) {
                                    if (data) {
                                        location.reload();
                                    }
                                })
                                .fail(function (e) {
                                    //console.log(e);
                                });
                    }
                </script>
                <div id="exusernew" class="modal"><div class="frm-field"><label>Name</label><div><input autocomplete="off" class="logininput" type="text" name="nameuser" id="nameuser"></div></div><div class="frm-field"><label>Username</label><div><input autocomplete="off" class="logininput" type="text" name="usernameuser" id="usernameuser"></div></div><div class="frm-field"><label>Password</label><div><input autocomplete="new-password" class="logininput" type="password" name="passworduser" id="passworduser"></div></div><div class="frm-field"><div><input type="button" id="adduserbutton" onclick="adduser();" value="Save" class="generateButton"></div></div><a href="#" rel="modal:close">Close</a></div>

                <a id="addUser" href="#exusernew" rel="modal:open" class="generateButton">Add User</a>
                <table  id="users_table" class="table table-bordered table-hover display nowrap dataTable dtr-inline collapsed">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Username</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
                <script>

                    jQuery(document).ready(function ($) {

                        $.ajax({
                            type: 'POST',
                            url: '../server/script.php',
                            data: {'type': 'getusers'}
                        })
                                .done(function (data) {
                                    if (data) {
                                        var result = JSON.parse(data);
                                        $.each(result, function (i, item) {
                                            var form = '<form method="POST" name="frmUseru' + item.user_id + '" id="frmUseru' + item.user_id + '"><div class="frm-field"><label>Name</label><div><input autocomplete="off" class="logininput" value="' + item.name + '" type="text" name="name' + item.user_id + '" id="name' + item.user_id + '"></div></div><div class="frm-field"><label>Username</label><div><input autocomplete="off" class="logininput" value="' + item.username + '" type="text" name="username' + item.user_id + '" id="username' + item.user_id + '"></div></div><div class="frm-field"><div><input type="submit" value="Save" class="generateButton"></div></div></form>';
                                            var divModal = '<div id="exuser' + item.user_id + '" class="modal">' + form + '<a href="#" rel="modal:close">Close</a></div>';
                                            var deleteEditLink = divModal + '<a href="#exuser' + item.user_id + '" rel="modal:open">Edit</a> | <a href="javascript:void(0);" id="deleteuser' + item.user_id + '">Delete</a>';

                                            $('<tr>').append(
                                                    $('<td>').text(item.name),
                                                    $('<td>').text(item.username),
                                                    $('<td>').html(deleteEditLink)
                                                    ).appendTo('#users_table');
                                            $('#deleteuser' + item.user_id).on('click', function () {
                                                deleteItem(item.user_id, 'user');
                                            });
                                            $('#frmUseru' + item.user_id).submit(function (event) {
                                                $.ajax({
                                                    type: 'POST',
                                                    url: '../server/script.php',
                                                    data: {'type': 'edituser', 'userId': item.user_id, 'name': $('#name' + item.user_id).val(), 'username': $('#username' + item.user_id).val()}
                                                })
                                                        .done(function (data) {
                                                            if (data) {
                                                                location.reload();
                                                            }
                                                        })
                                                        .fail(function () {
                                                            console.log(false);
                                                        });
                                            });
                                        });
                                        $('#users_table').dataTable({
                                            "pagingType": "full_numbers"
                                        });

                                    }
                                })
                                .fail(function (e) {
                                    console.log(e);
                                });

                    });
                </script>

            </div>
        <?php } ?>

    </div>

</div>
<div id="chats-lsv-admin"></div>
<script>
    $(function () {
        $("#tabs").tabs();
    });

    var deleteItem = function (itemid, type) {
        if (type === 'room') {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'deleteroom', 'agentId': agentId, 'roomId': itemid}
            })
                    .done(function (data) {
                        location.reload();
                    })
                    .fail(function () {
                        console.log(false);
                    });
        } else if (type === 'agent') {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'deleteagent', 'agentId': itemid}
            })
                    .done(function (data) {
                        location.reload();
                    })
                    .fail(function () {
                        console.log(false);
                    });
        } else if (type === 'user') {
            $.ajax({
                type: 'POST',
                url: '../server/script.php',
                data: {'type': 'deleteuser', 'userId': itemid}
            })
                    .done(function (data) {
                        location.reload();
                    })
                    .fail(function () {
                        console.log(false);
                    });
        }
    };
    var isAdmin = true;
    var roomId = false;
<?php if ($_SESSION["tenant"] == 'lsv_mastertenant') { ?>
        var agentId = false;
<?php } else { ?>
        var agentId = "<?php echo $_SESSION["tenant"]; ?>";
<?php } ?>
</script>
<script src="<?php echo $actual_link; ?>js/loader.v2.js" data-source_path="<?php echo $actual_link; ?>" ></script>
<?php
include_once 'footer.php';
