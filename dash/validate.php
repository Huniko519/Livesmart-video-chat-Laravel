<?php
include_once 'header.php';
?>
<h1 class="h3 mb-2 text-gray-800" data-localize="activation"></h1>



<div class="card shadow mb-4">
    <div class="card-body">

        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-6">
                <div class="p-3">
                    <div id="error" style="display:none;" class="alert alert-danger"></div>
                    <div id="codeInfo" class="alert alert-success" style="display: none;"></div>
                    <div class="alert alert-warning" data-localize="fillin_purchase_code"></div>
                    <form class="user" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="code" aria-describedby="code" placeholder="Purchase Code">
                        </div>

                        <a href="javascript:void(0);" id="loginbutton" class="btn btn-primary btn-user btn-block">
                            Check
                        </a>
                        <hr>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>



<?php
include_once 'footer.php';
?>



<script>
    $('#loginbutton').click(function (event) {
        event.preventDefault();
        $("#error").hide();
        $("#codeInfo").hide();
        if (!$("#code").val()) {
            $("#error").show();
            $('#error').html('<span data-localize="purchase_code_mandatory"></span>');
            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
            $('[data-localize]').localize('dashboard', opts);
            return false;
        }
        $.ajax({
            url: "https://www.new-dev.com/version/activate.php",
            type: "POST",
            data: {code: $("#code").val(), url: '<?php echo $actual_link; ?>'},
            success: function (data) {
                $("#error").hide();
                if (data) {
                    $.ajax({
                        type: 'POST',
                        url: '../server/activate.php',
                        data: {'type': 'setpk', 'value': data}
                    })
                            .done(function () {
                                $("#codeInfo").show();
                                $('#codeInfo').html('<span data-localize="livesmart_activated"></span>');
                                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                $('[data-localize]').localize('dashboard', opts);
                                setTimeout(function () {
                                    window.location = 'dash.php';
                                }, 3000);
                            })
                            .fail(function () {
                                $('#error').show();
                                $('#error').html('<span data-localize="livesmart_not_activated"></span>');
                                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                $('[data-localize]').localize('dashboard', opts);
                            });
                } else {
                    $('#error').show();
                    $('#error').html('<span data-localize="invalid_purchase_code"></span>');
                    var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                    $('[data-localize]').localize('dashboard', opts);
                }
            },
            error: function (e) {
                $('#error').show();
                $('#error').html('<span data-localize="invalid_purchase_code"></span>');
                var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                $('[data-localize]').localize('dashboard', opts);
            }
        });
    });
</script>
</body>

</html>