<?php include_once 'header.php'; ?>


<div id="login-form">
    <form name="frmUser" id="frmUser" method="post">
        <div class="frm-field">
            <label>Username</label>
            <div><input class="logininput" type="text" name="username" id="username"></div>
        </div>
        <div class="frm-field">
            <label>Password</label>
            <div><input class="logininput" type="password" name="password" id="password"></div>
        </div>
        <div class="frm-field">
            <div><input type="submit" name="submit" value="Submit" class="generateButton"><span id="error"></span></div>
        </div>
    </form>
</div>
<script>
    $('#frmUser').submit(function (event) {
        event.preventDefault();

        if (!$("#username").val() || !$("#password").val()) {
            $("#error").html("Please fill in username and password");
            return false;
        }
        $.ajax({
            url: "../server/script.php",
            type: "POST",
            data: {type: 'loginagent', username: $("#username").val(), password: $("#password").val()},
            success: function (data) {
                if (data) {
                    window.location.href = "agent.php"
                } else {
                    $("#error").html("Invalid Credentials");
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    });
</script>
<?php
include_once 'footer.php';
