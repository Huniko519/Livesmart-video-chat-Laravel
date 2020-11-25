<?php
include_once 'header.php';
?>
<h1 class="h3 mb-2 text-gray-800" data-localize="update_code"></h1>



<div class="card shadow mb-4">
    <div class="card-body">

        <!-- Nested Row within Card Body -->
        <div class="row">
            <div class="col-lg-6">
                <div class="p-3">
                    <div id="error" style="display:none;" class="alert alert-danger"></div>
                    <div id="waiting" style="display: none;"><div class="text-center"><img src="img/preloader.gif" width="300"><br/><h4 data-localize="please_wait"></h4></div></div>
                    <div id="codeInfo" class="alert alert-success" style="display: none;"></div>
                    <div class="alert alert-warning" data-localize="update_info" id="update_info"></div>
                    <form class="user" method="post" id="updateform">
                        <div class="form-group">
                            <input type="text" class="form-control" id="code" aria-describedby="code" placeholder="Purchase Code">
                        </div>

                        <a href="javascript:void(0);" id="update_button" class="btn btn-primary btn-user btn-block">
                            Update
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
    
    var resetForm = function () {
        $("#updateform").show();
        $("#waiting").hide();
        $("#error").hide();
        $("#update_info").show();
        $("#updateform").prop("disabled", false);        
    };
    
    $('#update_button').click(function (event) {
        event.preventDefault();
        $("#error").hide();
        $("#codeInfo").hide();
        $("#update_info").hide();
        $("#updateform").hide();
        $("#waiting").show();

        if (!$("#code").val()) {
            $("#error").show();
            $('#error').html('<span data-localize="purchase_code_mandatory"></span>');
            resetForm();
            var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
            $('[data-localize]').localize('dashboard', opts);
            return false;
        }
        $.ajax({
            url: "https://www.new-dev.com/version/update.php",
            type: "POST",
            data: {code: $("#code").val(), url: '<?php echo $actual_link; ?>'},
            success: function (data) {
                $("#error").hide();
                if (data) {
                    if (data == '200') {
                        resetForm();
                        $("#codeInfo").show();
                        $('#codeInfo').html('<span data-localize="livesmart_nothing_toupdate"></span>');
                        var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                        $('[data-localize]').localize('dashboard', opts);
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: '../server/activate.php',
                            data: {'type': 'update', 'value': data}
                        })
                                .done(function (val) {
                                    if (val) {
                                        $("#codeInfo").show();
                                        $('#codeInfo').html('<span data-localize="livesmart_updated"></span><br/><br/>' + val + '<br/><span data-localize="livesmart_updated_zip"></span>');
                                        resetForm();
                                        var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                        $('[data-localize]').localize('dashboard', opts);
                                    } else {
                                        $("#waiting").hide();
                                        $('#error').show();
                                        $('#error').html('<span data-localize="livesmart_update_failed"></span>');
                                        var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                        $('[data-localize]').localize('dashboard', opts);
                                    }
                                })
                                .fail(function () {
                                    resetForm();
                                    $('#error').show();
                                    $('#error').html('<span data-localize="livesmart_update_failed"></span>');
                                    var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                                    $('[data-localize]').localize('dashboard', opts);
                                });
                    }
                } else {
                    resetForm();
                    $('#error').show();
                    $('#error').html('<span data-localize="invalid_purchase_code"></span>');
                    var opts = {language: 'en', pathPrefix: 'locales', loadBase: true};
                    $('[data-localize]').localize('dashboard', opts);
                }
            },
            error: function (e) {
                resetForm();
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